<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\CustomerPass;
use App\Services\CreditAndPassService;
use App\Services\BookingEngineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditAndPassTest extends TestCase
{
    use RefreshDatabase;

    protected CreditAndPassService $creditAndPassService;
    protected BookingEngineService $bookingEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creditAndPassService = new CreditAndPassService();
        $this->bookingEngine = new BookingEngineService(null, $this->creditAndPassService);
    }

    public function test_ledger_derived_credit_balance_and_staff_issuance(): void
    {
        $tenant = Tenant::create(['name' => 'Ledger Tenant', 'slug' => 'ledger-tenant']);
        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Ledger Player',
            'email' => 'ledger@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Staff Admin',
            'email' => 'staffadmin@test.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $this->assertEquals(0.00, $user->credit_balance);

        // Staff issues 15,000 LKR credits
        $entry1 = $this->creditAndPassService->issueCredits($user, 15000.00, 'Goodwill compensation', $staff);
        $user->refresh();

        $this->assertEquals(15000.00, $user->credit_balance);
        $this->assertEquals(15000.00, $entry1->balance_after);

        // Staff issues additional 5,000 LKR
        $entry2 = $this->creditAndPassService->issueCredits($user, 5000.00, 'Bonus topup', $staff);
        $user->refresh();

        $this->assertEquals(20000.00, $user->credit_balance);
        $this->assertEquals(20000.00, $entry2->balance_after);
    }

    public function test_credit_tender_booking_and_cancellation_refund(): void
    {
        $tenant = Tenant::create(['name' => 'Tender Tenant', 'slug' => 'tender-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Badminton', 'slug' => 'badminton']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Badminton 1',
            'type' => 'indoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tender Player',
            'email' => 'tender@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Issue initial credits
        $this->creditAndPassService->issueCredits($user, 10000.00, 'Initial topup');
        $user->refresh();

        $date = Carbon::today()->addDays(3)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '16:00',
            'end_time' => '17:00',
            'status' => 'available',
            'price' => 3000,
        ]);

        // 1. Book using wallet credits tender
        $booking = $this->bookingEngine->createBooking($user, $court, $date, '16:00', '17:00', [], 'credits');
        $user->refresh();

        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals(7000.00, $user->credit_balance); // 10000 - 3000

        // 2. Cancel booking (>24h prior) -> full credit refund
        $this->bookingEngine->cancelBooking($booking, 'User cancelled');
        $user->refresh();

        $this->assertEquals('refunded', $booking->refresh()->payment_status);
        $this->assertEquals(10000.00, $user->credit_balance); // 7000 + 3000 restored
    }

    public function test_customer_pass_issuance_redemption_and_restoration(): void
    {
        $tenant = Tenant::create(['name' => 'Pass Tenant', 'slug' => 'pass-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Tennis 1',
            'type' => 'outdoor',
            'hourly_rate' => 3500,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Pass Player',
            'email' => 'passplayer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Issue 2-visit pass
        $pass = $this->creditAndPassService->issuePass(
            $user,
            'Tennis 2-Visit Pack',
            2,
            7000.00,
            Carbon::today()->addDays(30)
        );

        $this->assertEquals(2, $pass->remaining_units);

        $date = Carbon::today()->addDays(4)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '11:00',
            'end_time' => '12:00',
            'status' => 'available',
            'price' => 3500,
        ]);

        // 1. Redeem 1 pass unit for booking
        $booking = $this->bookingEngine->createBooking($user, $court, $date, '11:00', '12:00', [], 'pass', $pass);
        $pass->refresh();

        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals(1, $pass->remaining_units);
        $this->assertEquals('active', $pass->status);

        // 2. Cancel booking (>24h prior) -> restores 1 pass unit
        $this->bookingEngine->cancelBooking($booking, 'User cancelled');
        $pass->refresh();

        $this->assertEquals(2, $pass->remaining_units);
        $this->assertEquals('active', $pass->status);
    }
}
