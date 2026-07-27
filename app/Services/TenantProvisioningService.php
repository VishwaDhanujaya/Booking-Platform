<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\PricingRule;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TenantProvisioningService
{
    /**
     * Self-serve tenant registration and automatic inventory provisioning.
     */
    public function provisionTenant(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $slug = \Illuminate\Support\Str::slug($data['slug'] ?? $data['business_name']);

            // 1. Create Tenant Record
            $tenant = Tenant::create([
                'name' => $data['business_name'],
                'slug' => $slug,
                'domain' => $slug . '.bookingplatform.lk',
                'email' => $data['owner_email'],
                'phone' => $data['owner_phone'] ?? null,
                'subscription_plan' => $data['subscription_plan'] ?? 'pro',
                'subscription_status' => 'active',
                'brand_color' => $data['brand_color'] ?? '#0284c7',
                'is_active' => true,
            ]);

            // 2. Create Owner Account
            $owner = User::create([
                'tenant_id' => $tenant->id,
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'phone' => $data['owner_phone'] ?? null,
                'password' => bcrypt($data['password']),
                'role' => 'owner',
            ]);

            // 3. Seed Default Sport Categories
            $tennisCategory = SportCategory::create([
                'tenant_id' => $tenant->id,
                'name' => 'Tennis & Racket Sports',
                'slug' => 'tennis',
                'description' => 'Professional tennis & racket sport courts.',
            ]);

            $badmintonCategory = SportCategory::create([
                'tenant_id' => $tenant->id,
                'name' => 'Badminton',
                'slug' => 'badminton',
                'description' => 'Indoor synthetic badminton courts.',
            ]);

            // 4. Seed Starter Court Resources
            $court1 = Court::create([
                'tenant_id' => $tenant->id,
                'sport_category_id' => $tennisCategory->id,
                'name' => 'Court 1 (Indoor AC)',
                'type' => 'indoor',
                'surface_type' => 'Plexicushion Hard',
                'hourly_rate' => 4500.00,
                'peak_hourly_rate' => 5500.00,
                'image_url' => '/images/courts/tennis.png',
                'description' => 'Championship indoor air-conditioned court.',
                'max_capacity' => 1,
                'is_active' => true,
            ]);

            $court2 = Court::create([
                'tenant_id' => $tenant->id,
                'sport_category_id' => $badmintonCategory->id,
                'name' => 'Court 2 (Badminton Synthetic)',
                'type' => 'indoor',
                'surface_type' => 'BWF Approved Rubber Mat',
                'hourly_rate' => 3000.00,
                'peak_hourly_rate' => 4000.00,
                'image_url' => '/images/courts/badminton.png',
                'description' => 'Standard tournament badminton court.',
                'max_capacity' => 1,
                'is_active' => true,
            ]);

            // 5. Seed Starter Pricing Rules
            PricingRule::create([
                'tenant_id' => $tenant->id,
                'name' => 'Evening Peak Window (+25%)',
                'rule_type' => 'peak',
                'adjustment_type' => 'percentage',
                'adjustment_value' => 25.00,
                'start_time' => '17:00',
                'end_time' => '22:00',
                'priority' => 1,
                'is_active' => true,
            ]);

            // 6. Generate 7 Days of TimeSlots for Starter Courts
            $startDate = Carbon::today();
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $startDate->copy()->addDays($d)->toDateString();

                foreach ([$court1, $court2] as $court) {
                    for ($hour = 6; $hour < 22; $hour++) {
                        $start = sprintf('%02d:00', $hour);
                        $end = sprintf('%02d:00', $hour + 1);

                        TimeSlot::create([
                            'tenant_id' => $tenant->id,
                            'court_id' => $court->id,
                            'date' => $dateStr,
                            'start_time' => $start,
                            'end_time' => $end,
                            'status' => 'available',
                            'price' => ($hour >= 17) ? $court->peak_hourly_rate : $court->hourly_rate,
                        ]);
                    }
                }
            }

            Log::info("==========================================");
            Log::info("[TENANT SELF-SERVE PROVISIONED]");
            Log::info("Tenant Name: {$tenant->name}");
            Log::info("Subdomain Slug: {$tenant->slug}");
            Log::info("Subscription Plan: " . strtoupper($tenant->subscription_plan));
            Log::info("Owner Account: {$owner->name} ({$owner->email})");
            Log::info("Status: Fully Operational with Starter Courts & 7-Day TimeSlots.");
            Log::info("==========================================");

            return [
                'tenant' => $tenant,
                'owner' => $owner,
            ];
        });
    }
}
