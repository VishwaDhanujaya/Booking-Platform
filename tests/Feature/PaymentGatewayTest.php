<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateway\ManualPaymentGateway;
use App\Services\BookingEngineService;
use App\Services\CreditAndPassService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected PaymentGatewayInterface $gateway;
    protected BookingEngineService $bookingEngine;
    protected CreditAndPassService $creditAndPassService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gateway = app(PaymentGatewayInterface::class);
        $this->creditAndPassService = new CreditAndPassService();
        $this->bookingEngine = new BookingEngineService(null, $this->creditAndPassService);
    }

    public function test_payment_gateway_interface_binding(): void
    {
        $this->assertInstanceOf(ManualPaymentGateway::class, $this->gateway);
        $this->assertStringContainsString('Manual / Offline Gateway', $this->gateway->getGatewayName());
    }

    public function test_three_tender_methods_checkout_lifecycle(): void
    {
        $tenant = Tenant::create(['name' => 'Gateway Tenant', 'slug' => 'gateway-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Squash', 'slug' => 'squash']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Squash 1',
            'type' => 'indoor',
            'hourly_rate' => 2500,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Checkout Player',
            'email' => 'checkout@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $date1 = Carbon::today()->addDays(2)->toDateString();
        $date2 = Carbon::today()->addDays(3)->toDateString();
        $date3 = Carbon::today()->addDays(4)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date1,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'available',
            'price' => 2500,
        ]);

        // 1. Pay at venue -> booking status confirmed, payment status unpaid
        $venueBooking = $this->bookingEngine->createBooking($user, $court, $date1, '10:00', '11:00', [], 'pay_at_venue');
        $this->assertEquals('confirmed', $venueBooking->status);
        $this->assertEquals('unpaid', $venueBooking->payment_status);

        // 2. Bank transfer -> booking status confirmed, payment status payment_pending
        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date2,
            'start_time' => '11:00',
            'end_time' => '12:00',
            'status' => 'available',
            'price' => 2500,
        ]);

        $bankBooking = $this->bookingEngine->createBooking($user, $court, $date2, '11:00', '12:00', [], 'bank_transfer');
        $this->assertEquals('confirmed', $bankBooking->status);
        $this->assertEquals('payment_pending', $bankBooking->payment_status);

        // 3. Wallet credits tender -> booking status confirmed, payment status paid
        $this->creditAndPassService->issueCredits($user, 5000.00, 'Topup');
        $user->refresh();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date3,
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'available',
            'price' => 2500,
        ]);

        $creditBooking = $this->bookingEngine->createBooking($user, $court, $date3, '14:00', '15:00', [], 'credits');
        $this->assertEquals('confirmed', $creditBooking->status);
        $this->assertEquals('paid', $creditBooking->payment_status);
    }

    public function test_staff_mark_paid_action(): void
    {
        $tenant = Tenant::create(['name' => 'Approval Tenant', 'slug' => 'approval-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Tennis 2',
            'type' => 'outdoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Staff Member',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'front_desk',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bank Transfer Player',
            'email' => 'bankplayer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'user_id' => $user->id,
            'booking_reference' => 'CCC-2026-99999',
            'booking_date' => Carbon::today()->addDays(1)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'status' => 'confirmed',
            'payment_status' => 'payment_pending',
            'payment_method' => 'bank_transfer',
            'total_amount' => 3000.00,
        ]);

        $response = $this->actingAs($staff)->post(route('admin.bookings.mark-paid', ['id' => $booking->id]));
        $response->assertRedirect();

        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertNotNull($booking->paid_at);
    }
}
