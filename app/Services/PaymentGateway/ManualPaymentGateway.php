<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Booking;
use App\Services\CreditAndPassService;
use Exception;

class ManualPaymentGateway implements PaymentGatewayInterface
{
    protected CreditAndPassService $creditAndPassService;

    public function __construct(?CreditAndPassService $creditAndPassService = null)
    {
        $this->creditAndPassService = $creditAndPassService ?? new CreditAndPassService();
    }

    public function getGatewayName(): string
    {
        return 'Manual / Offline Gateway (Credits, Pass, Bank Transfer, Pay at Venue)';
    }

    public function charge(Booking $booking, string $tenderMethod, array $options = []): array
    {
        switch ($tenderMethod) {
            case 'credits':
                $user = $booking->user;
                if (!$user) {
                    throw new Exception("User account required for wallet credit payment.");
                }
                $this->creditAndPassService->deductCredits($user, $booking->total_amount, $booking);
                $paymentStatus = 'paid';
                $txnRef = 'TXN-CREDIT-' . $booking->id . '-' . time();
                $message = 'Paid via internal wallet credits.';
                break;

            case 'pass':
                $pass = $options['pass'] ?? null;
                if (!$pass) {
                    throw new Exception("Active customer pass required for pass redemption.");
                }
                $this->creditAndPassService->redeemPassUnit($pass, $booking);
                $paymentStatus = 'paid';
                $txnRef = 'TXN-PASS-' . $pass->id . '-' . $booking->id;
                $message = 'Paid via customer pass unit redemption.';
                break;

            case 'bank_transfer':
                $paymentStatus = 'pending';
                $txnRef = 'TXN-BANK-PENDING-' . $booking->id;
                $message = 'Bank transfer pending verification by staff.';
                break;

            case 'pay_at_venue':
            default:
                $paymentStatus = 'unpaid';
                $txnRef = 'TXN-VENUE-UNPAID-' . $booking->id;
                $message = 'Pay at venue selected. Payment due upon arrival.';
                break;
        }

        return [
            'success' => true,
            'payment_status' => $paymentStatus,
            'transaction_reference' => $txnRef,
            'message' => $message,
        ];
    }

    public function refund(Booking $booking, float $amount, string $reason = ''): array
    {
        if ($booking->payment_method === 'credits' && $booking->user) {
            $this->creditAndPassService->refundCredits($booking->user, $amount, $booking, $reason);
        }

        return [
            'success' => true,
            'payment_status' => 'refunded',
            'transaction_reference' => 'REFUND-' . $booking->id . '-' . time(),
            'message' => 'Refund processed successfully.',
        ];
    }
}
