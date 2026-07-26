<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantResolver
{
    /**
     * Resolve tenant from request query, session, or domain, setting tenant context in session.
     */
    public static function resolve(Request $request): ?Tenant
    {
        $slug = $request->query('tenant');

        if ($slug) {
            $tenant = Tenant::where('slug', '=', $slug)->first();
            if ($tenant) {
                session(['tenant_id' => $tenant->id, 'active_tenant_slug' => $tenant->slug]);
                return $tenant;
            }
        }

        if (session()->has('tenant_id')) {
            $tenant = Tenant::find(session('tenant_id'));
            if ($tenant) {
                return $tenant;
            }
        }

        $tenant = Tenant::first();
        if ($tenant) {
            session(['tenant_id' => $tenant->id, 'active_tenant_slug' => $tenant->slug]);
        }

        return $tenant;
    }

    public static function getActiveTenant(): array
    {
        $tenants = config('tenants', []);

        if (request()->has('tenant')) {
            $requestedSlug = request()->query('tenant');
            if (isset($tenants[$requestedSlug])) {
                session(['active_tenant_slug' => $requestedSlug]);
            }
        }

        $activeSlug = session('active_tenant_slug', 'colombo-courts-club');

        return $tenants[$activeSlug] ?? ($tenants['colombo-courts-club'] ?? []);
    }

    public static function getAllTenants(): array
    {
        return config('tenants', []);
    }
}
