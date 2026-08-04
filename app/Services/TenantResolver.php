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
    /**
     * Resolve current tenant from Host Header (Subdomain / Custom Domain).
     * In local/testing environments ONLY, allows ?tenant=slug query parameter override.
     * Path-segment (/slug/) and Session-based tenant resolution are strictly REMOVED.
     */
    public static function resolve(Request $request): ?Tenant
    {
        $tenant = null;

        // 1. Local/Testing environment query param convenience (?tenant=slug)
        if (app()->environment('local', 'testing') && $request->has('tenant')) {
            $slug = strtolower((string) $request->query('tenant'));
            if ($slug !== '') {
                $tenant = Tenant::where('slug', '=', $slug, 'and')->where('is_active', '=', true, 'and')->first(['*']);
                if ($tenant) {
                    static::setActiveTenantContext($tenant);
                    return $tenant;
                }
            }
        }

        // 2. Subdomain & Custom Domain Resolution via Host Header
        $host = strtolower($request->getHost());
        $appUrl = config('app.url');
        $appHost = strtolower(parse_url($appUrl, PHP_URL_HOST) ?? '');

        if ($host && $host !== 'localhost' && $host !== '127.0.0.1') {
            // Direct custom domain check (e.g. "booking.colombocourts.lk")
            $tenant = Tenant::where('domain', '=', $host, 'and')->where('is_active', '=', true, 'and')->first(['*']);

            // Subdomain check (e.g. "colombo-courts-club" from "colombo-courts-club.lvh.me")
            if (!$tenant && $host !== $appHost) {
                $hostParts = explode('.', $host);
                if (count($hostParts) >= 2 && $hostParts[0] !== 'www') {
                    $subdomain = $hostParts[0];
                    $tenant = Tenant::where('slug', '=', $subdomain, 'and')->where('is_active', '=', true, 'and')->first(['*']);
                }
            }

            if ($tenant) {
                static::setActiveTenantContext($tenant);
                return $tenant;
            }
        }

        // Clear context if no tenant resolved
        static::clearTenantContext();
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
     * Generate canonical absolute subdomain-based URL for tenant routes.
     * Supports both route names (e.g., 'admin.bookings') and relative paths (e.g., '/admin/bookings').
     */
    public static function tenantUrl(string $nameOrPath = '', array $params = [], ?Tenant $tenant = null): string
    {
        $activeTenant = $tenant ?? static::getActiveTenantModel();
        $slug = $activeTenant?->slug;

        if ($nameOrPath !== '' && \Illuminate\Support\Facades\Route::has($nameOrPath)) {
            $path = route($nameOrPath, $params, false);
        } else {
            $path = '/' . ltrim($nameOrPath, '/');
            if (!empty($params)) {
                $path .= '?' . http_build_query($params);
            }
        }

        $appUrl = config('app.url');
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?? 'http';
        $host = parse_url($appUrl, PHP_URL_HOST) ?? 'lvh.me';
        $port = parse_url($appUrl, PHP_URL_PORT);

        $baseHost = $slug ? "{$slug}.{$host}" : $host;
        $portStr = $port ? ":{$port}" : '';

        return "{$scheme}://{$baseHost}{$portStr}" . $path;
    }

    /**
     * Store resolved tenant into container singleton and view scope.
     * Session storing tenant_id is kept solely for authorization checks, not resolution.
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

        return null;
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
