<?php

namespace App\Contracts;

use App\Models\Booking;

interface PaymentGatewayInterface
{
    /**
     * Process payment charge for a booking.
     */
    public function charge(Booking $booking, string $tenderMethod, array $options = []): array;

    /**
     * Process refund for a booking.
     */
    public function refund(Booking $booking, float $amount, string $reason = ''): array;

    /**
     * Get human-readable gateway identifier.
     */
    public function getGatewayName(): string;
}
