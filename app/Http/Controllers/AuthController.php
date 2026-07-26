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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $tenant = TenantResolver::getActiveTenantModel();

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

        // 2. Check tenant scope matching (unless super_admin)
        if ($user->role !== 'super_admin' && $tenant && $user->tenant_id && (int) $user->tenant_id !== (int) $tenant->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => "This account is registered under another facility tenant. Please access your facility URL.",
            ]);
        }

        Log::info("User logged in successfully: {$user->email} [Role: {$user->role}] for Tenant: {$tenant?->name}");

        // 3. Role-based redirect
        if (in_array($user->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin'])) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('customer.my-bookings'));
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
            $customerRole = Role::where('tenant_id', '=', $tenant->id)->where('slug', '=', 'customer')->first();
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
        $user = User::where('email', '=', $request->email)->first();

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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
