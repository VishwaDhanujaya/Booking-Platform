<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Services\BookingEngineService;
use App\Services\CreditAndPassService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected InvoiceService $invoiceService;
    protected BookingEngineService $bookingEngine;
    protected CreditAndPassService $creditAndPassService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceService = new InvoiceService();
        $this->creditAndPassService = new CreditAndPassService();
        $this->bookingEngine = new BookingEngineService(null, $this->creditAndPassService, $this->invoiceService);
    }

    public function test_automatic_invoice_generation_on_credit_checkout(): void
    {
        $tenant = Tenant::create(['name' => 'Invoice Tenant', 'slug' => 'invoice-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court 1',
            'type' => 'outdoor',
            'hourly_rate' => 4000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Invoice Customer',
            'email' => 'invoice@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->creditAndPassService->issueCredits($user, 10000.00, 'Topup');
        $user->refresh();

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

        $booking = $this->bookingEngine->createBooking($user, $court, $date, '10:00', '11:00', [], 'credits');

        $invoice = Invoice::where('booking_id', '=', $booking->id, 'and')->first();

        $this->assertNotNull($invoice);
        $this->assertStringStartsWith('INV-2026-', $invoice->invoice_number);
        $this->assertEquals(4000.00, $invoice->total_amount);
        $this->assertEquals('paid', $invoice->status);
    }

    public function test_invoice_generated_on_staff_mark_paid_action(): void
    {
        $tenant = Tenant::create(['name' => 'Staff Invoice Tenant', 'slug' => 'staff-inv-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Padel', 'slug' => 'padel']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Padel 1',
            'type' => 'indoor',
            'hourly_rate' => 5000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Staff Admin',
            'email' => 'staffinv@test.com',
            'password' => bcrypt('password'),
            'role' => 'front_desk',
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bank Customer',
            'email' => 'bankinv@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $date = Carbon::today()->addDays(3)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '15:00',
            'end_time' => '16:00',
            'status' => 'available',
            'price' => 5000,
        ]);

        $booking = $this->bookingEngine->createBooking($user, $court, $date, '15:00', '16:00', [], 'bank_transfer');

        $this->assertNull(Invoice::where('booking_id', '=', $booking->id, 'and')->first());

        // Staff marks paid
        $response = $this->actingAs($staff)->post(route('admin.bookings.mark-paid', ['id' => $booking->id]));
        $response->assertRedirect();

        $invoice = Invoice::where('booking_id', '=', $booking->id, 'and')->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(5000.00, $invoice->total_amount);
    }

    public function test_invoice_view_routes_accessible(): void
    {
        $tenant = Tenant::create(['name' => 'View Tenant', 'slug' => 'view-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Badminton', 'slug' => 'badminton']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Badminton A',
            'type' => 'indoor',
            'hourly_rate' => 3000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'View Customer',
            'email' => 'viewcust@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'user_id' => $user->id,
            'booking_reference' => 'CCC-2026-77777',
            'booking_date' => Carbon::today()->addDays(1)->toDateString(),
            'start_time' => '08:00',
            'end_time' => '09:00',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'credits',
            'base_amount' => 3000.00,
            'total_amount' => 3000.00,
        ]);

        $invoice = $this->invoiceService->generateInvoice($booking);

        $staff = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'View Staff',
            'email' => 'viewstaff@test.com',
            'password' => bcrypt('password'),
            'role' => 'front_desk',
        ]);

        // Public/Customer Invoice View
        $response1 = $this->get(route('booking.invoice', ['reference' => $booking->booking_reference]));
        $response1->assertStatus(200);
        $response1->assertSee($invoice->invoice_number);

        // Admin Invoice View
        $response2 = $this->actingAs($staff)->get(route('admin.bookings.invoice', ['id' => $booking->id]));
        $response2->assertStatus(200);
        $response2->assertSee($invoice->invoice_number);
    }
}
