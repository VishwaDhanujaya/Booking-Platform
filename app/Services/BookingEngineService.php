<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\TimeSlot;
use App\Models\Waitlist;
use App\Models\NoShowRecord;
use App\Models\User;
use App\Models\CreditLedger;
use App\Models\CustomerPass;
use App\Models\PassLedgerEntry;
use App\Services\PricingEngineService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingEngineService
{
    protected PricingEngineService $pricingEngine;

    public function __construct(?PricingEngineService $pricingEngine = null)
    {
        $this->pricingEngine = $pricingEngine ?? new PricingEngineService();
    }

    /**
     * Create a new booking with concurrency safety, dynamic pricing engine, and limit checks.
     */
    public function createBooking(
        User $user,
        Court $court,
        string $date,
        string $startTime,
        string $endTime,
        array $addons = [],
        string $paymentMethod = 'pay_at_venue',
        ?CustomerPass $pass = null
    ): Booking {
        // 1. Check if user is banned
        if ($user->is_banned) {
            throw new Exception("Account is suspended: " . ($user->ban_reason ?? "Violation of facility policies."));
        }

        // 2. Check Customer Booking Limits (Max 2 bookings/day, Max 6 bookings/week)
        $this->enforceBookingLimits($user, $date);

        // 3. Perform Concurrency Safe Atomic Booking Creation
        return DB::transaction(function () use ($user, $court, $date, $startTime, $endTime, $addons, $paymentMethod, $pass) {
            // Lock target slots for update
            $slots = TimeSlot::where('court_id', $court->id)
                ->where('date', $date)
                ->where('start_time', '>=', $startTime)
                ->where('end_time', '<=', $endTime)
                ->lockForUpdate()
                ->get();

            // Check existing confirmed bookings overlapping this slot range
            $existingBookingsCount = Booking::where('court_id', $court->id)
                ->where('booking_date', $date)
                ->whereIn('status', ['confirmed', 'pending', 'payment_pending'])
                ->where(function ($q) use ($startTime, $endTime) {
                    $q->where(function ($sub) use ($startTime, $endTime) {
                        $sub->where('start_time', '<', $endTime)
                            ->where('end_time', '>', $startTime);
                    });
                })
                ->lockForUpdate()
                ->count();

            if ($existingBookingsCount >= $court->max_capacity) {
                throw new Exception("Slot is already booked for this court and time range. You may join the waitlist instead.");
            }

            // Compute dynamic pricing via PricingEngineService
            $slotCount = max(1, $slots->count());
            $priceCalculation = $this->pricingEngine->calculatePrice(
                $court,
                $date,
                $startTime,
                $endTime,
                $user,
                $addons,
                $slotCount
            );

            $baseCourtPrice = $priceCalculation['base_rate'];
            $addonsTotal = $priceCalculation['addons_amount'];
            $discountAmount = $priceCalculation['discount_amount'];
            $totalAmount = $priceCalculation['total_amount'];

            // Handle Payment Method Logic
            $paymentStatus = 'unpaid';

            if ($paymentMethod === 'credits') {
                if ($user->credit_balance < $totalAmount) {
                    throw new Exception("Insufficient wallet credit balance. Required: LKR " . number_format($totalAmount, 2));
                }
                $paymentStatus = 'paid';
            } elseif ($paymentMethod === 'pass') {
                if (!$pass || $pass->remaining_units < 1) {
                    throw new Exception("No remaining units on selected customer pass.");
                }
                $discountAmount = $totalAmount;
                $totalAmount = 0;
                $paymentStatus = 'paid';
            } elseif ($paymentMethod === 'bank_transfer') {
                $paymentStatus = 'payment_pending';
            }

            $refNumber = strtoupper(substr($court->tenant->slug ?? 'CCC', 0, 3)) . '-2026-' . rand(10000, 99999);

            $booking = Booking::create([
                'tenant_id' => $court->tenant_id,
                'court_id' => $court->id,
                'user_id' => $user->id,
                'booking_reference' => $refNumber,
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'status' => ($paymentMethod === 'bank_transfer') ? 'payment_pending' : 'confirmed',
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'base_amount' => $baseCourtPrice,
                'addons_amount' => $addonsTotal,
                'discount_amount' => $discountAmount,
                'total_amount' => max(0, $totalAmount),
                'addons' => $addons,
                'price_breakdown' => $priceCalculation['price_breakdown'],
            ]);

            // Deduct Credits if applicable
            if ($paymentMethod === 'credits') {
                CreditLedger::create([
                    'tenant_id' => $court->tenant_id,
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'amount_out' => max(0, $totalAmount),
                    'balance_after' => $user->credit_balance - max(0, $totalAmount),
                    'reason' => "Payment for court booking {$booking->booking_reference}",
                    'reference_type' => 'booking',
                ]);
            }

            // Deduct Pass Unit if applicable
            if ($paymentMethod === 'pass' && $pass) {
                $pass->decrement('remaining_units');
                if ($pass->remaining_units <= 0) {
                    $pass->update(['status' => 'exhausted']);
                }

                PassLedgerEntry::create([
                    'customer_pass_id' => $pass->id,
                    'booking_id' => $booking->id,
                    'units_out' => 1,
                    'units_after' => $pass->remaining_units,
                    'reason' => "Redeemed 1 session unit for booking {$booking->booking_reference}",
                ]);
            }

            // Mark time slots as booked
            foreach ($slots as $s) {
                $s->status = 'booked';
                $s->booked_by_name = $user->name;
                $s->save();
            }

            return $booking;
        });
    }

    /**
     * Enforce per-customer per-day and per-week booking limits.
     */
    protected function enforceBookingLimits(User $user, string $date): void
    {
        $targetDate = Carbon::parse($date);

        // Daily Limit: Max 2 bookings/day
        $dailyCount = Booking::where('user_id', $user->id)
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed', 'pending', 'payment_pending'])
            ->count();

        if ($dailyCount >= 2) {
            throw new Exception("Booking limit exceeded: You can only make a maximum of 2 court bookings per day.");
        }

        // Weekly Limit: Max 6 bookings/week
        $weekStart = $targetDate->copy()->startOfWeek()->toDateString();
        $weekEnd = $targetDate->copy()->endOfWeek()->toDateString();

        $weeklyCount = Booking::where('user_id', $user->id)
            ->whereBetween('booking_date', [$weekStart, $weekEnd])
            ->whereIn('status', ['confirmed', 'pending', 'payment_pending'])
            ->count();

        if ($weeklyCount >= 6) {
            throw new Exception("Booking limit exceeded: You can only make a maximum of 6 court bookings per week.");
        }
    }

    /**
     * Cancel booking with deadline policy enforcement and waitlist auto-promotion.
     */
    public function cancelBooking(Booking $booking, string $reason = 'Cancelled by user'): void
    {
        DB::transaction(function () use ($booking, $reason) {
            if ($booking->status === 'cancelled') {
                return;
            }

            $bookingStart = Carbon::parse($booking->booking_date . ' ' . $booking->start_time);
            $hoursUntilBooking = Carbon::now()->diffInHours($bookingStart, false);

            $isEligibleForRefund = ($hoursUntilBooking >= 24);

            $booking->status = 'cancelled';
            $booking->cancellation_reason = $reason . ($isEligibleForRefund ? " (Full Refund Eligible)" : " (Late Cancellation - Non-Refundable)");
            $booking->cancelled_at = Carbon::now();

            if ($isEligibleForRefund && $booking->payment_status === 'paid') {
                if ($booking->payment_method === 'credits' && $booking->user_id) {
                    CreditLedger::create([
                        'tenant_id' => $booking->tenant_id,
                        'user_id' => $booking->user_id,
                        'booking_id' => $booking->id,
                        'amount_in' => $booking->total_amount,
                        'balance_after' => $booking->user->credit_balance + $booking->total_amount,
                        'reason' => "Refund for cancelled booking {$booking->booking_reference}",
                        'reference_type' => 'refund',
                    ]);
                    $booking->payment_status = 'refunded';
                }
            }

            $booking->save();

            // Free up time slots
            TimeSlot::where('court_id', $booking->court_id)
                ->where('date', $booking->booking_date)
                ->where('start_time', '>=', $booking->start_time)
                ->where('end_time', '<=', $booking->end_time)
                ->update(['status' => 'available', 'booked_by_name' => null]);

            // Auto-promote waitlisted customer
            $this->promoteWaitlistedCustomer($booking->court_id, $booking->booking_date, $booking->start_time, $booking->end_time);
        });
    }

    /**
     * Promote waitlisted customer when a slot opens up.
     */
    protected function promoteWaitlistedCustomer(int $courtId, string $date, string $startTime, string $endTime): void
    {
        $nextInLine = Waitlist::where('court_id', $courtId)
            ->where('date', $date)
            ->where('start_time', $startTime)
            ->where('status', 'waiting')
            ->orderBy('position', 'asc')
            ->first();

        if ($nextInLine) {
            $nextInLine->status = 'notified';
            $nextInLine->save();

            Log::info("==========================================");
            Log::info("[WAITLIST PROMOTION NOTIFICATION]");
            Log::info("Customer: {$nextInLine->user->name} ({$nextInLine->user->email})");
            Log::info("Court ID: {$courtId} | Date: {$date} | Slot: {$startTime} - {$endTime}");
            Log::info("Message: A court slot has opened up! You have been promoted from the waitlist.");
            Log::info("==========================================");
        }
    }

    /**
     * Mark booking as no-show and auto-ban customer if threshold is crossed.
     */
    public function markNoShow(Booking $booking, User $loggedBy, ?string $notes = null): NoShowRecord
    {
        return DB::transaction(function () use ($booking, $loggedBy, $notes) {
            $booking->status = 'no_show';
            $booking->save();

            $record = NoShowRecord::create([
                'tenant_id' => $booking->tenant_id,
                'user_id' => $booking->user_id ?? $loggedBy->id,
                'booking_id' => $booking->id,
                'logged_by' => $loggedBy->id,
                'notes' => $notes ?? 'Customer failed to attend scheduled court session.',
                'occurred_at' => Carbon::now(),
            ]);

            // Check auto-ban threshold (3 no-shows within 30 days)
            if ($booking->user_id) {
                $customer = User::find($booking->user_id);
                if ($customer && !$customer->is_banned) {
                    $recentNoShows = NoShowRecord::where('user_id', $customer->id)
                        ->where('occurred_at', '>=', Carbon::now()->subDays(30))
                        ->count();

                    if ($recentNoShows >= 3) {
                        $customer->update([
                            'is_banned' => true,
                            'banned_until' => Carbon::now()->addDays(30),
                            'ban_reason' => "Account automatically suspended due to {$recentNoShows} unexcused no-shows within 30 days.",
                        ]);

                        Log::warning("[AUTO-BAN TRIGGERED] Customer {$customer->name} ({$customer->email}) has been automatically suspended for 30 days due to {$recentNoShows} no-shows.");
                    }
                }
            }

            return $record;
        });
    }

    /**
     * Join waitlist for a full slot.
     */
    public function joinWaitlist(User $user, Court $court, string $date, string $startTime, string $endTime): Waitlist
    {
        $lastPosition = Waitlist::where('court_id', $court->id)
            ->where('date', $date)
            ->where('start_time', $startTime)
            ->max('position') ?? 0;

        return Waitlist::create([
            'tenant_id' => $court->tenant_id,
            'court_id' => $court->id,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'user_id' => $user->id,
            'position' => $lastPosition + 1,
            'status' => 'waiting',
        ]);
    }
}
