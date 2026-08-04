<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantResolver;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     * Runs on every web request — resolves the current tenant from the Host
     * header (subdomain) and, in local/testing environments only, from the
     * ?tenant= query param.
     */
    public function handle(Request $request, Closure $next): Response
    {
        TenantResolver::resolve($request);

        return $next($request);
    }
}
