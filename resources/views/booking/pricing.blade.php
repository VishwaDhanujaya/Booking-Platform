@php
    $tenantName = $tenant['name'];
    $brandColor = $tenant['brand_color'] ?? '#0056A2';
@endphp
<x-layouts.app title="Pricing & Court Rates | {{ $tenantName }}">

    <!-- Hero Header (VADEL Liquid Glass Aesthetic - Flush Top Alignment) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden py-12 sm:py-16 p-6 sm:p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white text-center">
            <div class="absolute inset-0 bg-linear-to-r from-slate-950 via-slate-900/90 to-slate-950 z-0"></div>
            <div class="relative z-10 max-w-4xl mx-auto space-y-4">
                <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest text-white shadow-md border border-white/20 backdrop-blur-xl" style="background-color: {{ $brandColor }};">
                    {{ $tenantName }} Member Rates
                </span>
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg leading-tight">
                    Court Rates & Member Pricing
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl mx-auto leading-relaxed font-normal">
                    Transparent hourly court reservation rates, peak window schedules, and multi-session pass packages for {{ $tenantName }}.
                </p>
            </div>
        </section>
    </div>

    <!-- Main Pricing Content -->
    <section class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section 1: Court Hourly Rates Matrix -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-2xl font-black italic tracking-tighter text-slate-950 uppercase">Court Hourly Rates</h2>
                        <p class="text-xs text-slate-500 font-medium">Peak vs Off-peak hourly rates across all active sports courts.</p>
                    </div>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-full text-xs font-black text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_20px_-5px_rgba(0,0,0,0.3)] transition-all hover:scale-105 active:scale-[0.98]" style="background-color: {{ $brandColor }};">
                        Reserve Court Slot &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courts as $court)
                        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-7 shadow-xl shadow-slate-200/50 space-y-4 flex flex-col justify-between hover:scale-[1.01] transition-transform">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full bg-slate-950 text-white text-[9px] font-black uppercase tracking-wider shadow-xs">
                                        {{ $court->sportCategory ? $court->sportCategory->name : 'Court' }}
                                    </span>
                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full {{ $court->type === 'indoor' ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                        {{ ucfirst($court->type) }}
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-xl font-black tracking-tight text-slate-950 leading-tight">{{ $court->name }}</h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1">{{ $court->surface_type }}</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-bold">Off-Peak Rate:</span>
                                    <strong class="text-slate-950 font-black text-base">LKR {{ number_format($court->hourly_rate) }}<span class="text-[10px] text-slate-400 font-normal">/hr</span></strong>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-amber-700 font-black">Peak Rate (16:00+):</span>
                                    <strong class="text-amber-700 font-black text-base">LKR {{ number_format($court->peak_hourly_rate) }}<span class="text-[10px] text-amber-700/70 font-normal">/hr</span></strong>
                                </div>

                                <a href="{{ route('booking.index', ['court_id' => $court->id]) }}" class="block w-full text-center py-3 rounded-full font-black text-xs bg-slate-100 hover:bg-slate-200 text-slate-950 transition-colors">
                                    Book This Court
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 2: Multi-Visit Member Passes & Bundles -->
            <div class="bg-white rounded-4xl border border-slate-200/90 p-8 sm:p-10 shadow-xl shadow-slate-200/50 space-y-8">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-2xl font-black italic tracking-tighter text-slate-950 uppercase">Multi-Visit Pass Packages & Wallet Credits</h2>
                    <p class="text-xs text-slate-500 font-medium mt-1">Pre-purchase session units or wallet credit top-ups for bulk booking discounts at {{ $tenantName }}.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                        <span class="px-3 py-1 rounded-full bg-slate-950 text-white font-black text-[9px] uppercase tracking-wider">10-Session Pass</span>
                        <div>
                            <h3 class="text-2xl font-black text-slate-950 tracking-tight">10 Sessions</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Save up to 20% on regular court rates. Valid for 60 days.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-2xl font-black text-slate-950">LKR 25,000</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white border border-slate-800 space-y-4 relative shadow-xl">
                        <span class="absolute -top-3 right-6 bg-white text-slate-950 text-[9px] font-black uppercase px-3 py-1 rounded-full shadow-md">Best Value</span>
                        <span class="px-3 py-1 rounded-full bg-white/20 text-white font-black text-[9px] uppercase tracking-wider border border-white/25">20-Session Pass</span>
                        <div>
                            <h3 class="text-2xl font-black text-white tracking-tight">20 Sessions</h3>
                            <p class="text-xs text-slate-300 font-normal mt-1">Maximum savings for frequent players & teams. Valid for 120 days.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-2xl font-black text-white">LKR 45,000</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-black text-[9px] uppercase tracking-wider border border-emerald-200">Goodwill Wallet Credits</span>
                        <div>
                            <h3 class="text-2xl font-black text-slate-950 tracking-tight">Credit Top-ups</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Instant digital wallet credits applied at checkout.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-xs font-black text-emerald-700">Issued at Front Desk or Admin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Booking Policies & Hours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xl shadow-slate-200/50 space-y-3">
                    <h3 class="text-lg font-black text-slate-950 tracking-tight">Peak Window Hours</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        Peak rates apply from <strong>4:00 PM to 10:00 PM</strong> on weekdays, and all day on Saturdays and Sundays. Off-peak rates apply for morning and afternoon weekday sessions.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/90 p-8 shadow-xl shadow-slate-200/50 space-y-3">
                    <h3 class="text-lg font-black text-slate-950 tracking-tight">Cancellation & Refund Policy</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium">
                        Free cancellations up to <strong>24 hours prior</strong> to court start time with automatic credit refund to your player account wallet.
                    </p>
                </div>
            </div>

            <!-- CTA Banner (VADEL Dark Liquid Glass Banner) -->
            <div class="bg-slate-950 rounded-4xl p-10 sm:p-12 text-center space-y-6 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3),0_25px_50px_rgba(0,0,0,0.7)] relative overflow-hidden">
                <div class="relative z-10 space-y-3">
                    <h2 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-md">Ready to Play at {{ $tenantName }}?</h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto font-normal">Select open hourly slots on our real-time court reservation grid.</p>
                </div>
                <div class="relative z-10 pt-2">
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all hover:scale-105 active:scale-[0.98]">
                        Reserve Your Court Slot &rarr;
                    </a>
                </div>
            </div>

        </div>
    </section>

</x-layouts.app>
