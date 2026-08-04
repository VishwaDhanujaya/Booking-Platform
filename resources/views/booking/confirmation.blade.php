@php
    $tenant = \App\Services\TenantResolver::getActiveTenant();
    $tenantName = $tenant['name'];
    $tenantAddress = $tenant['address'] ?? 'Colombo, Sri Lanka';
@endphp
<x-layouts.app title="Booking Confirmed | {{ $tenantName }}">

    <!-- Confirmation Banner (VADEL Liquid Glass Aesthetic - Flush Top Alignment) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden py-12 sm:py-14 p-6 sm:p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white text-center">
            <div class="absolute inset-0 bg-linear-to-r from-slate-950 via-slate-900/90 to-slate-950 z-0"></div>
            <div class="relative z-10 max-w-3xl mx-auto space-y-5">
                
                <!-- Success Icon Badge (Liquid Glass Circle) -->
                <div class="w-16 h-16 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-400/40 flex items-center justify-center mx-auto shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)] backdrop-blur-xl">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <span class="text-xs font-black text-slate-300 uppercase tracking-widest block">{{ $tenantName }} Reservation</span>
                    <h1 class="text-3xl sm:text-5xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg">Your Court is Locked & Ready</h1>
                    <p class="text-xs sm:text-sm text-slate-300">We've dispatched your digital pass & receipt to <strong class="text-white font-extrabold">{{ $booking->customer_email }}</strong>.</p>
                </div>
                
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-2xl border border-white/20 rounded-full px-5 py-2 text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                    <span class="text-slate-300 font-medium">Booking Ref:</span>
                    <strong class="text-white font-mono font-black tracking-wider">{{ $booking->booking_reference }}</strong>
                </div>

            </div>
        </section>
    </div>

    <!-- Confirmation Details & Digital QR Pass -->
    <section class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Digital QR Pass Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-2xl space-y-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl"></div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Official Digital Access Pass</span>
                        <h2 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $booking->court ? $booking->court->name : 'Center Court' }}</h2>
                        <p class="text-xs text-slate-500 font-medium">{{ $booking->tenant->name ?? $tenantName }} &bull; {{ $booking->tenant->address ?? $tenantAddress }}</p>
                    </div>

                    <!-- Mock QR Code Box -->
                    <div class="bg-slate-900 text-white p-4 rounded-2xl border border-slate-800 text-center space-y-2 flex flex-col items-center shadow-lg">
                        <!-- Simulated QR Grid -->
                        <div class="w-24 h-24 bg-white p-2 rounded-xl grid grid-cols-4 gap-1">
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-white"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-white"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-white"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-white"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                            <div class="bg-white"></div>
                            <div class="bg-slate-950 rounded-xs"></div>
                        </div>
                        <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest">SCAN AT GATE</span>
                    </div>
                </div>

                <!-- Reservation Information Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">
                    <div class="space-y-1">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Date & Time</span>
                        <p class="text-sm font-extrabold text-slate-900">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('D, M j, Y') }}
                        </p>
                        <p class="text-slate-600 font-semibold">
                            {{ substr($booking->start_time, 0, 5) }} – {{ substr($booking->end_time, 0, 5) }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Primary Player</span>
                        <p class="text-sm font-bold text-slate-900">{{ $booking->customer_name }}</p>
                        <p class="text-slate-500">{{ $booking->customer_phone }}</p>
                    </div>

                    <div class="space-y-1">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Payment Status</span>
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> PAID (LKR {{ number_format($booking->total_amount) }})
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Included Add-ons -->
                @if($booking->addons && count($booking->addons) > 0)
                <div class="pt-6 border-t border-slate-100 space-y-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Included Add-ons & Equipment Rentals</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($booking->addons as $addon)
                            <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                {{ $addon['name'] }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Download & Calendar Sync Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="button" onclick="alert('Digital QR Pass PDF downloaded successfully!')" class="px-5 py-3 rounded-xl font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 text-xs flex items-center justify-center gap-2 w-full sm:w-auto transition-colors">
                            <svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Pass (PDF)
                        </button>
                        <button type="button" onclick="alert('.ics Calendar invite saved to your device!')" class="px-5 py-3 rounded-xl font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 text-xs flex items-center justify-center gap-2 w-full sm:w-auto transition-colors">
                            <svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Add to Calendar (.ics)
                        </button>
                    </div>

                    <a href="{{ route('customer.my-bookings') }}" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md text-xs flex items-center justify-center gap-2 w-full sm:w-auto transition-all">
                        View My Bookings Dashboard &rarr;
                    </a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
