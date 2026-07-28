<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Tenant;
use App\Models\ContactSubmission;

class ParentMarketingSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_apex_root_loads_slt_digital_services_parent_marketing_site()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SLT Digital');
        $response->assertSee('Booking Software for');
        $response->assertSee('Request a Demo');
    }

    public function test_features_page_renders_all_six_platform_modules()
    {
        $response = $this->get('/features');

        $response->assertStatus(200);
        $response->assertSee('Visual Multi-Court Day Grid');
        $response->assertSee('Capacity');
        $response->assertSee('Dynamic Pricing Engine');
        $response->assertSee('Customer Credits');
        $response->assertSee('Automated Tax Invoicing');
        $response->assertSee('Multi-Role Staff');
    }

    public function test_where_to_use_page_renders_all_five_verticals()
    {
        $response = $this->get('/where-to-use');

        $response->assertStatus(200);
        $response->assertSee('Sports Venues');
        $response->assertSee('Individual Bookings');
        $response->assertSee('Group Classes');
        $response->assertSee('Fitness Equipment');
        $response->assertSee('Creche');
    }

    public function test_customer_directory_displays_real_db_tenants_and_category_filters()
    {
        $response = $this->get('/customers');

        $response->assertStatus(200);
        $response->assertSee('Colombo Courts Club');
        $response->assertSee('Apex Sports Arena');
        $response->assertSee('Zenith Yoga');
        $response->assertSee('Launch Customer Site');
    }

    public function test_pricing_page_displays_subscription_tiers()
    {
        $response = $this->get('/platform-pricing');

        $response->assertStatus(200);
        $response->assertSee('Single Venue Starter');
        $response->assertSee('Facility Pro');
        $response->assertSee('Multi-Location Enterprise');
    }

    public function test_contact_form_stores_submission_in_database()
    {
        $response = $this->post('/contact', [
            'name' => 'Kusal Perera',
            'email' => 'kusal@sportsarena.lk',
            'phone' => '+94 77 555 1234',
            'business_name' => 'Kusal Badminton Center',
            'category' => 'Sports Venues & Facilities',
            'type' => 'demo',
            'message' => 'We have 8 badminton courts and would like a live demo of the multi-court grid.',
        ]);

        $response->assertSessionHas('status');

        $this->assertDatabaseHas('contact_submissions', [
            'name' => 'Kusal Perera',
            'email' => 'kusal@sportsarena.lk',
            'business_name' => 'Kusal Badminton Center',
            'type' => 'demo',
        ]);
    }
}
