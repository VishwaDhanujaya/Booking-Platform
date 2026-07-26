<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TenantResolver
{
    /**
     * Resolve current tenant from Subdomain, Query Parameter, or Session.
     */
    public static function resolve(Request $request): ?Tenant
    {
        $tenant = null;

        // 1. Explicit Query Param Override (?tenant=slug)
        if ($request->has('tenant')) {
            $slug = (string) $request->query('tenant');
            $tenant = Tenant::where('slug', '=', $slug)->where('is_active', '=', true)->first();
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 2. Subdomain & Custom Domain Resolution (e.g. colombo.localhost or apex.appdomain.test)
        $host = $request->getHost();
        if ($host && $host !== 'localhost' && $host !== '127.0.0.1') {
            // Direct domain check
            $tenant = Tenant::where('domain', '=', $host)->where('is_active', '=', true)->first();

            // Subdomain check (e.g. "colombo" from "colombo.localhost")
            if (!$tenant) {
                $hostParts = explode('.', $host);
                if (count($hostParts) >= 2 && $hostParts[0] !== 'www') {
                    $subdomain = strtolower($hostParts[0]);
                    $tenant = Tenant::where('slug', '=', $subdomain)->where('is_active', '=', true)->first();
                }
            }

            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 3. Session Fallback
        if (session()->has('tenant_id')) {
            $tenant = Tenant::where('id', '=', session('tenant_id'))->where('is_active', '=', true)->first();
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 4. Default Local Dev Fallback (First active tenant in DB)
        $tenant = Tenant::where('is_active', '=', true)->first();
        if ($tenant) {
            static::setActiveTenantContext($tenant);
        }

        return $tenant;
    }

    /**
     * Store resolved tenant into session, container singleton, and view scope.
     */
    public static function setActiveTenantContext(Tenant $tenant): void
    {
        session([
            'tenant_id' => $tenant->id,
            'active_tenant_slug' => $tenant->slug,
        ]);

        app()->instance('currentTenant', $tenant);
        View::share('currentTenant', $tenant);
    }

    /**
     * Get active tenant model instance.
     */
    public static function getActiveTenantModel(): ?Tenant
    {
        if (app()->bound('currentTenant')) {
            return app('currentTenant');
        }

        if (session()->has('tenant_id')) {
            return Tenant::find(session('tenant_id'));
        }

        return Tenant::first();
    }

    public static function getActiveTenant(): array
    {
        $tenant = static::getActiveTenantModel();

        if ($tenant) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'address' => $tenant->address,
                'phone' => $tenant->phone,
                'email' => $tenant->email,
                'brand_color' => $tenant->brand_color ?? '#0284c7',
                'logo_url' => $tenant->logo_url,
                'theme_settings' => $tenant->theme_settings ?? [],
            ];
        }

        return config('tenants.colombo-courts-club', []);
    }

    public static function getAllTenants()
    {
        return Tenant::where('is_active', '=', true)->get();
    }
}
