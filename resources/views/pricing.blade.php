@php
    $t = \App\Services\TenantResolver::getActiveTenant();
@endphp
<x-layouts.app title="Pricing & Court Rates | {{ $t['name'] }}">

    <!-- Pricing Header -->
    <section class="bg-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-500/20 text-brand-300 border border-brand-500/30">
                Transparent Rates
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Court Rates & Memberships</h1>
            <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto">
                No hidden fees. All prices include access to court facilities, changing rooms, and baseline lighting.
            </p>
        </div>
    </section>

    <!-- Hourly Rates Grid -->
    <section class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-8 text-center sm:text-left">Standard Hourly Court Rates</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Tennis -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover transition-all space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="1.8" d="M5.636 5.636a9 9 0 0112.728 0M5.636 18.364a9 9 0 0012.728 0"/></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">Tennis</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Tennis Courts</h3>
                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Off-Peak (6AM - 4PM):</span>
                            <strong class="text-slate-900">LKR 3,500/hr</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Peak (4PM - 10PM):</span>
                            <strong class="text-brand-600">LKR 4,500/hr</strong>
                        </div>
                    </div>
                    <a href="{{ route('booking.index') }}?sport=tennis" class="block w-full text-center py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-xs transition-colors">
                        Book Tennis Court &rarr;
                    </a>
                </div>

                <!-- Padel -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover transition-all space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="7" y="3" width="10" height="12" rx="5" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 15v6M10 21h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">Padel</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Padel Arenas</h3>
                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Off-Peak (6AM - 4PM):</span>
                            <strong class="text-slate-900">LKR 4,500/hr</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Peak (4PM - 10PM):</span>
                            <strong class="text-brand-600">LKR 5,800/hr</strong>
                        </div>
                    </div>
                    <a href="{{ route('booking.index') }}?sport=padel" class="block w-full text-center py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-xs transition-colors">
                        Book Padel Arena &rarr;
                    </a>
                </div>

                <!-- Badminton -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover transition-all space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19a2 2 0 100-4 2 2 0 000 4z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 15L6 5h12l-4 10" /></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">Badminton</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Badminton Courts</h3>
                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Off-Peak (6AM - 4PM):</span>
                            <strong class="text-slate-900">LKR 2,500/hr</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Peak (4PM - 10PM):</span>
                            <strong class="text-brand-600">LKR 3,200/hr</strong>
                        </div>
                    </div>
                    <a href="{{ route('booking.index') }}?sport=badminton" class="block w-full text-center py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-xs transition-colors">
                        Book Badminton &rarr;
                    </a>
                </div>

                <!-- Squash -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover transition-all space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">Squash</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Squash Courts</h3>
                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Off-Peak (6AM - 4PM):</span>
                            <strong class="text-slate-900">LKR 2,800/hr</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Peak (4PM - 10PM):</span>
                            <strong class="text-brand-600">LKR 3,600/hr</strong>
                        </div>
                    </div>
                    <a href="{{ route('booking.index') }}?sport=squash" class="block w-full text-center py-2.5 rounded-xl bg-slate-900 hover:bg-brand-600 text-white font-semibold text-xs transition-colors">
                        Book Squash &rarr;
                    </a>
                </div>

            </div>
        </div>
    </section>

</x-layouts.app>
