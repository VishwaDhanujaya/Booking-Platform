<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user->isSuperAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Platform Super-Admin privileges required.'], 403);
            }

            abort(403, 'Unauthorized Access: Platform Super-Admin privileges required to access SLTDS Super Admin panel.');
        }

        return $next($request);
    }
}
