@php
    $tenantName = $tenant['name'];
    $brandColor = $tenant['brand_color'] ?? '#0056A2';
@endphp
<x-layouts.app title="Pricing & Court Rates | {{ $tenantName }}">

    <!-- Hero Header -->
    <section class="bg-slate-900 text-white py-16 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider text-white shadow-xs" style="background-color: {{ $brandColor }};">
                {{ $tenantName }} Member Rates
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Court Rates & Membership Pricing</h1>
            <p class="text-xs sm:text-sm text-slate-400 max-w-2xl mx-auto leading-relaxed">
                Transparent hourly court reservation rates, peak window schedules, and multi-session pass packages for {{ $tenantName }}.
            </p>
        </div>
    </section>

    <!-- Main Pricing Content -->
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section 1: Court Hourly Rates Matrix -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">Court Hourly Rates</h2>
                        <p class="text-xs text-slate-500">Peak vs Off-peak hourly rates across all active sports courts.</p>
                    </div>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-xs font-extrabold text-white shadow-md transition-all hover:scale-[1.01]" style="background-color: {{ $brandColor }};">
                        Reserve Court Slot &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($courts as $court)
                        <div class="bg-white rounded-3xl border-2 border-slate-200/80 p-6 shadow-xl space-y-4 flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-wider">
                                        {{ $court->sportCategory ? $court->sportCategory->name : 'Court' }}
                                    </span>
                                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $court->type === 'indoor' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ ucfirst($court->type) }}
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-lg font-black text-slate-900 leading-tight">{{ $court->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-1">{{ $court->surface_type }}</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-semibold">Off-Peak Rate:</span>
                                    <strong class="text-slate-900 font-black text-sm">LKR {{ number_format($court->hourly_rate) }}<span class="text-[10px] text-slate-400 font-normal">/hr</span></strong>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-amber-700 font-bold">Peak Rate (5pm-10pm):</span>
                                    <strong class="text-amber-600 font-black text-sm">LKR {{ number_format($court->peak_hourly_rate) }}<span class="text-[10px] text-amber-700/70 font-normal">/hr</span></strong>
                                </div>

                                <a href="{{ route('booking.index', ['court_id' => $court->id]) }}" class="block w-full text-center py-2.5 rounded-xl font-bold text-xs bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                                    Book This Court
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 2: Multi-Visit Member Passes & Bundles -->
            <div class="bg-white rounded-3xl border-2 border-slate-200/80 p-8 shadow-xl space-y-8">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Multi-Visit Pass Packages & Wallet Credits</h2>
                    <p class="text-xs text-slate-500 mt-1">Pre-purchase session units or wallet credit top-ups for bulk booking discounts at {{ $tenantName }}.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <span class="px-2.5 py-1 rounded-md bg-blue-100 text-sltds-endeavour font-black text-[10px] uppercase tracking-wider">10-Session Pass</span>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">10 Sessions</h3>
                            <p class="text-xs text-slate-500 mt-1">Save up to 20% on regular court rates. Valid for 60 days.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-xl font-black text-slate-900">LKR 25,000</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-blue-50/70 border-2 border-blue-200 space-y-4 relative">
                        <span class="absolute -top-2.5 right-4 bg-sltds-endeavour text-white text-[9px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-xs">Best Value</span>
                        <span class="px-2.5 py-1 rounded-md bg-blue-200 text-sltds-endeavour font-black text-[10px] uppercase tracking-wider">20-Session Pass</span>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">20 Sessions</h3>
                            <p class="text-xs text-slate-600 mt-1">Maximum savings for frequent players & teams. Valid for 120 days.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-xl font-black text-slate-900">LKR 45,000</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
                        <span class="px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-800 font-black text-[10px] uppercase tracking-wider">Goodwill Wallet Credits</span>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900">Credit Top-ups</h3>
                            <p class="text-xs text-slate-500 mt-1">Instant digital wallet credits applied at checkout.</p>
                        </div>
                        <div class="pt-2">
                            <span class="text-xs font-extrabold text-emerald-700">Issued at Front Desk or Admin</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Booking Policies & Hours -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border-2 border-slate-200/80 p-8 shadow-xl space-y-3">
                    <h3 class="text-base font-black text-slate-900">Peak Window Hours</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Peak rates apply from <strong>5:00 PM to 10:00 PM</strong> on weekdays, and all day on Saturdays and Sundays. Off-peak rates apply for morning and afternoon weekday sessions.
                    </p>
                </div>

                <div class="bg-white rounded-3xl border-2 border-slate-200/80 p-8 shadow-xl space-y-3">
                    <h3 class="text-base font-black text-slate-900">Cancellation & Refund Policy</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Free cancellations up to <strong>24 hours prior</strong> to court start time with automatic credit refund to your player account wallet.
                    </p>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="bg-slate-900 rounded-3xl p-8 text-center space-y-4 text-white">
                <h2 class="text-2xl font-black tracking-tight">Ready to Play at {{ $tenantName }}?</h2>
                <p class="text-xs text-slate-400 max-w-md mx-auto">Select open hourly slots on our real-time court reservation grid.</p>
                <div>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-xs font-black text-white shadow-lg transition-all hover:scale-[1.02]" style="background-color: {{ $brandColor }};">
                        Reserve Your Court Slot &rarr;
                    </a>
                </div>
            </div>

        </div>
    </section>

</x-layouts.app>
