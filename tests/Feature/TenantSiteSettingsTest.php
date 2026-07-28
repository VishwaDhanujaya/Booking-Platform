<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantSiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $owner;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Kandy Badminton Hub',
            'slug' => 'kandy-badminton',
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Premier Badminton Courts',
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        $this->owner = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Owner Kasun',
            'email' => 'owner@kandybadminton.lk',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->customer = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Customer Aruni',
            'email' => 'customer@kandybadminton.lk',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);
    }

    public function test_tenant_owner_can_access_site_settings_form(): void
    {
        $response = $this->actingAs($this->owner)
                         ->get('/admin/settings?tenant=kandy-badminton');

        $response->assertStatus(200);
        $response->assertSee('Site Settings & Branding');
        $response->assertSee('Kandy Badminton Hub');
    }

    public function test_tenant_owner_can_update_site_settings_in_database(): void
    {
        $response = $this->actingAs($this->owner)
                         ->post('/admin/settings?tenant=kandy-badminton', [
                             'name' => 'Kandy Badminton & Squash Complex',
                             'brand_color' => '#50B748',
                             'favicon_url' => 'https://example.com/favicon.ico',
                             'logo_url' => 'https://example.com/kandy-logo.png',
                             'tagline' => 'World-Class Indoor Courts',
                             'description' => '12 competition courts with anti-glare lighting.',
                             'address' => '123 Peradeniya Road, Kandy',
                             'phone' => '+94 81 223 4567',
                             'email' => 'contact@kandybadminton.lk',
                             'opening_hours' => 'Mon-Sun 5:00 AM - 11:00 PM',
                             'hero_headline' => 'Book Premium Kandy Badminton Courts',
                             'hero_subheading' => 'Select open slots on our multi-court grid.',
                             'hero_image_url' => 'https://example.com/kandy-hero.jpg',
                             'category' => 'Group Classes & Courses',
                             'custom_domain' => 'booking.kandybadminton.lk',
                             'notices' => [
                                 ['title' => 'Non-marking Shoes Mandatory', 'link' => '#rules', 'icon' => 'document-text'],
                             ],
                             'nav_settings' => [
                                 'show_courts' => true,
                                 'show_pricing' => true,
                                 'show_passes' => false,
                                 'show_rules' => true,
                                 'show_contact' => true,
                             ],
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('tenants', [
            'id' => $this->tenant->id,
            'name' => 'Kandy Badminton & Squash Complex',
            'brand_color' => '#50B748',
            'tagline' => 'World-Class Indoor Courts',
            'category' => 'Group Classes & Courses',
            'custom_domain' => 'booking.kandybadminton.lk',
        ]);
    }

    public function test_updated_site_settings_are_rendered_on_live_tenant_site(): void
    {
        $this->tenant->update([
            'name' => 'Customized Arena Name',
            'tagline' => 'Unique Facility Tagline',
            'brand_color' => '#00B4EB',
            'opening_hours' => 'Mon-Sat 7am-10pm',
            'notices' => [
                ['title' => 'Special Promo Notice Strip', 'link' => '#promo', 'icon' => 'info'],
            ],
        ]);

        $response = $this->get('/book?tenant=kandy-badminton');

        $response->assertStatus(200);
        $response->assertSee('Customized Arena Name');
        $response->assertSee('Special Promo Notice Strip');
        $response->assertSee('Mon-Sat 7am-10pm');
    }

    public function test_unauthenticated_or_non_staff_users_cannot_access_site_settings(): void
    {
        // Unauthenticated -> redirect to login
        $guestResponse = $this->get('/admin/settings?tenant=kandy-badminton');
        $guestResponse->assertRedirect('/login');

        // Customer -> blocked with 403 Forbidden by role middleware
        $customerResponse = $this->actingAs($this->customer)
                                 ->get('/admin/settings?tenant=kandy-badminton');
        $customerResponse->assertStatus(403);
    }

    public function test_tenant_owner_can_upload_logo_and_hero_image_files(): void
    {
        $logoFile = \Illuminate\Http\UploadedFile::fake()->create('my_venue_logo.png', 100, 'image/png');
        $heroFile = \Illuminate\Http\UploadedFile::fake()->create('my_hero_banner.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($this->owner)
                         ->post('/admin/settings?tenant=kandy-badminton', [
                             'name' => 'Kandy Badminton Hub',
                             'category' => 'Sports Venues & Facilities',
                             'logo_file' => $logoFile,
                             'hero_image_file' => $heroFile,
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->tenant->refresh();
        $this->assertStringContainsString('/uploads/tenants/kandy-badminton/logo_', $this->tenant->logo_url);
        $this->assertStringContainsString('/uploads/tenants/kandy-badminton/hero_', $this->tenant->hero_image_url);
    }
}
