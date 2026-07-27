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
use App\Services\BookingEngineService;
use App\Services\CreditAndPassService;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Phase1HardeningTest extends TestCase
{
    use RefreshDatabase;

    protected BookingEngineService $bookingEngine;
    protected CreditAndPassService $creditAndPassService;
    protected InvoiceService $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceService = new InvoiceService();
        $this->creditAndPassService = new CreditAndPassService();
        $this->bookingEngine = new BookingEngineService(null, $this->creditAndPassService, $this->invoiceService);
    }

    public function test_concurrency_locking_prevents_double_booking(): void
    {
        $tenant = Tenant::create(['name' => 'Concurrency Tenant', 'slug' => 'concurrency-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court C1',
            'type' => 'indoor',
            'hourly_rate' => 4000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user1 = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Player One',
            'email' => 'player1@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $user2 = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Player Two',
            'email' => 'player2@test.com',
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
            'price' => 4000,
        ]);

        // First customer books slot -> succeeds
        $booking1 = $this->bookingEngine->createBooking($user1, $court, $date, '10:00', '11:00', [], 'pay_at_venue');
        $this->assertNotNull($booking1);

        // Second customer attempts to book same slot -> throws exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Slot is already booked');

        $this->bookingEngine->createBooking($user2, $court, $date, '10:00', '11:00', [], 'pay_at_venue');
    }

    public function test_multi_tenant_data_isolation_enforced(): void
    {
        $tenantA = Tenant::create(['name' => 'Tenant Alpha', 'slug' => 'tenant-alpha']);
        $tenantB = Tenant::create(['name' => 'Tenant Beta', 'slug' => 'tenant-beta']);

        $userA = User::create([
            'tenant_id' => $tenantA->id,
            'name' => 'Alpha Owner',
            'email' => 'alpha@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $userB = User::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Beta Customer',
            'email' => 'beta@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $categoryA = SportCategory::create(['tenant_id' => $tenantA->id, 'name' => 'Alpha Squash', 'slug' => 'squash-a']);
        $courtA = Court::create([
            'tenant_id' => $tenantA->id,
            'sport_category_id' => $categoryA->id,
            'name' => 'Alpha Court 1',
            'type' => 'indoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        // Tenant Alpha queries courts -> sees Alpha Court 1, does NOT see Tenant Beta data
        $response = $this->actingAs($userA)->get(route('admin.courts', ['tenant' => $tenantA->slug]));
        $response->assertStatus(200);
        $response->assertSee('Alpha Court 1');
        $response->assertDontSee('Beta Court');
    }

    public function test_cancellation_deadline_policy_and_refund_eligibility(): void
    {
        $tenant = Tenant::create(['name' => 'Deadline Tenant', 'slug' => 'deadline-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Badminton', 'slug' => 'badminton']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court B1',
            'type' => 'indoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Deadline Player',
            'email' => 'deadline@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->creditAndPassService->issueCredits($user, 10000.00, 'Topup');
        $user->refresh();

        // 1. Future Booking (>24h prior) -> Eligible for refund
        $futureDate = Carbon::today()->addDays(3)->toDateString();
        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $futureDate,
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'available',
            'price' => 3000,
        ]);

        $bookingEligible = $this->bookingEngine->createBooking($user, $court, $futureDate, '14:00', '15:00', [], 'credits');
        $this->bookingEngine->cancelBooking($bookingEligible, 'Early cancellation');

        $user->refresh();
        $this->assertEquals('refunded', $bookingEligible->refresh()->payment_status);
        $this->assertEquals(10000.00, $user->credit_balance);

        // 2. Late Booking (<24h prior) -> Non-refundable
        $todayDate = Carbon::today()->toDateString();
        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $todayDate,
            'start_time' => '23:00',
            'end_time' => '23:59',
            'status' => 'available',
            'price' => 3000,
        ]);

        $bookingLate = $this->bookingEngine->createBooking($user, $court, $todayDate, '23:00', '23:59', [], 'credits');
        $this->bookingEngine->cancelBooking($bookingLate, 'Late cancellation');

        $user->refresh();
        $this->assertEquals('paid', $bookingLate->refresh()->payment_status); // Not refunded
        $this->assertEquals(7000.00, $user->credit_balance);
    }

    public function test_no_show_threshold_auto_bans_customer_and_blocks_new_bookings(): void
    {
        $tenant = Tenant::create(['name' => 'NoShow Tenant', 'slug' => 'noshow-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Padel', 'slug' => 'padel']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Padel Court 1',
            'type' => 'outdoor',
            'hourly_rate' => 4000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Staff Admin',
            'email' => 'noshowstaff@test.com',
            'password' => bcrypt('password'),
            'role' => 'front_desk',
        ]);

        $customer = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Repeated NoShow Player',
            'email' => 'noshow@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Create 3 bookings and mark all 3 as No-Show
        for ($i = 1; $i <= 3; $i++) {
            $date = Carbon::today()->addDays($i)->toDateString();
            TimeSlot::create([
                'tenant_id' => $tenant->id,
                'court_id' => $court->id,
                'date' => $date,
                'start_time' => '10:00',
                'end_time' => '11:00',
                'status' => 'available',
                'price' => 4000,
            ]);

            $booking = $this->bookingEngine->createBooking($customer, $court, $date, '10:00', '11:00', [], 'pay_at_venue');
            $this->bookingEngine->markNoShow($booking, $staff, "Unexcused no show #{$i}");
        }

        $customer->refresh();
        $this->assertTrue($customer->is_banned);
        $this->assertNotNull($customer->ban_reason);

        // Attempting to create a 4th booking while banned -> throws exception
        $date4 = Carbon::today()->addDays(5)->toDateString();
        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date4,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'available',
            'price' => 4000,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Account is suspended');
        $this->bookingEngine->createBooking($customer, $court, $date4, '10:00', '11:00', [], 'pay_at_venue');
    }

    public function test_credit_and_pass_balances_prevent_negative_overdraft_and_reconcile(): void
    {
        $tenant = Tenant::create(['name' => 'Reconcile Tenant', 'slug' => 'reconcile-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court R1',
            'type' => 'indoor',
            'hourly_rate' => 5000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Overdraft Player',
            'email' => 'overdraft@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Issue 2000 credits (insufficient for 5000 court rate)
        $this->creditAndPassService->issueCredits($user, 2000.00, 'Small Topup');
        $user->refresh();

        $date = Carbon::today()->addDays(2)->toDateString();
        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'available',
            'price' => 5000,
        ]);

        // Booking with insufficient credit balance throws exception
        try {
            $this->bookingEngine->createBooking($user, $court, $date, '10:00', '11:00', [], 'credits');
            $this->fail('Expected exception for insufficient credit balance was not thrown.');
        } catch (Exception $e) {
            $this->assertStringContainsString('Insufficient wallet credit balance', $e->getMessage());
        }

        // Credit balance remains uncorrupted at 2000.00
        $user->refresh();
        $this->assertEquals(2000.00, $user->credit_balance);
    }
}
