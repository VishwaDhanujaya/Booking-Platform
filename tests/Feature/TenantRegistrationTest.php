<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\TimeSlot;
use App\Models\PricingRule;
use App\Services\TenantProvisioningService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected TenantProvisioningService $provisioningService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provisioningService = new TenantProvisioningService();
    }

    public function test_tenant_provisioning_service_creates_complete_usable_venue(): void
    {
        $result = $this->provisioningService->provisionTenant([
            'business_name' => 'Apex Sports Arena',
            'slug' => 'apex-sports',
            'subscription_plan' => 'pro',
            'owner_name' => 'Sahan Perera',
            'owner_email' => 'sahan@apexsports.lk',
            'owner_phone' => '+94 77 999 8888',
            'password' => 'password123',
        ]);

        $tenant = $result['tenant'];
        $owner = $result['owner'];

        $this->assertEquals('Apex Sports Arena', $tenant->name);
        $this->assertEquals('apex-sports', $tenant->slug);
        $this->assertEquals('pro', $tenant->subscription_plan);

        $this->assertEquals('sahan@apexsports.lk', $owner->email);
        $this->assertEquals('owner', $owner->role);

        // Verify starter categories and courts
        $categoriesCount = SportCategory::where('tenant_id', '=', $tenant->id, 'and')->count();
        $courtsCount = Court::where('tenant_id', '=', $tenant->id, 'and')->count();
        $pricingRulesCount = PricingRule::where('tenant_id', '=', $tenant->id, 'and')->count();
        $timeSlotsCount = TimeSlot::where('tenant_id', '=', $tenant->id, 'and')->count();

        $this->assertGreaterThanOrEqual(2, $categoriesCount);
        $this->assertEquals(2, $courtsCount);
        $this->assertGreaterThanOrEqual(1, $pricingRulesCount);
        $this->assertGreaterThan(100, $timeSlotsCount);
    }

    public function test_self_serve_business_registration_web_flow(): void
    {
        $response = $this->post(route('tenant.register.perform'), [
            'business_name' => 'Lanka Padel Club',
            'slug' => 'lanka-padel',
            'subscription_plan' => 'enterprise',
            'owner_name' => 'Malik Silva',
            'owner_email' => 'malik@lankapadel.lk',
            'owner_phone' => '+94 71 555 4444',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $tenant = Tenant::where('slug', '=', 'lanka-padel', 'and')->first();
        $this->assertNotNull($tenant);
        $this->assertEquals('enterprise', $tenant->subscription_plan);

        $user = User::where('email', '=', 'malik@lankapadel.lk', 'and')->first();
        $this->assertNotNull($user);
        $this->assertEquals('owner', $user->role);
    }
}
