<?php

namespace Tests\Feature;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantStandaloneSiteTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant1;
    protected Tenant $tenant2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant1 = Tenant::create([
            'name' => 'Royal Colombo Tennis Club',
            'slug' => 'royal-colombo-tennis',
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Championship Grass & Hard Tennis Courts',
            'description' => '10 floodlit tennis courts with clubhouse.',
            'address' => '12 Maitland Place, Colombo 07',
            'phone' => '+94 11 999 1111',
            'email' => 'info@royaltennis.lk',
            'opening_hours' => 'Mon - Sun: 6:00 AM - 9:00 PM',
            'brand_color' => '#0056A2',
            'hero_headline' => 'Reserve Royal Tennis Courts',
            'hero_subheading' => 'Instant online court reservation with lighting.',
            'notices' => [
                ['title' => 'Tennis Whites Mandatory', 'link' => '#rules', 'icon' => 'document-text'],
            ],
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        $this->tenant2 = Tenant::create([
            'name' => 'Kandy Padel & Badminton Hub',
            'slug' => 'kandy-padel-hub',
            'category' => 'Group Classes & Courses',
            'tagline' => 'World-Class Indoor Padel Arena',
            'description' => 'Glass-walled FIP padel courts in Kandy.',
            'address' => '88 Peradeniya Road, Kandy',
            'phone' => '+94 81 888 2222',
            'email' => 'booking@kandypadel.lk',
            'opening_hours' => 'Mon - Sun: 5:00 AM - 11:00 PM',
            'brand_color' => '#50B748',
            'hero_headline' => 'Book Kandy Padel & Badminton Slots',
            'hero_subheading' => 'FIP glass padel courts with BWF mats.',
            'notices' => [
                ['title' => 'Padel Racket Rental Available', 'link' => '#rentals', 'icon' => 'info'],
            ],
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);
    }

    public function test_tenant_site_renders_without_parent_platform_marketing_bleed(): void
    {
        $response = $this->get('/book?tenant=royal-colombo-tennis');

        $response->assertStatus(200);
        $response->assertSee('Royal Colombo Tennis Club');
        $response->assertSee('Tennis Whites Mandatory');

        // Confirm platform B2B bleed elements are NOT present
        $response->assertDontSee('Start Free Trial');
        $response->assertDontSee('Subdomain-based tenant isolation');
        $response->assertDontSee('Multi-tenant court booking & facility platform powered by SLT Digital Engine');
    }

    public function test_parent_site_layout_differs_from_tenant_site_layout(): void
    {
        $parentResponse = $this->get('/');
        $tenantResponse = $this->get('/book?tenant=royal-colombo-tennis');

        $parentResponse->assertStatus(200);
        $tenantResponse->assertStatus(200);

        // Parent marketing site has corporate CTAs
        $parentResponse->assertSee('SLT Digital Services');
        $parentResponse->assertSee('Register Venue');
        $parentResponse->assertSee('Book a Demo');

        // Tenant public site has venue-specific content
        $tenantResponse->assertSee('Royal Colombo Tennis Club');
        $tenantResponse->assertDontSee('Book a Demo');
    }

    public function test_two_tenant_sites_render_completely_distinct_branding_and_content(): void
    {
        $responseTenant1 = $this->get('/book?tenant=royal-colombo-tennis');
        $responseTenant2 = $this->get('/book?tenant=kandy-padel-hub');

        $responseTenant1->assertStatus(200);
        $responseTenant2->assertStatus(200);

        // Tenant 1 distinct attributes
        $responseTenant1->assertSee('Royal Colombo Tennis Club');
        $responseTenant1->assertSee('Tennis Whites Mandatory');
        $responseTenant1->assertSee('Mon - Sun: 6:00 AM - 9:00 PM');
        $responseTenant1->assertSee('#0056A2');

        // Tenant 2 distinct attributes
        $responseTenant2->assertSee('Kandy Padel & Badminton Hub');
        $responseTenant2->assertSee('Padel Racket Rental Available');
        $responseTenant2->assertSee('Mon - Sun: 5:00 AM - 11:00 PM');
        $responseTenant2->assertSee('#50B748');

        // Confirm cross-contamination does not occur
        $responseTenant1->assertDontSee('Kandy Padel & Badminton Hub');
        $responseTenant2->assertDontSee('Royal Colombo Tennis Club');
    }

    public function test_tenant_pricing_route_shows_venue_court_rates_not_parent_saas_pricing(): void
    {
        // Tenant Pricing Route -> Shows Venue Court Rates & Member Passes
        $tenantPricingResponse = $this->get('/pricing?tenant=royal-colombo-tennis');
        $tenantPricingResponse->assertStatus(200);
        $tenantPricingResponse->assertSee('Court Rates');
        $tenantPricingResponse->assertSee('Royal Colombo Tennis Club');
        $tenantPricingResponse->assertDontSee('Self-Serve Business Onboarding');

        // Parent Platform Pricing Route -> Shows B2B SaaS Tiers
        $parentPricingResponse = $this->get('/platform-pricing');
        $parentPricingResponse->assertStatus(200);
        $parentPricingResponse->assertSee('SLT Digital');
    }

    public function test_tenant_navigation_links_preserve_tenant_context_and_do_not_leak_to_parent_home(): void
    {
        $response = $this->get('/book?tenant=royal-colombo-tennis');

        $response->assertStatus(200);
        
        // Assert header logo and nav links preserve the tenant parameter
        $response->assertSee('/book?tenant=royal-colombo-tennis');
        $response->assertSee('/pricing?tenant=royal-colombo-tennis');
    }
}
