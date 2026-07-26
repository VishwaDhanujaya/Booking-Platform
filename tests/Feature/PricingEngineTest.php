<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Court;
use App\Models\SportCategory;
use App\Models\PricingRule;
use App\Models\TimeSlot;
use App\Services\PricingEngineService;
use App\Services\BookingEngineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PricingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected PricingEngineService $pricingEngine;
    protected BookingEngineService $bookingEngine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingEngine = new PricingEngineService();
        $this->bookingEngine = new BookingEngineService($this->pricingEngine);
    }

    public function test_pricing_engine_calculates_base_peak_and_discounts(): void
    {
        $tenant = Tenant::create(['name' => 'Pricing Tenant', 'slug' => 'pricing-tenant']);
        $category = SportCategory::create(['tenant_id' => $tenant->id, 'name' => 'Tennis', 'slug' => 'tennis']);
        $court = Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $category->id,
            'name' => 'Court A',
            'type' => 'outdoor',
            'hourly_rate' => 4000,
            'peak_hourly_rate' => 5000,
            'max_capacity' => 1,
            'is_active' => true,
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Customer A',
            'email' => 'customerA@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $date = Carbon::today()->addDays(3)->toDateString();

        // 1. Off-peak calculation (10:00 - 12:00 = 2 hours @ LKR 4000/hr = 8000 base)
        $offPeakPricing = $this->pricingEngine->calculatePrice(
            $court,
            $date,
            '10:00',
            '12:00',
            $user,
            [],
            2 // 2 slots -> triggers 10% multi-slot discount
        );

        $this->assertEquals(8000, $offPeakPricing['base_rate']);
        $this->assertEquals(800, $offPeakPricing['discount_amount']); // 10% of 8000
        $this->assertEquals(7200, $offPeakPricing['total_amount']);

        // 2. Peak calculation (18:00 - 20:00 = 2 hours @ LKR 5000/hr peak)
        PricingRule::create([
            'tenant_id' => $tenant->id,
            'name' => 'Evening Peak',
            'rule_type' => 'peak',
            'adjustment_type' => 'percentage',
            'adjustment_value' => 25,
            'start_time' => '17:00',
            'end_time' => '22:00',
            'is_active' => true,
        ]);

        $peakPricing = $this->pricingEngine->calculatePrice(
            $court,
            $date,
            '18:00',
            '20:00',
            $user,
            [],
            1
        );

        $this->assertEquals(8000, $peakPricing['base_rate']);
        $this->assertEquals(2000, $peakPricing['peak_adjustment']); // +25% peak
        $this->assertEquals(10000, $peakPricing['total_amount']);
    }

    public function test_booking_stores_itemized_price_breakdown_json(): void
    {
        $tenant = Tenant::create(['name' => 'Breakdown Tenant', 'slug' => 'breakdown-tenant']);
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

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Breakdown Customer',
            'email' => 'breakdown@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $date = Carbon::today()->addDays(1)->toDateString();

        TimeSlot::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'available',
            'price' => 5000,
        ]);

        $addons = [['name' => 'Pro Racket Rental x2', 'price' => 1200]];

        $booking = $this->bookingEngine->createBooking(
            $user,
            $court,
            $date,
            '14:00',
            '15:00',
            $addons,
            'pay_at_venue'
        );

        $this->assertNotNull($booking->price_breakdown);
        $this->assertEquals(5000, $booking->base_amount);
        $this->assertEquals(1200, $booking->addons_amount);
        $this->assertEquals(6200, $booking->total_amount);
        $this->assertIsArray($booking->price_breakdown['line_items']);
    }
}
