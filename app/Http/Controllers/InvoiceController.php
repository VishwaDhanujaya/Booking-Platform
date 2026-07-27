<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function show(string $reference, InvoiceService $invoiceService)
    {
        $booking = Booking::where('booking_reference', '=', $reference, 'and')
            ->with(['court.sportCategory', 'tenant'])
            ->firstOrFail();

        $invoice = $booking->invoice ?? $invoiceService->generateInvoice($booking);

        return view('booking.invoice', compact('booking', 'invoice'));
    }

    public function showAdmin(int $id, InvoiceService $invoiceService)
    {
        $booking = Booking::with(['court.sportCategory', 'tenant'])->findOrFail($id);
        $invoice = $booking->invoice ?? $invoiceService->generateInvoice($booking);

        return view('booking.invoice', compact('booking', 'invoice'));
    }
}
