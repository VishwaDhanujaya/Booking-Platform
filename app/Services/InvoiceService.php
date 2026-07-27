<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Generate or fetch invoice record when booking is marked paid.
     */
    public function generateInvoice(Booking $booking): Invoice
    {
        return DB::transaction(function () use ($booking) {
            $existing = Invoice::where('booking_id', '=', $booking->id, 'and')->first(['*']);
            if ($existing) {
                return $existing;
            }

            $invNumber = 'INV-2026-' . rand(10000, 99999);
            $paidAt = $booking->paid_at ?? Carbon::now();

            $subtotal = ($booking->base_amount ?? 0) + ($booking->addons_amount ?? 0);

            $invoice = Invoice::create([
                'tenant_id' => $booking->tenant_id,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'invoice_number' => $invNumber,
                'invoice_date' => Carbon::today()->toDateString(),
                'paid_at' => $paidAt,
                'payment_method' => $booking->payment_method ?? 'pay_at_venue',
                'subtotal_amount' => $subtotal,
                'discount_amount' => $booking->discount_amount ?? 0,
                'tax_amount' => $booking->tax_amount ?? 0,
                'total_amount' => $booking->total_amount,
                'price_breakdown' => $booking->price_breakdown,
                'status' => 'paid',
            ]);

            Log::info("==========================================");
            Log::info("[INVOICE READY NOTIFICATION]");
            Log::info("Invoice Number: {$invoice->invoice_number}");
            Log::info("Booking Reference: {$booking->booking_reference}");
            Log::info("Customer: {$booking->customer_name} ({$booking->customer_email})");
            Log::info("Total Paid: LKR " . number_format($invoice->total_amount, 2));
            Log::info("Payment Method: " . strtoupper($invoice->payment_method));
            Log::info("==========================================");

            return $invoice;
        });
    }
}
