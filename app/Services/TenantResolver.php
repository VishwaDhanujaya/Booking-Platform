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
            $tenant = Tenant::where('slug', '=', $slug, 'and')->where('is_active', '=', true, 'and')->first(['*']);
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 2. Subdomain & Custom Domain Resolution (e.g. colombo.localhost or apex.appdomain.test)
        $host = $request->getHost();
        if ($host && $host !== 'localhost' && $host !== '127.0.0.1') {
            // Direct domain check
            $tenant = Tenant::where('domain', '=', $host, 'and')->where('is_active', '=', true, 'and')->first(['*']);

            // Subdomain check (e.g. "colombo" from "colombo.localhost")
            if (!$tenant) {
                $hostParts = explode('.', $host);
                if (count($hostParts) >= 2 && $hostParts[0] !== 'www') {
                    $subdomain = strtolower($hostParts[0]);
                    $tenant = Tenant::where('slug', '=', $subdomain, 'and')->where('is_active', '=', true, 'and')->first(['*']);
                }
            }

            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 3. Session Fallback
        if (session()->has('tenant_id')) {
            $tenant = Tenant::where('id', '=', session('tenant_id'), 'and')->where('is_active', '=', true, 'and')->first(['*']);
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 4. Default Local Dev Fallback (First active tenant in DB)
        $tenant = Tenant::where('is_active', '=', true, 'and')->first(['*']);
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
            return Tenant::find(session('tenant_id'), ['*']);
        }

        return Tenant::first(['*']);
    }

    public static function getActiveTenant(): array
    {
        $tenant = static::getActiveTenantModel();

        if ($tenant) {
            $brandHex = $tenant->brand_color ?? '#0284c7';
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'domain' => $tenant->domain,
                'address' => $tenant->address ?? 'Colombo, Sri Lanka',
                'phone' => $tenant->phone ?? '+94 11 234 5678',
                'email' => $tenant->email ?? ('info@' . $tenant->slug . '.lk'),
                'brand_color' => $brandHex,
                'primary_hex' => $brandHex,
                'primary_hover' => $brandHex,
                'primary_light' => '#f0f9ff',
                'accent_hex' => '#0f172a',
                'logo_url' => $tenant->logo_url,
                'logo_initial' => strtoupper(substr($tenant->name, 0, 1)),
                'badge' => $tenant->name,
                'hero_title' => 'Reserve Premium ' . $tenant->name . ' Courts Online',
                'hero_subtitle' => 'Instant real-time booking for Tennis, Padel, Badminton & Squash with automated lighting and court scheduling.',
                'tagline' => 'Premier multi-tenant court booking & facility platform.',
                'sports' => ['Tennis', 'Padel', 'Badminton', 'Squash'],
                'theme_settings' => $tenant->theme_settings ?? [],
            ];
        }

        return config('tenants.colombo-courts-club', [
            'id' => 1,
            'name' => 'Colombo Courts Club',
            'slug' => 'colombo-courts-club',
            'address' => '45 Maitland Crescent, Colombo 00700, Sri Lanka',
            'phone' => '+94 11 234 5678',
            'email' => 'info@colombocourts.lk',
            'brand_color' => '#0284c7',
            'primary_hex' => '#0284c7',
            'primary_hover' => '#0369a1',
            'primary_light' => '#f0f9ff',
            'accent_hex' => '#0f172a',
            'logo_initial' => 'C',
            'badge' => 'Colombo Courts Club',
            'hero_title' => 'Reserve Premium Colombo Courts Online',
            'hero_subtitle' => 'Instant real-time booking for Tennis, Padel, Badminton & Squash.',
            'tagline' => 'Premier multi-tenant court booking & facility platform.',
            'sports' => ['Tennis', 'Padel', 'Badminton', 'Squash'],
        ]);
    }

    public static function getAllTenants()
    {
        return Tenant::where('is_active', '=', true, 'and')->get(['*']);
    }
}
