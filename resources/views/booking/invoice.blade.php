<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }} | {{ $booking->tenant->name ?? 'Colombo Courts Club' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: black !important; }
            .print-card { border: none !important; shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800 p-4 sm:p-8">

    <!-- Top Action Bar (Hidden when printing) -->
    <div class="max-w-4xl mx-auto mb-6 no-print flex items-center justify-between gap-4">
        <a href="{{ auth()->check() && in_array(auth()->user()->role, ['owner','manager','trainer_staff','front_desk']) ? route('admin.bookings') : route('customer.my-bookings') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-xs">
            &larr; Return to Dashboard
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Invoice / Download PDF
            </button>
        </div>
    </div>

    <!-- Invoice Document Container -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl border border-slate-200/90 shadow-xl p-8 sm:p-12 print-card space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-b border-slate-100 pb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-black text-xl flex items-center justify-center tracking-tighter shadow-md">
                        {{ strtoupper(substr($booking->tenant->name ?? 'V', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $booking->tenant->name ?? 'Venue Operations' }}</h1>
                        <p class="text-xs text-slate-500 font-medium">Premier Sports Facility & Venue</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 leading-relaxed mt-3">
                    123 Sports Complex Way, Maitland Crescent<br>
                    Colombo 00700, Sri Lanka &bull; +94 11 269 8888<br>
                    support@colombocourts.lk &bull; www.colombocourts.lk
                </p>
            </div>

            <div class="text-left sm:text-right space-y-1">
                <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold tracking-wider uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                    TAX INVOICE & RECEIPT &bull; {{ strtoupper($invoice->status) }}
                </span>
                <h2 class="text-2xl font-black font-mono text-slate-900 pt-1">{{ $invoice->invoice_number }}</h2>
                <p class="text-xs text-slate-500">Date: <strong class="text-slate-800 font-bold">{{ $invoice->invoice_date->format('F d, Y') }}</strong></p>
                <p class="text-xs text-slate-500">Paid At: <strong class="text-slate-800 font-bold">{{ $invoice->paid_at ? $invoice->paid_at->format('Y-m-d H:i:s') : 'Pending Settlement' }}</strong></p>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-2xl border border-slate-100">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-1">Customer / Billed To</span>
                <h3 class="text-sm font-extrabold text-slate-900">{{ $booking->customer_name }}</h3>
                <p class="text-xs text-slate-600 font-medium">{{ $booking->customer_email }}</p>
                <p class="text-xs text-slate-600 font-medium">{{ $booking->customer_phone ?? 'Phone on record' }}</p>
            </div>

            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block mb-1">Reservation Details</span>
                <h3 class="text-sm font-extrabold text-slate-900">{{ $booking->court ? $booking->court->name : 'Court Resource' }}</h3>
                <p class="text-xs text-slate-600 font-medium font-mono">Ref: {{ $booking->booking_reference }}</p>
                <p class="text-xs text-slate-600 font-medium">Session: {{ $booking->booking_date }} &bull; {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</p>
            </div>
        </div>

        <!-- Itemized Financial Breakdown Table -->
        <div class="space-y-3">
            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Itemized Fee Breakdown</h3>
            
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 uppercase tracking-wider font-extrabold text-[10px]">
                        <th class="py-3">Description</th>
                        <th class="py-3 text-right">Rate / Charge</th>
                        <th class="py-3 text-right">Amount (LKR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @if(isset($invoice->price_breakdown['line_items']) && count($invoice->price_breakdown['line_items']) > 0)
                        @foreach($invoice->price_breakdown['line_items'] as $item)
                            <tr>
                                <td class="py-3 font-semibold text-slate-900">{{ $item['name'] }}</td>
                                <td class="py-3 text-right text-slate-500">LKR {{ number_format($item['amount'], 2) }}</td>
                                <td class="py-3 text-right font-bold text-slate-900">LKR {{ number_format($item['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="py-3 font-semibold text-slate-900">Court Session Reservation ({{ $booking->court ? $booking->court->name : 'Court' }})</td>
                            <td class="py-3 text-right text-slate-500">LKR {{ number_format($booking->base_amount, 2) }}</td>
                            <td class="py-3 text-right font-bold text-slate-900">LKR {{ number_format($booking->base_amount, 2) }}</td>
                        </tr>
                        @if($booking->addons_amount > 0)
                        <tr>
                            <td class="py-3 font-semibold text-slate-900">Selected Equipment Add-ons</td>
                            <td class="py-3 text-right text-slate-500">LKR {{ number_format($booking->addons_amount, 2) }}</td>
                            <td class="py-3 text-right font-bold text-slate-900">LKR {{ number_format($booking->addons_amount, 2) }}</td>
                        </tr>
                        @endif
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Totals Summary Card -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 pt-4 border-t border-slate-200">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Tender & Payment Method</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-900 text-white text-xs font-bold">
                    Tender: {{ strtoupper(str_replace('_', ' ', $invoice->payment_method)) }}
                </span>
                <p class="text-[11px] text-slate-500">Thank you for booking with {{ $booking->tenant->name ?? 'Colombo Courts Club' }}.</p>
            </div>

            <div class="w-full sm:w-64 space-y-2 text-xs bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-bold">LKR {{ number_format($invoice->subtotal_amount, 2) }}</span>
                </div>

                @if($invoice->discount_amount > 0)
                <div class="flex justify-between text-emerald-700">
                    <span>Discount Applied:</span>
                    <span class="font-bold">- LKR {{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
                @endif

                <div class="flex justify-between text-slate-600">
                    <span>Tax (0% Standard):</span>
                    <span class="font-bold">LKR 0.00</span>
                </div>

                <div class="flex justify-between text-slate-900 font-extrabold text-base pt-2 border-t border-slate-200">
                    <span>Total Paid:</span>
                    <span class="font-mono text-emerald-700">LKR {{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="pt-8 border-t border-slate-100 text-center space-y-1">
            <p class="text-xs font-bold text-slate-700">Official Computer-Generated Tax Invoice & Receipt</p>
            <p class="text-[11px] text-slate-400">{{ $booking->tenant->name ?? 'Venue Facility' }} &bull; Gate Pass Reference: {{ $booking->booking_reference }}</p>
        </div>

    </div>

</body>
</html>
