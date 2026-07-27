<?php

namespace App\Services;

use App\Models\User;
use App\Models\Booking;
use App\Models\CreditLedger;
use App\Models\CustomerPass;
use App\Models\PassLedgerEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class CreditAndPassService
{
    /**
     * Manually issue or top up wallet credits for a customer.
     */
    public function issueCredits(User $user, float $amount, string $reason, ?User $issuedBy = null): CreditLedger
    {
        if ($amount <= 0) {
            throw new Exception("Credit adjustment amount must be greater than zero.");
        }

        return DB::transaction(function () use ($user, $amount, $reason, $issuedBy) {
            $currentBalance = $user->credit_balance;
            $newBalance = $currentBalance + $amount;

            return CreditLedger::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'booking_id' => null,
                'amount_in' => $amount,
                'amount_out' => 0.00,
                'balance_after' => $newBalance,
                'reason' => $reason,
                'reference_type' => $issuedBy ? 'manual_issue' : 'topup',
                'created_by' => $issuedBy?->id,
            ]);
        });
    }

    /**
     * Deduct credits for booking tender payment.
     */
    public function deductCredits(User $user, float $amount, Booking $booking, string $reason = 'Court booking payment'): CreditLedger
    {
        if ($amount <= 0) {
            throw new Exception("Deduction amount must be greater than zero.");
        }

        return DB::transaction(function () use ($user, $amount, $booking, $reason) {
            $currentBalance = $user->credit_balance;
            if ($currentBalance < $amount) {
                throw new Exception("Insufficient credit balance. Available: LKR " . number_format($currentBalance, 2) . ", Required: LKR " . number_format($amount, 2));
            }

            $newBalance = $currentBalance - $amount;

            return CreditLedger::create([
                'tenant_id' => $booking->tenant_id,
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'amount_in' => 0.00,
                'amount_out' => $amount,
                'balance_after' => $newBalance,
                'reason' => $reason . " ({$booking->booking_reference})",
                'reference_type' => 'booking',
            ]);
        });
    }

    /**
     * Refund credits for eligible booking cancellation.
     */
    public function refundCredits(User $user, float $amount, Booking $booking, string $reason = 'Booking cancellation refund'): CreditLedger
    {
        return DB::transaction(function () use ($user, $amount, $booking, $reason) {
            $currentBalance = $user->credit_balance;
            $newBalance = $currentBalance + $amount;

            return CreditLedger::create([
                'tenant_id' => $booking->tenant_id,
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'amount_in' => $amount,
                'amount_out' => 0.00,
                'balance_after' => $newBalance,
                'reason' => $reason . " ({$booking->booking_reference})",
                'reference_type' => 'refund',
            ]);
        });
    }

    /**
     * Manually issue a new multi-session customer pass.
     */
    public function issuePass(
        User $user,
        string $passName,
        int $totalUnits,
        float $pricePaid,
        Carbon $expiresAt
    ): CustomerPass {
        if ($totalUnits <= 0) {
            throw new Exception("Pass total units must be at least 1.");
        }

        return DB::transaction(function () use ($user, $passName, $totalUnits, $pricePaid, $expiresAt) {
            $pass = CustomerPass::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'pass_name' => $passName,
                'total_units' => $totalUnits,
                'remaining_units' => $totalUnits,
                'price_paid' => $pricePaid,
                'expires_at' => $expiresAt,
                'status' => 'active',
            ]);

            PassLedgerEntry::create([
                'customer_pass_id' => $pass->id,
                'booking_id' => null,
                'units_in' => $totalUnits,
                'units_out' => 0,
                'units_after' => $totalUnits,
                'reason' => "Pass issued: {$passName} ({$totalUnits} units)",
            ]);

            return $pass;
        });
    }

    /**
     * Redeem 1 unit from a customer pass for a booking.
     */
    public function redeemPassUnit(CustomerPass $pass, Booking $booking): PassLedgerEntry
    {
        return DB::transaction(function () use ($pass, $booking) {
            if ($pass->remaining_units <= 0 || $pass->status !== 'active') {
                throw new Exception("This pass is no longer active or has no remaining session units.");
            }

            if ($pass->expires_at && Carbon::parse($pass->expires_at)->isPast()) {
                $pass->update(['status' => 'expired']);
                throw new Exception("This customer pass expired on " . Carbon::parse($pass->expires_at)->format('Y-m-d'));
            }

            $pass->decrement('remaining_units');
            if ($pass->remaining_units <= 0) {
                $pass->update(['status' => 'exhausted']);
            }

            return PassLedgerEntry::create([
                'customer_pass_id' => $pass->id,
                'booking_id' => $booking->id,
                'units_in' => 0,
                'units_out' => 1,
                'units_after' => $pass->remaining_units,
                'reason' => "Redeemed 1 session unit for booking {$booking->booking_reference}",
            ]);
        });
    }

    /**
     * Restore 1 pass unit on eligible cancellation.
     */
    public function restorePassUnit(CustomerPass $pass, Booking $booking, string $reason = 'Restored due to booking cancellation'): PassLedgerEntry
    {
        return DB::transaction(function () use ($pass, $booking, $reason) {
            $pass->increment('remaining_units');
            if ($pass->status === 'exhausted') {
                $pass->update(['status' => 'active']);
            }

            return PassLedgerEntry::create([
                'customer_pass_id' => $pass->id,
                'booking_id' => $booking->id,
                'units_in' => 1,
                'units_out' => 0,
                'units_after' => $pass->remaining_units,
                'reason' => $reason . " ({$booking->booking_reference})",
            ]);
        });
    }
}
