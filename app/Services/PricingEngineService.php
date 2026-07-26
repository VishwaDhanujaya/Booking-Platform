<?php

namespace App\Services;

use App\Models\Court;
use App\Models\PricingRule;
use App\Models\User;
use App\Models\AddOn;
use Carbon\Carbon;

class PricingEngineService
{
    /**
     * Calculate itemized price breakdown for a court reservation.
     *
     * Order of Operations:
     * 1. Base Rate (Court Hourly Rate * Duration)
     * 2. Peak / Off-Peak Time Adjustments
     * 3. Seasonal Date Adjustments
     * 4. Discounts (Multi-booking, Last-minute, Customer Tier)
     * 5. Selected Add-ons
     * 6. Final Net Total
     */
    public function calculatePrice(
        Court $court,
        string $date,
        string $startTime,
        string $endTime,
        ?User $user = null,
        array $selectedAddons = [],
        int $slotCount = 1
    ): array {
        $bookingDate = Carbon::parse($date);
        $startCarbon = Carbon::parse($date . ' ' . $startTime);
        $endCarbon = Carbon::parse($date . ' ' . $endTime);
        
        $durationHours = max(1, round($startCarbon->diffInMinutes($endCarbon) / 60, 2));

        $lineItems = [];

        // 1. BASE COURT RATE
        $baseRate = $court->hourly_rate * $durationHours;
        $lineItems[] = [
            'type' => 'base',
            'code' => 'BASE_RATE',
            'description' => "Base Court Rate ({$durationHours} hr @ LKR " . number_format($court->hourly_rate, 2) . "/hr)",
            'amount' => $baseRate,
        ];

        // Fetch active tenant pricing rules
        $rules = PricingRule::where('tenant_id', '=', $court->tenant_id, 'and')
            ->where('is_active', '=', true, 'and')
            ->orderBy('priority', 'asc')
            ->get();

        // 2. PEAK / OFF-PEAK TIME ADJUSTMENTS
        $peakAdjustment = 0;
        $dayOfWeek = $bookingDate->dayOfWeek; // 0 (Sun) - 6 (Sat)

        foreach ($rules->where('rule_type', 'peak') as $rule) {
            if ($this->matchesCourtAndCategory($rule, $court)) {
                $days = $rule->days_of_week ?? [1, 2, 3, 4, 5, 6, 0];
                if (in_array($dayOfWeek, $days)) {
                    $ruleStart = $rule->start_time ?? '17:00';
                    $ruleEnd = $rule->end_time ?? '22:00';

                    // Check overlap with rule window
                    if ($startTime >= $ruleStart && $startTime < $ruleEnd) {
                        $adj = ($rule->adjustment_type === 'percentage') 
                            ? ($baseRate * ($rule->adjustment_value / 100))
                            : $rule->adjustment_value;

                        $peakAdjustment += $adj;
                        $lineItems[] = [
                            'type' => 'peak_surcharge',
                            'code' => 'PEAK_SURCHARGE',
                            'description' => "Peak Hours Surcharge ({$rule->name})",
                            'amount' => $adj,
                        ];
                        break;
                    }
                }
            }
        }

        // Default Peak fallback if no database rule triggered but court has peak rate
        if ($peakAdjustment == 0 && $court->peak_hourly_rate && $court->peak_hourly_rate > $court->hourly_rate) {
            $isPeakWindow = ($startCarbon->hour >= 17 && $startCarbon->hour < 22) || $bookingDate->isWeekend();
            if ($isPeakWindow) {
                $peakDiff = ($court->peak_hourly_rate - $court->hourly_rate) * $durationHours;
                $peakAdjustment += $peakDiff;
                $lineItems[] = [
                    'type' => 'peak_surcharge',
                    'code' => 'PEAK_SURCHARGE',
                    'description' => "Peak Hours Rate Adjustment (17:00 - 22:00 / Weekend)",
                    'amount' => $peakDiff,
                ];
            }
        }

        // 3. SEASONAL ADJUSTMENT
        $seasonalAdjustment = 0;
        foreach ($rules->where('rule_type', 'seasonal') as $rule) {
            if ($this->matchesCourtAndCategory($rule, $court)) {
                if ($rule->start_date && $rule->end_date) {
                    if ($bookingDate->between($rule->start_date, $rule->end_date)) {
                        $adj = ($rule->adjustment_type === 'percentage')
                            ? ($baseRate * ($rule->adjustment_value / 100))
                            : $rule->adjustment_value;

                        $seasonalAdjustment += $adj;
                        $lineItems[] = [
                            'type' => 'seasonal',
                            'code' => 'SEASONAL_ADJUSTMENT',
                            'description' => "Seasonal Adjustment ({$rule->name})",
                            'amount' => $adj,
                        ];
                    }
                }
            }
        }

        $subtotalBeforeDiscounts = $baseRate + $peakAdjustment + $seasonalAdjustment;

        // 4. DISCOUNTS (Multi-booking, Last-minute, Customer Tier)
        $totalDiscount = 0;

        foreach ($rules->where('rule_type', 'discount') as $rule) {
            if (!$this->matchesCourtAndCategory($rule, $court)) {
                continue;
            }

            $discountAmount = 0;

            // Multi-booking discount
            if ($rule->discount_type === 'multi_booking' && $slotCount >= $rule->min_slots) {
                $discountAmount = ($rule->adjustment_type === 'percentage')
                    ? ($subtotalBeforeDiscounts * (abs($rule->adjustment_value) / 100))
                    : abs($rule->adjustment_value);

                $lineItems[] = [
                    'type' => 'discount',
                    'code' => 'MULTI_SLOT_DISCOUNT',
                    'description' => "Multi-Slot Booking Discount ({$slotCount}+ sessions)",
                    'amount' => -$discountAmount,
                ];
            }

            // Last-minute booking discount (booked within 3 hours of start)
            if ($rule->discount_type === 'last_minute') {
                $hoursNotice = Carbon::now()->diffInHours($startCarbon, false);
                if ($hoursNotice >= 0 && $hoursNotice <= 3) {
                    $discountAmount = ($rule->adjustment_type === 'percentage')
                        ? ($subtotalBeforeDiscounts * (abs($rule->adjustment_value) / 100))
                        : abs($rule->adjustment_value);

                    $lineItems[] = [
                        'type' => 'discount',
                        'code' => 'LAST_MINUTE_DISCOUNT',
                        'description' => "Last-Minute Special Discount ({$rule->name})",
                        'amount' => -$discountAmount,
                    ];
                }
            }

            // Customer Tier Discount (Club Member / Senior / Student)
            if ($user && in_array($rule->discount_type, ['student', 'senior', 'club'])) {
                if (strtolower($user->role) === 'customer' || str_contains(strtolower($rule->name), strtolower($user->role))) {
                    $discountAmount = ($rule->adjustment_type === 'percentage')
                        ? ($subtotalBeforeDiscounts * (abs($rule->adjustment_value) / 100))
                        : abs($rule->adjustment_value);

                    $lineItems[] = [
                        'type' => 'discount',
                        'code' => strtoupper($rule->discount_type) . '_DISCOUNT',
                        'description' => "Member Tier Discount ({$rule->name})",
                        'amount' => -$discountAmount,
                    ];
                }
            }

            $totalDiscount += $discountAmount;
        }

        // Automatic fallback multi-booking discount if >= 2 slots and no DB rule triggered
        if ($totalDiscount == 0 && $slotCount >= 2) {
            $multiDiscount = $subtotalBeforeDiscounts * 0.10; // 10% multi-slot discount
            $totalDiscount += $multiDiscount;
            $lineItems[] = [
                'type' => 'discount',
                'code' => 'MULTI_SLOT_DISCOUNT',
                'description' => 'Multi-Slot Consecutive Booking Discount (10% Off)',
                'amount' => -$multiDiscount,
            ];
        }

        // 5. ADD-ONS COSTS
        $addonsTotal = 0;
        foreach ($selectedAddons as $addon) {
            $addonPrice = floatval($addon['price'] ?? 0);
            $addonsTotal += $addonPrice;
            $lineItems[] = [
                'type' => 'addon',
                'code' => 'ADDON_' . strtoupper(str_replace(' ', '_', $addon['name'] ?? 'ITEM')),
                'description' => "Add-on: " . ($addon['name'] ?? 'Equipment Rental'),
                'amount' => $addonPrice,
            ];
        }

        // 6. FINAL PAYABLE TOTAL
        $finalTotal = max(0, $subtotalBeforeDiscounts - $totalDiscount + $addonsTotal);

        return [
            'base_rate' => round($baseRate, 2),
            'peak_adjustment' => round($peakAdjustment, 2),
            'seasonal_adjustment' => round($seasonalAdjustment, 2),
            'subtotal_before_discounts' => round($subtotalBeforeDiscounts, 2),
            'discount_amount' => round($totalDiscount, 2),
            'addons_amount' => round($addonsTotal, 2),
            'total_amount' => round($finalTotal, 2),
            'line_items' => $lineItems,
            'price_breakdown' => [
                'base_rate' => round($baseRate, 2),
                'peak_adjustment' => round($peakAdjustment, 2),
                'seasonal_adjustment' => round($seasonalAdjustment, 2),
                'subtotal' => round($subtotalBeforeDiscounts, 2),
                'discount_amount' => round($totalDiscount, 2),
                'addons_amount' => round($addonsTotal, 2),
                'total_amount' => round($finalTotal, 2),
                'line_items' => $lineItems,
            ],
        ];
    }

    protected function matchesCourtAndCategory(PricingRule $rule, Court $court): bool
    {
        if ($rule->court_id && $rule->court_id !== $court->id) {
            return false;
        }

        if ($rule->sport_category_id && $rule->sport_category_id !== $court->sport_category_id) {
            return false;
        }

        return true;
    }
}
