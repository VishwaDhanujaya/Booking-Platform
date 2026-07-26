<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use App\Models\Role;
use App\Models\User;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\Schedule;
use App\Models\TimeSlot;
use App\Models\AddOn;
use App\Models\PricingRule;
use App\Models\CreditLedger;
use App\Models\CustomerPass;
use App\Models\PassLedgerEntry;
use App\Models\NoShowRecord;
use App\Models\Booking;
use App\Models\BookingAddOn;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Tenant
        $tenant = Tenant::create([
            'name' => 'Colombo Courts Club',
            'slug' => 'colombo-courts-club',
            'domain' => 'colombocourts.local',
            'address' => '45 Maitland Crescent, Colombo 00700, Sri Lanka',
            'phone' => '+94 11 234 5678',
            'email' => 'info@colombocourts.lk',
            'brand_color' => '#0284c7',
            'logo_url' => '/images/logo.png',
            'theme_settings' => [
                'primary_color' => '#0284c7',
                'accent_color' => '#0f172a',
                'font_family' => 'Inter, sans-serif'
            ],
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
        ]);

        // 2. Create Roles
        $ownerRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Owner',
            'slug' => 'owner',
            'description' => 'Full administrative control over facility settings, staff, and pricing.',
            'permissions' => ['all' => true],
        ]);

        $managerRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Operational management of schedules, court bookings, and customer ledgers.',
            'permissions' => ['manage_bookings' => true, 'manage_courts' => true, 'manage_customers' => true],
        ]);

        $staffRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Trainer / Staff',
            'slug' => 'trainer_staff',
            'description' => 'Court coaching schedules and attendance tracking.',
            'permissions' => ['view_bookings' => true, 'mark_attendance' => true],
        ]);

        $frontDeskRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Front Desk',
            'slug' => 'front_desk',
            'description' => 'Check-ins, cash/bank payments, and walk-in reservations.',
            'permissions' => ['create_bookings' => true, 'process_payments' => true],
        ]);

        $customerRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Customer',
            'slug' => 'customer',
            'description' => 'Public facility user capable of reserving courts and purchasing passes.',
            'permissions' => ['book_courts' => true],
        ]);

        // 3. Create Users
        $ownerUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Aruni Fernando (Owner)',
            'email' => 'admin@colombocourts.lk',
            'phone' => '+94 77 000 1111',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
        $ownerUser->roles()->attach($ownerRole);

        $managerUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Dinesh Wickramasinghe',
            'email' => 'manager@colombocourts.lk',
            'phone' => '+94 77 222 3333',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
        $managerUser->roles()->attach($managerRole);

        $staffUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Coach Ruwan Bandara',
            'email' => 'staff@colombocourts.lk',
            'phone' => '+94 77 444 5555',
            'password' => Hash::make('password'),
            'role' => 'trainer_staff',
        ]);
        $staffUser->roles()->attach($staffRole);

        $frontDeskUser = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Samanthi Silva',
            'email' => 'frontdesk@colombocourts.lk',
            'phone' => '+94 77 666 7777',
            'password' => Hash::make('password'),
            'role' => 'front_desk',
        ]);
        $frontDeskUser->roles()->attach($frontDeskRole);

        // Customers
        $customerKavinda = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kavinda Perera',
            'email' => 'kavinda@example.com',
            'phone' => '+94 77 123 4567',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        $customerKavinda->roles()->attach($customerRole);

        $customerNizal = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Nizal Shanaka',
            'email' => 'nizal@example.com',
            'phone' => '+94 71 987 6543',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        $customerNizal->roles()->attach($customerRole);

        // Banned Customer
        $customerBanned = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Kasun Jayawardena',
            'email' => 'kasun.banned@example.com',
            'phone' => '+94 70 111 2233',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_banned' => true,
            'banned_until' => Carbon::now()->addDays(30),
            'ban_reason' => 'Account automatically suspended due to 3 unexcused no-shows within 30 days.',
        ]);
        $customerBanned->roles()->attach($customerRole);

        // 4. Sport Categories
        $tennis = SportCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tennis',
            'slug' => 'tennis',
            'icon_type' => 'tennis',
            'description' => 'Championship acrylic hard courts with high-performance LED floodlighting.',
        ]);

        $padel = SportCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Padel',
            'slug' => 'padel',
            'icon_type' => 'padel',
            'description' => 'International FIP standard glass-walled enclosed padel courts.',
        ]);

        $badminton = SportCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Badminton',
            'slug' => 'badminton',
            'icon_type' => 'badminton',
            'description' => 'Air-conditioned indoor halls with BWF-certified PVC mats.',
        ]);

        $squash = SportCategory::create([
            'tenant_id' => $tenant->id,
            'name' => 'Squash',
            'slug' => 'squash',
            'icon_type' => 'squash',
            'description' => 'World Squash Federation approved glass-backed courts with maple floors.',
        ]);

        // 5. Courts / Resources
        $courtsData = [
            [
                'tenant_id' => $tenant->id,
                'sport_category_id' => $tennis->id,
                'name' => 'Center Court - Tennis 1',
                'type' => 'outdoor',
                'surface_type' => 'Plexicushion Hard',
                'hourly_rate' => 3500.00,
                'peak_hourly_rate' => 4500.00,
                'buffer_time_minutes' => 15,
                'max_capacity' => 1,
                'image_url' => '/images/courts/tennis.png',
                'description' => 'Championship center court with spectator seating & night floodlights.',
            ],
            [
                'tenant_id' => $tenant->id,
                'sport_category_id' => $tennis->id,
                'name' => 'Hard Court - Tennis 2',
                'type' => 'outdoor',
                'surface_type' => 'Acrylic Hard Court',
                'hourly_rate' => 3500.00,
                'peak_hourly_rate' => 4500.00,
                'buffer_time_minutes' => 15,
                'max_capacity' => 1,
                'image_url' => '/images/courts/tennis.png',
                'description' => 'Tournament grade hard court with high grip surface.',
            ],
            [
                'tenant_id' => $tenant->id,
                'sport_category_id' => $padel->id,
                'name' => 'Panoramic Glass Padel 1',
                'type' => 'indoor',
                'surface_type' => 'Monofilament Turf',
                'hourly_rate' => 4500.00,
                'peak_hourly_rate' => 5800.00,
                'buffer_time_minutes' => 10,
                'max_capacity' => 1,
                'image_url' => '/images/courts/padel.png',
                'description' => 'Full panoramic glass rear wall with tournament blue turf.',
            ],
            [
                'tenant_id' => $tenant->id,
                'sport_category_id' => $badminton->id,
                'name' => 'AC Badminton Court 1',
                'type' => 'indoor',
                'surface_type' => 'BWF Green PVC Mat',
                'hourly_rate' => 2500.00,
                'peak_hourly_rate' => 3200.00,
                'buffer_time_minutes' => 0,
                'max_capacity' => 1,
                'image_url' => '/images/courts/badminton.png',
                'description' => 'Fully air-conditioned hall with anti-glare shadowless lighting.',
            ],
            [
                'tenant_id' => $tenant->id,
                'sport_category_id' => $squash->id,
                'name' => 'WSF Glass Court 1',
                'type' => 'indoor',
                'surface_type' => 'Sprung Maple',
                'hourly_rate' => 2800.00,
                'peak_hourly_rate' => 3600.00,
                'buffer_time_minutes' => 0,
                'max_capacity' => 1,
                'image_url' => '/images/courts/squash.png',
                'description' => 'World Squash Federation approved hardwood court with glass backwall.',
            ],
        ];

        $createdCourts = [];
        foreach ($courtsData as $c) {
            $createdCourts[] = Court::create($c);
        }

        // 6. Reusable Add-Ons
        $addonRacket = AddOn::create([
            'tenant_id' => $tenant->id,
            'name' => 'Pro Racket Rental x 2',
            'category' => 'equipment',
            'description' => 'Pair of Wilson/Head professional rackets.',
            'price' => 1200.00,
            'pricing_type' => 'per_booking',
            'stock_quantity' => 20,
        ]);

        $addonBalls = AddOn::create([
            'tenant_id' => $tenant->id,
            'name' => 'Match Balls Tube (Babolat)',
            'category' => 'equipment',
            'description' => 'New sealed pressure canister of 3 match balls.',
            'price' => 1500.00,
            'pricing_type' => 'per_item',
            'stock_quantity' => 50,
        ]);

        $addonLocker = AddOn::create([
            'tenant_id' => $tenant->id,
            'name' => 'Locker & Premium Towel Access',
            'category' => 'facility',
            'description' => 'Keyed locker storage and clean shower towels.',
            'price' => 500.00,
            'pricing_type' => 'per_booking',
            'stock_quantity' => 30,
        ]);

        $addonLights = AddOn::create([
            'tenant_id' => $tenant->id,
            'name' => 'Night LED Floodlights',
            'category' => 'facility',
            'description' => 'High intensity LED lighting surcharge after 18:00.',
            'price' => 1000.00,
            'pricing_type' => 'per_hour',
            'stock_quantity' => null,
        ]);

        // 7. Pricing Rules
        PricingRule::create([
            'tenant_id' => $tenant->id,
            'court_id' => null, // tenant-wide
            'name' => 'Evening Peak Surcharge',
            'rule_type' => 'peak',
            'discount_type' => 'none',
            'adjustment_type' => 'percentage',
            'adjustment_value' => 25.00, // +25%
            'start_time' => '16:00',
            'end_time' => '22:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'priority' => 1,
        ]);

        PricingRule::create([
            'tenant_id' => $tenant->id,
            'court_id' => null,
            'name' => 'Student & Senior Special',
            'rule_type' => 'discount',
            'discount_type' => 'student',
            'adjustment_type' => 'percentage',
            'adjustment_value' => -15.00, // -15%
            'start_time' => '08:00',
            'end_time' => '16:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'priority' => 2,
        ]);

        PricingRule::create([
            'tenant_id' => $tenant->id,
            'court_id' => null,
            'name' => 'Multi-Slot Bulk Discount',
            'rule_type' => 'discount',
            'discount_type' => 'multi_booking',
            'adjustment_type' => 'percentage',
            'adjustment_value' => -10.00, // -10% for 3+ consecutive hours
            'min_slots' => 3,
            'priority' => 3,
        ]);

        // 8. Schedules & One-off Blocked Times
        foreach ($createdCourts as $court) {
            Schedule::create([
                'tenant_id' => $tenant->id,
                'court_id' => $court->id,
                'title' => 'Standard Operating Hours',
                'type' => 'recurring',
                'day_of_week' => null,
                'start_time' => '06:00',
                'end_time' => '22:00',
                'is_available' => true,
            ]);
        }

        // Add a Tournament Block on Center Court for this Saturday
        $saturdayDate = Carbon::today()->next(Carbon::SATURDAY)->toDateString();
        Schedule::create([
            'tenant_id' => $tenant->id,
            'court_id' => $createdCourts[0]->id,
            'title' => 'National Junior Tennis Open - Semi Finals',
            'type' => 'tournament',
            'specific_date' => $saturdayDate,
            'start_time' => '09:00',
            'end_time' => '14:00',
            'is_available' => false,
            'notes' => 'Court reserved exclusively for junior championship matches.',
        ]);

        // 9. Generate Time Slots for 7 Days
        $today = Carbon::today();
        foreach ($createdCourts as $court) {
            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $loopDate = $today->copy()->addDays($dayOffset);
                $dateStr = $loopDate->toDateString();

                for ($hour = 6; $hour < 22; $hour++) {
                    $startTime = sprintf('%02d:00', $hour);
                    $endTime = sprintf('%02d:00', $hour + 1);

                    // Check if tournament blocked
                    $isBlocked = ($court->id === $createdCourts[0]->id && $dateStr === $saturdayDate && $hour >= 9 && $hour < 14);
                    $isPeak = ($hour >= 16);
                    $price = $isPeak ? ($court->peak_hourly_rate ?? $court->hourly_rate * 1.25) : $court->hourly_rate;

                    if ($isBlocked) {
                        $status = 'blocked';
                        $bookedBy = 'Junior Open Tournament';
                        $blockReason = 'Tournament Enclosure';
                    } else {
                        // Seed realistic pattern: ~30% booked
                        $randVal = rand(1, 100);
                        $isBooked = ($isPeak && $randVal <= 60) || (!$isPeak && $randVal <= 20);
                        $status = $isBooked ? 'booked' : 'available';
                        $bookedBy = $isBooked ? 'Reserved Slot' : null;
                        $blockReason = null;
                    }

                    TimeSlot::create([
                        'tenant_id' => $tenant->id,
                        'court_id' => $court->id,
                        'date' => $dateStr,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => $status,
                        'block_reason' => $blockReason,
                        'is_peak' => $isPeak,
                        'price' => $price,
                        'booked_by_name' => $bookedBy,
                    ]);
                }
            }
        }

        // 10. Credit Ledger & Customer Passes
        // Top up Kavinda's Wallet
        CreditLedger::create([
            'tenant_id' => $tenant->id,
            'user_id' => $customerKavinda->id,
            'amount_in' => 15000.00,
            'amount_out' => 0.00,
            'balance_after' => 15000.00,
            'reason' => 'Initial Wallet Top Up via Bank Deposit',
            'reference_type' => 'topup',
            'created_by' => $managerUser->id,
        ]);

        CreditLedger::create([
            'tenant_id' => $tenant->id,
            'user_id' => $customerNizal->id,
            'amount_in' => 5000.00,
            'amount_out' => 0.00,
            'balance_after' => 5000.00,
            'reason' => 'Promotional Sign-up Bonus',
            'reference_type' => 'adjustment',
            'created_by' => $ownerUser->id,
        ]);

        // Customer Pass for Kavinda
        $kavindaPass = CustomerPass::create([
            'tenant_id' => $tenant->id,
            'user_id' => $customerKavinda->id,
            'pass_name' => '10-Session Padel & Tennis Pass',
            'total_units' => 10,
            'remaining_units' => 8,
            'price_paid' => 30000.00,
            'expires_at' => Carbon::now()->addMonths(3)->toDateString(),
            'status' => 'active',
        ]);

        PassLedgerEntry::create([
            'customer_pass_id' => $kavindaPass->id,
            'units_in' => 10,
            'units_out' => 0,
            'units_after' => 10,
            'reason' => 'Pass Purchase (CCC-PASS-881)',
        ]);

        PassLedgerEntry::create([
            'customer_pass_id' => $kavindaPass->id,
            'units_in' => 0,
            'units_out' => 2,
            'units_after' => 8,
            'reason' => 'Redeemed 2 Hours on Panoramic Padel Court',
        ]);

        // 11. No-Show Log Records for Banned User
        for ($i = 1; $i <= 3; $i++) {
            $pastBooking = Booking::create([
                'tenant_id' => $tenant->id,
                'court_id' => $createdCourts[1]->id,
                'user_id' => $customerBanned->id,
                'booking_reference' => 'CCC-2026-NOSHOW-' . $i,
                'booking_date' => Carbon::today()->subDays($i * 5)->toDateString(),
                'start_time' => '18:00',
                'end_time' => '19:00',
                'customer_name' => $customerBanned->name,
                'customer_email' => $customerBanned->email,
                'customer_phone' => $customerBanned->phone,
                'status' => 'no_show',
                'payment_status' => 'unpaid',
                'payment_method' => 'pay_at_venue',
                'base_amount' => 3500.00,
                'addons_amount' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 3500.00,
            ]);

            NoShowRecord::create([
                'tenant_id' => $tenant->id,
                'user_id' => $customerBanned->id,
                'booking_id' => $pastBooking->id,
                'logged_by' => $frontDeskUser->id,
                'notes' => 'Customer failed to show up without prior 24h cancellation.',
                'occurred_at' => Carbon::today()->subDays($i * 5)->setHour(19),
            ]);
        }

        // 12. Create Real Database Bookings for Kavinda
        $b1 = Booking::create([
            'tenant_id' => $tenant->id,
            'court_id' => $createdCourts[0]->id,
            'user_id' => $customerKavinda->id,
            'booking_reference' => 'CCC-2026-9812',
            'booking_date' => $today->copy()->addDays(1)->toDateString(),
            'start_time' => '17:00',
            'end_time' => '19:00',
            'customer_name' => $customerKavinda->name,
            'customer_email' => $customerKavinda->email,
            'customer_phone' => $customerKavinda->phone,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'credits',
            'base_amount' => 7000.00,
            'addons_amount' => 2200.00,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 9200.00,
            'price_breakdown' => [
                'base_rate' => 3500.00,
                'hours' => 2,
                'peak_surcharge' => 0,
                'discount' => 0,
                'addons_total' => 2200.00
            ],
        ]);

        BookingAddOn::create([
            'booking_id' => $b1->id,
            'add_on_id' => $addonRacket->id,
            'name' => $addonRacket->name,
            'quantity' => 1,
            'unit_price' => 1200.00,
            'total_price' => 1200.00,
        ]);

        BookingAddOn::create([
            'booking_id' => $b1->id,
            'add_on_id' => $addonLights->id,
            'name' => $addonLights->name,
            'quantity' => 1,
            'unit_price' => 1000.00,
            'total_price' => 1000.00,
        ]);

        $b2 = Booking::create([
            'tenant_id' => $tenant->id,
            'court_id' => $createdCourts[2]->id,
            'user_id' => $customerKavinda->id,
            'booking_reference' => 'CCC-2026-4410',
            'booking_date' => $today->copy()->addDays(3)->toDateString(),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'customer_name' => $customerKavinda->name,
            'customer_email' => $customerKavinda->email,
            'customer_phone' => $customerKavinda->phone,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'pass',
            'base_amount' => 11600.00,
            'addons_amount' => 1500.00,
            'discount_amount' => 11600.00, // Covered by pass
            'tax_amount' => 0,
            'total_amount' => 1500.00,
            'price_breakdown' => [
                'base_rate' => 5800.00,
                'hours' => 2,
                'pass_redemption' => '10-Session Pass (2 units)',
                'addons_total' => 1500.00
            ],
        ]);

        BookingAddOn::create([
            'booking_id' => $b2->id,
            'add_on_id' => $addonBalls->id,
            'name' => $addonBalls->name,
            'quantity' => 1,
            'unit_price' => 1500.00,
            'total_price' => 1500.00,
        ]);
    }
}
