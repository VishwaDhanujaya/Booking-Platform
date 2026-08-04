<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:owner,manager,front_desk')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to access the staff portal.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->is_banned) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is suspended: ' . ($user->ban_reason ?? 'Violation of facility policies.'),
            ]);
        }

        // Super admin bypasses all role restrictions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Enforce Tenant Boundary Check for Tenant Staff
        $activeTenant = \App\Services\TenantResolver::getActiveTenantModel();
        if ($user->tenant_id && !$user->isSuperAdmin()) {
            if ($activeTenant && (int) $user->tenant_id !== (int) $activeTenant->id) {
                abort(403, 'Cross-Tenant Access Denied: Your account belongs to ' . ($user->tenant?->name ?? 'a different facility') . ' and cannot access this domain.');
            }

            if (!$activeTenant && $user->tenant) {
                \App\Services\TenantResolver::setActiveTenantContext($user->tenant);
            }
        }

        // Check if Tenant account is active
        if ($activeTenant && !$activeTenant->is_active && !$user->isSuperAdmin()) {
            abort(403, "Facility Access Suspended: The platform account for '{$activeTenant->name}' is currently suspended. Please contact platform support.");
        }

        if (!empty($roles)) {
            $hasRole = false;
            foreach ($roles as $role) {
                if ($user->hasRole(trim($role))) {
                    $hasRole = true;
                    break;
                }
            }

            if (!$hasRole) {
                abort(403, 'Unauthorized Access: Your assigned role (' . ucfirst($user->role) . ') does not have permission to access this portal section.');
            }
        }

        return $next($request);
    }
}
