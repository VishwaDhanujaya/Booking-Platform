<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\Booking;
use App\Models\NoShowRecord;
use App\Models\TimeSlot;
use App\Services\BookingEngineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected BookingEngineService $bookingEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingEngine = new BookingEngineService();
    }

    public function test_concurrency_safe_booking_and_limit_enforcement(): void
    {
        $tenant = Tenant::create(['name' => 'Test Tenant', 'slug' => 'test-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court 1',
            'type' => 'outdoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Player',
            'email' => 'player@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $date = Carbon::today()->addDays(2)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'available',
            'price' => 3000,
        ]);

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '11:00',
            'end_time' => '12:00',
            'status' => 'available',
            'price' => 3000,
        ]);

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '12:00',
            'end_time' => '13:00',
            'status' => 'available',
            'price' => 3000,
        ]);

        // 1. First booking succeeds
        $b1 = $this->bookingEngine->createBooking($user, $court, $date, '10:00', '11:00');
        $this->assertEquals('confirmed', $b1->status);

        // 2. Second booking on another slot succeeds
        $b2 = $this->bookingEngine->createBooking($user, $court, $date, '11:00', '12:00');
        $this->assertEquals('confirmed', $b2->status);

        // 3. Third booking on same day fails (Max 2 bookings per day limit)
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Booking limit exceeded');
        $this->bookingEngine->createBooking($user, $court, $date, '12:00', '13:00');
    }

    public function test_no_show_auto_ban_after_three_violations(): void
    {
        $tenant = Tenant::create(['name' => 'Ban Tenant', 'slug' => 'ban-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court 1',
            'type' => 'outdoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $customer = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'NoShow Customer',
            'email' => 'noshow@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Staff Manager',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $booking = Booking::create([
                'tenant_id' => $tenant->id,
                'court_id' => $court->id,
                'user_id' => $customer->id,
                'booking_reference' => "TEST-NS-{$i}",
                'booking_date' => Carbon::today()->subDays($i)->toDateString(),
                'start_time' => '10:00',
                'end_time' => '11:00',
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'status' => 'confirmed',
                'payment_status' => 'unpaid',
                'total_amount' => 3000,
            ]);

            $this->bookingEngine->markNoShow($booking, $staff, "Violation #{$i}");
        }

        $customer->refresh();
        $this->assertTrue((bool)$customer->is_banned);
        $this->assertStringContainsString('3 unexcused no-shows', $customer->ban_reason);
    }
}
