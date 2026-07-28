<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;
use App\Services\TenantResolver;

class AuthController extends Controller
{
    public function showLogin()
    {
        $tenant = TenantResolver::getActiveTenantModel();
        return view('auth.login', compact('tenant'));
    }

    public function showPlatformLogin()
    {
        if (Auth::check() && (Auth::user()->isSuperAdmin() || session('is_impersonating'))) {
            return redirect()->route('superadmin.dashboard');
        }
        return view('auth.platform_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // 1. Check if account is suspended / banned
        if ($user->is_banned) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Reason: ' . ($user->ban_reason ?? 'Violation of facility policies.'),
            ]);
        }

        // 2. Super Admin Access -> Always redirect to SLTDS Platform Admin (/platform-admin)
        if ($user->isSuperAdmin()) {
            Log::info("Super Admin logged in: {$user->email}");
            return redirect()->route('superadmin.dashboard');
        }

        // 3. Tenant scope validation for regular users
        $activeTenant = TenantResolver::resolve($request);
        if ($activeTenant && $user->tenant_id && (int) $user->tenant_id !== (int) $activeTenant->id) {
            $userTenant = \App\Models\Tenant::find($user->tenant_id);
            $userTenantName = $userTenant ? $userTenant->name : 'another facility';
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login', ['tenant' => $activeTenant->slug])->withErrors([
                'email' => "This account ({$user->email}) is registered under {$userTenantName}. Please switch to {$userTenantName} or create a new account for {$activeTenant->name}.",
            ]);
        }

        // 4. Automatically bind tenant context for tenant users
        if ($user->tenant_id) {
            $userTenant = \App\Models\Tenant::find($user->tenant_id, ['*']);
            if ($userTenant) {
                TenantResolver::setActiveTenantContext($userTenant);
            }
        }

        Log::info("User logged in successfully: {$user->email} [Role: {$user->role}]");

        if (in_array($user->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'platform_admin'])) {
            $tenantSlug = $user->tenant?->slug ?? session('active_tenant_slug');
            if ($tenantSlug) {
                return redirect('/' . $tenantSlug . '/admin');
            }
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.my-bookings');
    }

    public function showRegister()
    {
        $tenant = TenantResolver::getActiveTenantModel();
        return view('auth.register', compact('tenant'));
    }

    public function register(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'tenant_id' => $tenant?->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        if ($tenant) {
            $customerRole = Role::where('tenant_id', '=', $tenant->id, 'and')->where('slug', '=', 'customer', 'and')->first(['*']);
            if ($customerRole) {
                $user->roles()->attach($customerRole);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        Log::info("[MAIL SIMULATION] Welcome email sent to registered user: {$user->email} [Tenant: {$tenant?->name}]");

        return redirect()->route('customer.my-bookings')->with('status', 'Account created successfully! Welcome to ' . ($tenant?->name ?? 'our platform'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', '=', $request->email, 'and')->first(['*']);

        if ($user) {
            $token = Str::random(32);
            $resetUrl = url('/reset-password?token=' . $token . '&email=' . urlencode($user->email));

            Log::info("==========================================");
            Log::info("[MAIL SIMULATION] PASSWORD RESET REQUEST");
            Log::info("Recipient: {$user->email}");
            Log::info("Reset Link: {$resetUrl}");
            Log::info("==========================================");

            return back()->with('status', 'A password reset link has been generated and logged to console/logs!');
        }

        return back()->withErrors(['email' => 'We could not find a user with that email address.']);
    }

    public function logout(Request $request)
    {
        // 1. Capture user role & tenant context before invalidating session
        $user = Auth::user();
        $isSuperAdmin = $user && $user->isSuperAdmin();
        $tenant = TenantResolver::getActiveTenantModel() ?? $user?->tenant;
        $tenantSlug = $request->query('tenant') 
            ?? $tenant?->slug 
            ?? session('active_tenant_slug');

        // 2. Perform Logout & Session Invalidation
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        TenantResolver::clearTenantContext();

        // 3. Platform Super-Admin Logout -> Redirect to Platform Admin Login (/platform-admin/login)
        if ($isSuperAdmin) {
            return redirect()->route('superadmin.login')
                ->with('status', 'You have been signed out from Platform Super-Admin.');
        }

        // 4. If signed out from a tenant site / staff portal, redirect to that tenant's guest view
        if ($tenantSlug) {
            return redirect('/' . $tenantSlug . '/book')
                ->with('status', 'You have been signed out successfully.');
        }

        // 5. Default fallback to parent site
        return redirect()->route('parent.home')
            ->with('status', 'You have been signed out successfully.');
    }
}
