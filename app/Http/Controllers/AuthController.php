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
            $userTenant = \App\Models\Tenant::find($user->tenant_id, ['*']);
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
            $userTenant = $user->tenant ?? TenantResolver::getActiveTenantModel();
            return redirect(TenantResolver::tenantUrl('admin.dashboard', [], $userTenant));
        }

        return redirect(TenantResolver::tenantUrl('customer.my-bookings'));
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

        return redirect(TenantResolver::tenantUrl('customer.my-bookings'))->with('status', 'Account created successfully! Welcome to ' . ($tenant?->name ?? 'our platform'));
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('status', 'If an account exists for this email, password reset instructions have been dispatched.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user?->isSuperAdmin() ?? false;
        $tenantModel = $user?->tenant ?? TenantResolver::getActiveTenantModel();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        TenantResolver::clearTenantContext();

        if ($isSuperAdmin) {
            return redirect()->route('superadmin.login')
                ->with('status', 'You have been signed out from Platform Super-Admin.');
        }

        if ($tenantModel) {
            return redirect(TenantResolver::tenantUrl('booking.index', [], $tenantModel))
                ->with('status', 'You have been signed out successfully.');
        }

        return redirect()->route('parent.home')
            ->with('status', 'You have been signed out successfully.');
    }
}
