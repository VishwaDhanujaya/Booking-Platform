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
        if ($user->role === 'super_admin') {
            return $next($request);
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
