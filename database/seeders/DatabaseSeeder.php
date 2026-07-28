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
        // ==========================================
        // PLATFORM SUPER ADMIN (SLTDS Staff)
        // ==========================================
        User::create([
            'tenant_id' => null,
            'name' => 'SLTDS Platform Admin',
            'email' => 'superadmin@sltdigital.com',
            'phone' => '+94 11 000 0000',
            'password' => Hash::make('password'),
            'role' => 'platform_admin',
            'is_super_admin' => true,
        ]);

        // ==========================================
        // TENANT 1: Colombo Courts Club
        // ==========================================
        $t1 = Tenant::create([
            'name' => 'Colombo Courts Club',
            'slug' => 'colombo-courts-club',
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Premier Multi-Sport & Court Complex in Colombo',
            'description' => '6 international standard badminton courts, 4 floodlit tennis courts, and 2 championship padel arenas.',
            'location' => 'Colombo, Sri Lanka',
            'domain' => 'colombo-courts-club.localhost',
            'address' => '45 Maitland Crescent, Colombo 00700, Sri Lanka',
            'phone' => '+94 11 234 5678',
            'email' => 'info@colombocourts.lk',
            'opening_hours' => 'Mon - Sun: 6:00 AM - 10:00 PM',
            'brand_color' => '#0056A2',
            'logo_url' => null,
            'hero_headline' => 'Reserve Premium Colombo Courts Online',
            'hero_subheading' => 'Instant real-time booking for Tennis, Padel, Badminton & Squash with automated court lighting.',
            'hero_image_url' => '/images/hero_sports_court.png',
            'notices' => [
                ['title' => 'Non-Marking Shoes Required for Badminton', 'link' => '#rules', 'icon' => 'document-text'],
                ['title' => 'Special Member Pass Vouchers Available', 'link' => '#vouchers', 'icon' => 'ticket'],
            ],
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        // Tenant 1 Roles
        $t1OwnerRole = Role::create([
            'tenant_id' => $t1->id,
            'name' => 'Owner',
            'slug' => 'owner',
            'description' => 'Full administrative control over facility settings, staff, and pricing.',
            'permissions' => ['all' => true],
        ]);

        $t1ManagerRole = Role::create([
            'tenant_id' => $t1->id,
            'name' => 'Manager',
            'slug' => 'manager',
            'description' => 'Operational management of schedules, court bookings, and customer ledgers.',
            'permissions' => ['manage_bookings' => true, 'manage_courts' => true, 'manage_customers' => true],
        ]);

        $t1StaffRole = Role::create([
            'tenant_id' => $t1->id,
            'name' => 'Trainer / Staff',
            'slug' => 'trainer_staff',
            'description' => 'Court coaching schedules and attendance tracking.',
            'permissions' => ['view_bookings' => true, 'mark_attendance' => true],
        ]);

        $t1FrontDeskRole = Role::create([
            'tenant_id' => $t1->id,
            'name' => 'Front Desk',
            'slug' => 'front_desk',
            'description' => 'Check-ins, cash/bank payments, and walk-in reservations.',
            'permissions' => ['create_bookings' => true, 'process_payments' => true],
        ]);

        $t1CustomerRole = Role::create([
            'tenant_id' => $t1->id,
            'name' => 'Customer',
            'slug' => 'customer',
            'description' => 'Public facility user capable of reserving courts and purchasing passes.',
            'permissions' => ['book_courts' => true],
        ]);

        // Tenant 1 Users
        $t1Owner = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Aruni Fernando (Owner)',
            'email' => 'admin@colombocourts.lk',
            'phone' => '+94 77 000 1111',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
        $t1Owner->roles()->attach($t1OwnerRole);

        $t1Manager = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Dinesh Wickramasinghe',
            'email' => 'manager@colombocourts.lk',
            'phone' => '+94 77 222 3333',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
        $t1Manager->roles()->attach($t1ManagerRole);

        $t1Staff = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Coach Ruwan Bandara',
            'email' => 'staff@colombocourts.lk',
            'phone' => '+94 77 444 5555',
            'password' => Hash::make('password'),
            'role' => 'trainer_staff',
        ]);
        $t1Staff->roles()->attach($t1StaffRole);

        $t1FrontDesk = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Samanthi Silva',
            'email' => 'frontdesk@colombocourts.lk',
            'phone' => '+94 77 666 7777',
            'password' => Hash::make('password'),
            'role' => 'front_desk',
        ]);
        $t1FrontDesk->roles()->attach($t1FrontDeskRole);

        $t1Kavinda = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Kavinda Perera',
            'email' => 'kavinda@example.com',
            'phone' => '+94 77 123 4567',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        $t1Kavinda->roles()->attach($t1CustomerRole);

        $t1Nizal = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Nizal Shanaka',
            'email' => 'nizal@example.com',
            'phone' => '+94 71 987 6543',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        $t1Nizal->roles()->attach($t1CustomerRole);

        $t1Banned = User::create([
            'tenant_id' => $t1->id,
            'name' => 'Kasun Jayawardena',
            'email' => 'kasun.banned@example.com',
            'phone' => '+94 70 111 2233',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_banned' => true,
            'banned_until' => Carbon::now()->addDays(30),
            'ban_reason' => 'Account automatically suspended due to 3 unexcused no-shows within 30 days.',
        ]);
        $t1Banned->roles()->attach($t1CustomerRole);

        // Tenant 1 Sport Categories
        $t1Tennis = SportCategory::create([
            'tenant_id' => $t1->id,
            'name' => 'Tennis',
            'slug' => 'tennis',
            'icon_type' => 'tennis',
            'description' => 'Championship acrylic hard courts with high-performance LED floodlighting.',
        ]);

        $t1Padel = SportCategory::create([
            'tenant_id' => $t1->id,
            'name' => 'Padel',
            'slug' => 'padel',
            'icon_type' => 'padel',
            'description' => 'International FIP standard glass-walled enclosed padel courts.',
        ]);

        $t1Badminton = SportCategory::create([
            'tenant_id' => $t1->id,
            'name' => 'Badminton',
            'slug' => 'badminton',
            'icon_type' => 'badminton',
            'description' => 'Air-conditioned indoor halls with BWF-certified PVC mats.',
        ]);

        $t1Squash = SportCategory::create([
            'tenant_id' => $t1->id,
            'name' => 'Squash',
            'slug' => 'squash',
            'icon_type' => 'squash',
            'description' => 'World Squash Federation approved glass-backed courts with maple floors.',
        ]);

        // Tenant 1 Courts
        $t1Courts = [
            Court::create([
                'tenant_id' => $t1->id,
                'sport_category_id' => $t1Tennis->id,
                'name' => 'Center Court - Tennis 1',
                'type' => 'outdoor',
                'surface_type' => 'Plexicushion Hard',
                'hourly_rate' => 3500.00,
                'peak_hourly_rate' => 4500.00,
                'buffer_time_minutes' => 15,
                'max_capacity' => 1,
                'image_url' => '/images/courts/tennis.png',
                'description' => 'Championship center court with spectator seating & night floodlights.',
            ]),
            Court::create([
                'tenant_id' => $t1->id,
                'sport_category_id' => $t1Tennis->id,
                'name' => 'Hard Court - Tennis 2',
                'type' => 'outdoor',
                'surface_type' => 'Acrylic Hard Court',
                'hourly_rate' => 3500.00,
                'peak_hourly_rate' => 4500.00,
                'buffer_time_minutes' => 15,
                'max_capacity' => 1,
                'image_url' => '/images/courts/tennis.png',
                'description' => 'Tournament grade hard court with high grip surface.',
            ]),
            Court::create([
                'tenant_id' => $t1->id,
                'sport_category_id' => $t1Padel->id,
                'name' => 'Panoramic Glass Padel 1',
                'type' => 'indoor',
                'surface_type' => 'Monofilament Turf',
                'hourly_rate' => 4500.00,
                'peak_hourly_rate' => 5800.00,
                'buffer_time_minutes' => 10,
                'max_capacity' => 1,
                'image_url' => '/images/courts/padel.png',
                'description' => 'Full panoramic glass rear wall with tournament blue turf.',
            ]),
            Court::create([
                'tenant_id' => $t1->id,
                'sport_category_id' => $t1Badminton->id,
                'name' => 'AC Badminton Court 1',
                'type' => 'indoor',
                'surface_type' => 'BWF Green PVC Mat',
                'hourly_rate' => 2500.00,
                'peak_hourly_rate' => 3200.00,
                'buffer_time_minutes' => 0,
                'max_capacity' => 1,
                'image_url' => '/images/courts/badminton.png',
                'description' => 'Fully air-conditioned hall with anti-glare shadowless lighting.',
            ]),
        ];

        // Tenant 1 Add-ons
        $t1AddonRacket = AddOn::create([
            'tenant_id' => $t1->id,
            'name' => 'Pro Racket Rental x 2',
            'category' => 'equipment',
            'description' => 'Pair of Wilson/Head professional rackets.',
            'price' => 1200.00,
            'pricing_type' => 'per_booking',
            'stock_quantity' => 20,
        ]);

        $t1AddonBalls = AddOn::create([
            'tenant_id' => $t1->id,
            'name' => 'Match Balls Tube (Babolat)',
            'category' => 'equipment',
            'description' => 'New sealed pressure canister of 3 match balls.',
            'price' => 1500.00,
            'pricing_type' => 'per_item',
            'stock_quantity' => 50,
        ]);

        $t1AddonLights = AddOn::create([
            'tenant_id' => $t1->id,
            'name' => 'Night LED Floodlights',
            'category' => 'facility',
            'description' => 'High intensity LED lighting surcharge after 18:00.',
            'price' => 1000.00,
            'pricing_type' => 'per_hour',
            'stock_quantity' => null,
        ]);

        // Tenant 1 Pricing Rules
        PricingRule::create([
            'tenant_id' => $t1->id,
            'name' => 'Evening Peak Surcharge',
            'rule_type' => 'peak',
            'adjustment_type' => 'percentage',
            'adjustment_value' => 25.00,
            'start_time' => '16:00',
            'end_time' => '22:00',
            'days_of_week' => [1, 2, 3, 4, 5],
            'priority' => 1,
        ]);

        // Tenant 1 Time Slots (7 days)
        $today = Carbon::today();
        foreach ($t1Courts as $court) {
            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $dateStr = $today->copy()->addDays($dayOffset)->toDateString();
                for ($hour = 6; $hour < 22; $hour++) {
                    $isPeak = ($hour >= 16);
                    $price = $isPeak ? ($court->peak_hourly_rate ?? $court->hourly_rate * 1.25) : $court->hourly_rate;
                    $isBooked = ($isPeak && rand(1, 100) <= 50);

                    TimeSlot::create([
                        'tenant_id' => $t1->id,
                        'court_id' => $court->id,
                        'date' => $dateStr,
                        'start_time' => sprintf('%02d:00', $hour),
                        'end_time' => sprintf('%02d:00', $hour + 1),
                        'status' => $isBooked ? 'booked' : 'available',
                        'is_peak' => $isPeak,
                        'price' => $price,
                        'booked_by_name' => $isBooked ? 'Reserved Slot' : null,
                    ]);
                }
            }
        }

        // Tenant 1 Credit Ledgers & Passes
        CreditLedger::create([
            'tenant_id' => $t1->id,
            'user_id' => $t1Kavinda->id,
            'amount_in' => 15000.00,
            'balance_after' => 15000.00,
            'reason' => 'Initial Wallet Top Up via Bank Deposit',
            'reference_type' => 'topup',
            'created_by' => $t1Manager->id,
        ]);

        $t1Pass = CustomerPass::create([
            'tenant_id' => $t1->id,
            'user_id' => $t1Kavinda->id,
            'pass_name' => '10-Session Padel & Tennis Pass',
            'total_units' => 10,
            'remaining_units' => 8,
            'price_paid' => 30000.00,
            'expires_at' => Carbon::now()->addMonths(3)->toDateString(),
            'status' => 'active',
        ]);

        // Tenant 1 Bookings
        $b1 = Booking::create([
            'tenant_id' => $t1->id,
            'court_id' => $t1Courts[0]->id,
            'user_id' => $t1Kavinda->id,
            'booking_reference' => 'CCC-2026-9812',
            'booking_date' => $today->copy()->addDays(1)->toDateString(),
            'start_time' => '17:00',
            'end_time' => '19:00',
            'customer_name' => $t1Kavinda->name,
            'customer_email' => $t1Kavinda->email,
            'customer_phone' => $t1Kavinda->phone,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'credits',
            'base_amount' => 7000.00,
            'addons_amount' => 2200.00,
            'total_amount' => 9200.00,
        ]);

        BookingAddOn::create([
            'booking_id' => $b1->id,
            'add_on_id' => $t1AddonRacket->id,
            'name' => $t1AddonRacket->name,
            'quantity' => 1,
            'unit_price' => 1200.00,
            'total_price' => 1200.00,
        ]);

        // ==========================================
        // TENANT 2: Apex Sports Arena (Isolation Verification)
        // ==========================================
        $t2 = Tenant::create([
            'name' => 'Apex Sports Arena',
            'slug' => 'apex-sports-arena',
            'category' => 'Sports Venues & Facilities',
            'tagline' => 'Championship Badminton & Squash Facility',
            'description' => 'WSF certified sprung maple wood squash arena & BWF certified badminton courts with LED lighting.',
            'location' => 'Kandy, Sri Lanka',
            'domain' => 'apex-sports-arena.localhost',
            'address' => '120 Race Course Promenade, Kandy, Sri Lanka',
            'phone' => '+94 81 999 8888',
            'email' => 'info@apexsports.lk',
            'opening_hours' => 'Mon - Sun: 5:00 AM - 11:00 PM',
            'brand_color' => '#50B748',
            'logo_url' => null,
            'hero_headline' => 'Book Championship Kandy Badminton & Squash Courts',
            'hero_subheading' => 'BWF-certified indoor green mats and WSF glass-backed squash courts.',
            'hero_image_url' => '/images/hero_sports_court.png',
            'notices' => [
                ['title' => 'Racket Stringing & Grip Service Onsite', 'link' => '#stringing', 'icon' => 'info'],
                ['title' => 'Student & Senior Discounts Available', 'link' => '#discounts', 'icon' => 'ticket'],
            ],
            'subscription_plan' => 'enterprise',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        // Tenant 2 Roles
        $t2OwnerRole = Role::create([
            'tenant_id' => $t2->id,
            'name' => 'Owner',
            'slug' => 'owner',
            'description' => 'Apex Facility Owner',
            'permissions' => ['all' => true],
        ]);

        $t2CustomerRole = Role::create([
            'tenant_id' => $t2->id,
            'name' => 'Customer',
            'slug' => 'customer',
            'description' => 'Apex Arena Member',
            'permissions' => ['book_courts' => true],
        ]);

        // Tenant 2 Users
        $t2Owner = User::create([
            'tenant_id' => $t2->id,
            'name' => 'Nalin Wickramasinghe (Apex Owner)',
            'email' => 'owner@apexsports.lk',
            'phone' => '+94 81 111 2222',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
        $t2Owner->roles()->attach($t2OwnerRole);

        $t2Nishan = User::create([
            'tenant_id' => $t2->id,
            'name' => 'Nishan Gunaratne',
            'email' => 'nishan@example.com',
            'phone' => '+94 77 999 0000',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        $t2Nishan->roles()->attach($t2CustomerRole);

        // Tenant 2 Sport Categories
        $t2Badminton = SportCategory::create([
            'tenant_id' => $t2->id,
            'name' => 'Badminton',
            'slug' => 'badminton',
            'icon_type' => 'badminton',
            'description' => 'High ceilings, wooden subflooring, and competition green mats.',
        ]);

        $t2Squash = SportCategory::create([
            'tenant_id' => $t2->id,
            'name' => 'Squash',
            'slug' => 'squash',
            'icon_type' => 'squash',
            'description' => 'WSF certified sprung maple wood squash arena.',
        ]);

        // Tenant 2 Courts
        $t2Courts = [
            Court::create([
                'tenant_id' => $t2->id,
                'sport_category_id' => $t2Badminton->id,
                'name' => 'Apex AC Badminton Court 1',
                'type' => 'indoor',
                'surface_type' => 'Yonex BWF Mat',
                'hourly_rate' => 2800.00,
                'peak_hourly_rate' => 3500.00,
                'buffer_time_minutes' => 10,
                'max_capacity' => 1,
                'image_url' => '/images/courts/badminton.png',
                'description' => 'Premier AC badminton court with LED shadow-free illumination.',
            ]),
            Court::create([
                'tenant_id' => $t2->id,
                'sport_category_id' => $t2Squash->id,
                'name' => 'Apex Championship Squash Court',
                'type' => 'indoor',
                'surface_type' => 'Sprung Hard Maple',
                'hourly_rate' => 3200.00,
                'peak_hourly_rate' => 4000.00,
                'buffer_time_minutes' => 15,
                'max_capacity' => 1,
                'image_url' => '/images/courts/squash.png',
                'description' => 'Championship glass-backed court with viewing gallery.',
            ]),
        ];

        // Tenant 2 Add-ons
        $t2AddonRacket = AddOn::create([
            'tenant_id' => $t2->id,
            'name' => 'Yonex Carbonex Racket Rental',
            'category' => 'equipment',
            'description' => 'Single high-tension Yonex racket.',
            'price' => 800.00,
            'pricing_type' => 'per_booking',
            'stock_quantity' => 15,
        ]);

        // Tenant 2 Time Slots
        foreach ($t2Courts as $court) {
            for ($dayOffset = 0; $dayOffset < 7; $dayOffset++) {
                $dateStr = $today->copy()->addDays($dayOffset)->toDateString();
                for ($hour = 6; $hour < 22; $hour++) {
                    $isPeak = ($hour >= 17);
                    $price = $isPeak ? ($court->peak_hourly_rate ?? $court->hourly_rate * 1.2) : $court->hourly_rate;
                    $isBooked = ($isPeak && rand(1, 100) <= 40);

                    TimeSlot::create([
                        'tenant_id' => $t2->id,
                        'court_id' => $court->id,
                        'date' => $dateStr,
                        'start_time' => sprintf('%02d:00', $hour),
                        'end_time' => sprintf('%02d:00', $hour + 1),
                        'status' => $isBooked ? 'booked' : 'available',
                        'is_peak' => $isPeak,
                        'price' => $price,
                        'booked_by_name' => $isBooked ? 'Apex Club Member' : null,
                    ]);
                }
            }
        }

        // Tenant 2 Credit Ledger & Booking
        CreditLedger::create([
            'tenant_id' => $t2->id,
            'user_id' => $t2Nishan->id,
            'amount_in' => 10000.00,
            'balance_after' => 10000.00,
            'reason' => 'Apex Arena Member Deposit',
            'reference_type' => 'topup',
            'created_by' => $t2Owner->id,
        ]);

        Booking::create([
            'tenant_id' => $t2->id,
            'court_id' => $t2Courts[0]->id,
            'user_id' => $t2Nishan->id,
            'booking_reference' => 'APEX-2026-101',
            'booking_date' => $today->copy()->addDays(2)->toDateString(),
            'start_time' => '18:00',
            'end_time' => '20:00',
            'customer_name' => $t2Nishan->name,
            'customer_email' => $t2Nishan->email,
            'customer_phone' => $t2Nishan->phone,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'credits',
            'base_amount' => 7000.00,
            'addons_amount' => 800.00,
            'total_amount' => 7800.00,
        ]);

        // ==========================================
        // TENANT 3: Zenith Yoga & Wellness (Group Classes & Courses)
        // ==========================================
        Tenant::create([
            'name' => 'Zenith Yoga & Wellness',
            'slug' => 'zenith-yoga',
            'category' => 'Group Classes & Courses',
            'tagline' => 'Boutique Hot Yoga, Pilates & Meditation Studio',
            'description' => 'Holistic class bookings, instructor-led workshops, and reformer Pilates sessions with real-time slot reservations.',
            'location' => 'Colombo 03, Sri Lanka',
            'domain' => 'zenith.localhost',
            'address' => '88 Galle Road, Colombo 03, Sri Lanka',
            'phone' => '+94 11 456 7890',
            'email' => 'hello@zenithyoga.lk',
            'brand_color' => '#8b5cf6', // Violet
            'logo_url' => '/images/zenith-logo.png',
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        // ==========================================
        // TENANT 4: Ironworks Gym & Performance Lab (Fitness Equipment)
        // ==========================================
        Tenant::create([
            'name' => 'Ironworks Gym & Performance',
            'slug' => 'ironworks-gym',
            'category' => 'Fitness Equipment',
            'tagline' => 'High-Performance Powerlifting & Gym Equipment Booking',
            'description' => 'Book dedicated power racks, private PT lifting bays, and recovery ice-baths with live capacity tracking.',
            'location' => 'Nugegoda, Sri Lanka',
            'domain' => 'ironworks.localhost',
            'address' => '14 High Level Road, Nugegoda, Sri Lanka',
            'phone' => '+94 11 333 4444',
            'email' => 'contact@ironworksgym.lk',
            'brand_color' => '#ef4444', // Red
            'logo_url' => '/images/ironworks-logo.png',
            'subscription_plan' => 'pro',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);

        // ==========================================
        // TENANT 5: Little Champs Creche & Play (Creche & Childcare)
        // ==========================================
        Tenant::create([
            'name' => 'Little Champs Creche & Play',
            'slug' => 'little-champs',
            'category' => 'Creche',
            'tagline' => 'On-Demand In-Venue Childcare & Playcare Slots',
            'description' => 'Supervised drop-off creche booking for parents during workout and court reservation times.',
            'location' => 'Battaramulla, Sri Lanka',
            'domain' => 'littlechamps.localhost',
            'address' => '55 Main Street, Battaramulla, Sri Lanka',
            'phone' => '+94 11 777 8888',
            'email' => 'care@littlechamps.lk',
            'brand_color' => '#f59e0b', // Amber
            'logo_url' => '/images/champs-logo.png',
            'subscription_plan' => 'starter',
            'subscription_status' => 'active',
            'is_active' => true,
            'is_public' => true,
        ]);
    }
}
