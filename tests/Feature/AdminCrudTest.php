<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\TimeSlot;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_update_and_delete_court(): void
    {
        $tenant = Tenant::create(['name' => 'Crud Tenant', 'slug' => 'crud-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Facility Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        // 1. Create Court
        $response1 = $this->actingAs($owner)->post(route('admin.courts.store'), [
            'sport_category_id' => $category->id,
            'name' => 'Center Court 1',
            'type' => 'indoor',
            'surface_type' => 'Hard Court',
            'hourly_rate' => 5000,
            'peak_hourly_rate' => 6000,
        ]);
        $response1->assertRedirect(route('admin.courts'));

        $court = Court::where('name', '=', 'Center Court 1', 'and')->first();
        $this->assertNotNull($court);

        // 2. Update Court
        $response2 = $this->actingAs($owner)->post(route('admin.courts.update', ['id' => $court->id]), [
            'name' => 'Center Court 1 Renovated',
            'hourly_rate' => 5500,
            'peak_hourly_rate' => 7000,
            'is_active' => 1,
        ]);
        $response2->assertRedirect(route('admin.courts'));

        $court->refresh();
        $this->assertEquals('Center Court 1 Renovated', $court->name);
        $this->assertEquals(5500, $court->hourly_rate);

        // 3. Delete Court
        $response3 = $this->actingAs($owner)->delete(route('admin.courts.delete', ['id' => $court->id]));
        $response3->assertRedirect(route('admin.courts'));

        $this->assertNull(Court::find($court->id));
    }

    public function test_front_desk_cannot_manage_staff_accounts(): void
    {
        $tenant = Tenant::create(['name' => 'Staff Tenant', 'slug' => 'staff-tenant']);

        $frontDesk = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Desk Staff',
            'email' => 'desk@test.com',
            'password' => bcrypt('password'),
            'role' => 'front_desk',
        ]);

        // Front desk attempts to view staff management -> 403 Forbidden
        $response = $this->actingAs($frontDesk)->get(route('admin.staff'));
        $response->assertStatus(403);
    }

    public function test_owner_can_create_and_manage_staff_accounts(): void
    {
        $tenant = Tenant::create(['name' => 'Owner Staff Tenant', 'slug' => 'owner-staff-tenant']);

        $manager = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Facility Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        // Manager creates new Front Desk staff
        $response = $this->actingAs($manager)->post(route('admin.staff.store'), [
            'name' => 'Nimal Jay',
            'email' => 'nimal@test.com',
            'role' => 'front_desk',
            'password' => 'password123',
        ]);
        $response->assertRedirect(route('admin.staff'));

        $newStaff = User::where('email', '=', 'nimal@test.com', 'and')->first();
        $this->assertNotNull($newStaff);
        $this->assertEquals('front_desk', $newStaff->role);
    }
}
