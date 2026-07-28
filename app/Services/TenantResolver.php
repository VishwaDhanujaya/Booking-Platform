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

        // 2. URL Path Segment Resolution (e.g. /zenith-yoga/book or /zenith-yoga/admin)
        $firstSegment = strtolower((string) $request->segment(1));
        $excludedSegments = ['platform-admin', 'register-business', 'login', 'register', 'logout', 'parent', 'features', 'where-to-use', 'customers', 'platform-pricing', 'contact', 'demo', 'api', 'storage', 'build', 'images', 'uploads'];
        if ($firstSegment && !in_array($firstSegment, $excludedSegments, true)) {
            $tenant = Tenant::where('slug', '=', $firstSegment, 'and')->where('is_active', '=', true, 'and')->first(['*']);
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // 3. Subdomain & Custom Domain Resolution (e.g. colombo.localhost or apex.appdomain.test)
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

        // 4. Session Resolution
        if (session()->has('tenant_id')) {
            $tenant = Tenant::where('id', '=', session('tenant_id'), 'and')->where('is_active', '=', true, 'and')->first(['*']);
            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        return null;
    }

    /**
     * Clear tenant context for parent site routes.
     */
    public static function clearTenantContext(): void
    {
        session()->forget(['tenant_id', 'active_tenant_slug']);
        if (app()->bound('currentTenant')) {
            app()->forgetInstance('currentTenant');
        }
        View::share('currentTenant', null);
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
     * Set active tenant by slug.
     */
    public static function setActiveTenant(string $slug): void
    {
        $tenant = Tenant::where('slug', '=', $slug, 'and')->first(['*']);
        if ($tenant) {
            static::setActiveTenantContext($tenant);
        }
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
            $brandHex = $tenant->brand_color ?? '#0056A2';
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'domain' => $tenant->domain,
                'custom_domain' => $tenant->custom_domain,
                'category' => $tenant->category ?? 'Sports Venues & Facilities',
                'tagline' => $tenant->tagline ?? 'Premier multi-tenant court booking & facility platform.',
                'description' => $tenant->description,
                'address' => $tenant->address ?? 'Colombo, Sri Lanka',
                'phone' => $tenant->phone ?? '+94 11 234 5678',
                'email' => $tenant->email ?? ('info@' . $tenant->slug . '.lk'),
                'opening_hours' => $tenant->opening_hours ?? 'Mon - Sun: 6:00 AM - 10:00 PM',
                'brand_color' => $brandHex,
                'primary_hex' => $brandHex,
                'primary_hover' => $brandHex,
                'primary_light' => '#f0f9ff',
                'accent_hex' => '#0f172a',
                'logo_url' => $tenant->logo_url,
                'favicon_url' => $tenant->favicon_url,
                'logo_initial' => strtoupper(substr($tenant->name, 0, 1)),
                'badge' => $tenant->name,
                'hero_title' => $tenant->hero_headline ?? ('Reserve Premium ' . $tenant->name . ' Courts Online'),
                'hero_subtitle' => $tenant->hero_subheading ?? ('Instant real-time booking for ' . $tenant->name . ' with automated lighting and court scheduling.'),
                'hero_image_url' => $tenant->hero_image_url ?? '/images/hero_sports_court.png',
                'hero_highlights' => $tenant->hero_highlights ?? [],
                'notices' => $tenant->notices ?? [
                    ['title' => 'Court Booking Rules', 'link' => '#', 'icon' => 'document-text'],
                    ['title' => 'Redeem Member Voucher', 'link' => '#', 'icon' => 'ticket'],
                ],
                'nav_settings' => $tenant->nav_settings ?? [
                    'show_courts' => true,
                    'show_pricing' => true,
                    'show_passes' => true,
                    'show_rules' => true,
                    'show_contact' => true,
                ],
                'sports' => ['Tennis', 'Padel', 'Badminton', 'Squash'],
                'theme_settings' => $tenant->theme_settings ?? [],
            ];
        }

        $brandHex = '#0056A2';
        return [
            'id' => 1,
            'name' => 'Colombo Courts Club',
            'slug' => 'colombo-courts-club',
            'domain' => 'colombo-courts-club.localhost',
            'custom_domain' => null,
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Premier multi-tenant court booking & facility platform.',
            'description' => 'Multi-court badminton, tennis, and padel arena.',
            'address' => '45 Maitland Crescent, Colombo 00700, Sri Lanka',
            'phone' => '+94 11 234 5678',
            'email' => 'info@colombocourts.lk',
            'opening_hours' => 'Mon - Sun: 6:00 AM - 10:00 PM',
            'brand_color' => $brandHex,
            'primary_hex' => $brandHex,
            'primary_hover' => $brandHex,
            'primary_light' => '#f0f9ff',
            'accent_hex' => '#0f172a',
            'logo_url' => null,
            'favicon_url' => null,
            'logo_initial' => 'C',
            'badge' => 'Colombo Courts Club',
            'hero_title' => 'Reserve Premium Colombo Courts Online',
            'hero_subtitle' => 'Instant real-time booking for Tennis, Padel, Badminton & Squash with automated lighting and court scheduling.',
            'hero_image_url' => '/images/hero_sports_court.png',
            'hero_highlights' => [],
            'notices' => [
                ['title' => 'Court Booking Rules', 'link' => '#', 'icon' => 'document-text'],
                ['title' => 'Redeem Member Voucher', 'link' => '#', 'icon' => 'ticket'],
            ],
            'nav_settings' => [
                'show_courts' => true,
                'show_pricing' => true,
                'show_passes' => true,
                'show_rules' => true,
                'show_contact' => true,
            ],
            'sports' => ['Tennis', 'Padel', 'Badminton', 'Squash'],
            'theme_settings' => [],
        ];
    }

    public static function getAllTenants()
    {
        return Tenant::where('is_active', '=', true, 'and')->get(['*']);
    }
}
