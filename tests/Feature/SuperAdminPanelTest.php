<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;

class SuperAdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_platform_admin_can_access_superadmin_dashboard()
    {
        $superAdmin = User::where('is_super_admin', '=', true, 'and')->first(['*']);

        $response = $this->actingAs($superAdmin)->get('/platform-admin');

        $response->assertStatus(200);
        $response->assertSee('Platform Central Command');
        $response->assertSee('Total Tenants');
    }

    public function test_regular_user_cannot_access_superadmin_panel()
    {
        $tenant = Tenant::first(['*']);
        $regularUser = User::where('tenant_id', '=', $tenant->id, 'and')->first(['*']);

        $response = $this->actingAs($regularUser)->get('/platform-admin');

        $response->assertStatus(403);
    }

    public function test_platform_admin_can_view_tenant_directory()
    {
        $superAdmin = User::where('is_super_admin', '=', true, 'and')->first(['*']);

        $response = $this->actingAs($superAdmin)->get('/platform-admin/tenants');

        $response->assertStatus(200);
        $response->assertSee('Tenant Directory');
        $response->assertSee('Colombo Courts Club');
    }

    public function test_platform_admin_can_provision_new_tenant_with_owner_account()
    {
        $superAdmin = User::where('is_super_admin', '=', true, 'and')->first(['*']);

        $response = $this->actingAs($superAdmin)->post('/platform-admin/tenants', [
            'name' => 'Galle Sports Complex',
            'slug' => 'galle-sports',
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Premier Galle Multi-Sport Complex',
            'subscription_plan' => 'pro',
            'is_public' => 1,
            'owner_name' => 'Nimal Perera',
            'owner_email' => 'nimal@gallesports.lk',
            'owner_password' => 'password123',
        ]);

        $response->assertRedirect('/platform-admin/tenants');

        $this->assertDatabaseHas('tenants', [
            'slug' => 'galle-sports',
            'category' => 'Sports Venues & Facilities',
            'subscription_plan' => 'pro',
            'is_public' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'nimal@gallesports.lk',
            'role' => 'owner',
        ]);
    }

    public function test_platform_admin_can_toggle_tenant_active_and_public_status()
    {
        $superAdmin = User::where('is_super_admin', '=', true, 'and')->first(['*']);
        $tenant = Tenant::first(['*']);

        // Toggle active status (suspend)
        $response = $this->actingAs($superAdmin)->post("/platform-admin/tenants/{$tenant->id}/toggle-status");
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'is_active' => false,
        ]);

        // Toggle public status
        $response = $this->actingAs($superAdmin)->post("/platform-admin/tenants/{$tenant->id}/toggle-public");
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'is_public' => false,
        ]);
    }

    public function test_platform_admin_can_manage_tenant_user_accounts_directly()
    {
        $superAdmin = User::where('is_super_admin', '=', true, 'and')->first(['*']);
        $tenant = Tenant::first(['*']);

        // View tenant users screen
        $response = $this->actingAs($superAdmin)->get("/platform-admin/tenants/{$tenant->id}/users");
        $response->assertStatus(200);
        $response->assertSee("User Accounts: {$tenant->name}");

        // Create new user for tenant via platform admin
        $createResponse = $this->actingAs($superAdmin)->post("/platform-admin/tenants/{$tenant->id}/users", [
            'name' => 'Manager Sunil',
            'email' => 'sunil@tenant.lk',
            'role' => 'manager',
            'password' => 'password123',
        ]);
        $createResponse->assertRedirect(route('superadmin.tenants.users', $tenant->id));

        $newUser = User::withoutGlobalScopes()->where('email', '=', 'sunil@tenant.lk', 'and')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('manager', $newUser->role);

        // Delete user for tenant via platform admin
        $deleteResponse = $this->actingAs($superAdmin)->delete("/platform-admin/tenants/{$tenant->id}/users/{$newUser->id}");
        $deleteResponse->assertRedirect(route('superadmin.tenants.users', $tenant->id));

        $this->assertNull(User::withoutGlobalScopes()->find($newUser->id, ['*']));
    }

    public function test_super_admin_can_login_via_login_form_and_redirects_to_platform_admin()
    {
        $response = $this->post('/login', [
            'email' => 'superadmin@sltdigital.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/platform-admin');
    }
}
