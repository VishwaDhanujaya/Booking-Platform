<x-layouts.super_admin title="Platform Central Command | SLTDS Super Admin">

    <div class="space-y-10">

        <!-- CLEAN OPERATIONAL PAGE HEADER (VADEL Minimalist Display Typography) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-white/15">
            <div class="space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-md">
                    PLATFORM CENTRAL COMMAND
                </h1>
                <p class="text-xs text-slate-300 font-normal leading-relaxed">
                    Real-time aggregate platform metrics and facility operations across all tenant environments.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-6 py-3 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] hover:scale-105 active:scale-[0.98]">
                    + Provision New Tenant &rarr;
                </a>
                <a href="{{ route('superadmin.tenants') }}" class="inline-flex items-center px-5 py-3 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] hover:scale-105 active:scale-[0.98]">
                    Manage Tenants &rarr;
                </a>
            </div>
        </div>

        <!-- 4 REAL AGGREGATE METRIC CARDS (Parent-Inspired Specular Liquid Glass Cards & Zero Status Dots) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Metric 1: Total Tenants -->
            <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 space-y-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_25px_50px_rgba(0,0,0,0.7)] hover:border-white/40 hover:bg-slate-900/90 transition-all duration-300 group">
                <div class="flex items-center justify-between text-slate-300 text-xs font-bold uppercase tracking-wider">
                    <span>Total Tenants</span>
                    <span class="p-2.5 rounded-2xl bg-white/10 border border-white/20 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-white tracking-tight drop-shadow-md">{{ number_format($totalTenants) }}</div>
                <div class="text-xs text-slate-300 font-medium pt-3 border-t border-white/10">
                    <span class="text-white font-black">{{ $activeTenants }} Active</span> &bull; {{ $suspendedTenants }} Suspended
                </div>
            </div>

            <!-- Metric 2: Active Facilities -->
            <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 space-y-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_25px_50px_rgba(0,0,0,0.7)] hover:border-white/40 hover:bg-slate-900/90 transition-all duration-300 group">
                <div class="flex items-center justify-between text-slate-300 text-xs font-bold uppercase tracking-wider">
                    <span>Active Facilities</span>
                    <span class="p-2.5 rounded-2xl bg-white/10 border border-white/20 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-white tracking-tight drop-shadow-md">{{ number_format($activeTenants) }}</div>
                <div class="text-xs text-slate-300 font-medium pt-3 border-t border-white/10">
                    <span class="text-emerald-400 font-black">{{ round(($totalTenants > 0 ? ($activeTenants / $totalTenants) * 100 : 0)) }}%</span> Platform Health Ratio
                </div>
            </div>

            <!-- Metric 3: Bookings This Week -->
            <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 space-y-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_25px_50px_rgba(0,0,0,0.7)] hover:border-white/40 hover:bg-slate-900/90 transition-all duration-300 group">
                <div class="flex items-center justify-between text-slate-300 text-xs font-bold uppercase tracking-wider">
                    <span>Bookings This Week</span>
                    <span class="p-2.5 rounded-2xl bg-white/10 border border-white/20 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-white tracking-tight drop-shadow-md">{{ number_format($totalBookingsThisWeek) }}</div>
                <div class="text-xs text-slate-300 font-medium pt-3 border-t border-white/10">
                    Across all live tenant court grids
                </div>
            </div>

            <!-- Metric 4: Signups This Month -->
            <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 space-y-4 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_25px_50px_rgba(0,0,0,0.7)] hover:border-white/40 hover:bg-slate-900/90 transition-all duration-300 group">
                <div class="flex items-center justify-between text-slate-300 text-xs font-bold uppercase tracking-wider">
                    <span>Signups This Month</span>
                    <span class="p-2.5 rounded-2xl bg-white/10 border border-white/20 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                </div>
                <div class="text-4xl sm:text-5xl font-black text-white tracking-tight drop-shadow-md">{{ number_format($signupsThisMonth) }}</div>
                <div class="text-xs text-slate-300 font-medium pt-3 border-t border-white/10">
                    New tenant registrations
                </div>
            </div>

        </div>

        <!-- 2 COLUMN LAYOUT: RECENT TENANTS & OVERVIEW (Parent-Inspired Specular Liquid Glass Containers) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Recent Tenants -->
            <div class="lg:col-span-7 bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 sm:p-8 space-y-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.8)]">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-white tracking-tight">Recently Provisioned Tenants</h3>
                        <p class="text-xs text-slate-300">Latest tenant accounts onboarded to the platform.</p>
                    </div>
                    <a href="{{ route('superadmin.tenants') }}" class="text-xs font-black text-white hover:underline">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="text-slate-400 uppercase tracking-wider border-b border-white/15 pb-3 font-black text-[10px]">
                                <th class="pb-3">Facility</th>
                                <th class="pb-3">Category</th>
                                <th class="pb-3">Plan</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 font-medium text-slate-200">
                            @foreach($recentTenants as $tenant)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4">
                                        <div class="font-black text-white text-sm tracking-tight">{{ $tenant->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $tenant->domain ?? ($tenant->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST)) }}</div>
                                    </td>
                                    <td class="py-4 text-slate-300 font-medium">{{ $tenant->category }}</td>
                                    <td class="py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/10 backdrop-blur-xl text-white border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            {{ $tenant->subscription_plan }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        @if($tenant->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="py-4 text-right">
                                        <a href="{{ route('superadmin.tenants') }}" class="px-3.5 py-1.5 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                                            Manage &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Platform Overview -->
            <div class="lg:col-span-5 bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-6 sm:p-8 space-y-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.8)]">
                <div>
                    <h3 class="text-lg font-black text-white tracking-tight">Platform Overview</h3>
                    <p class="text-xs text-slate-300">Key subscription and tenant status metrics.</p>
                </div>

                <div class="space-y-3">
                    @foreach($recentTenants->take(5) as $tenant)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                            <div>
                                <div class="text-xs font-black text-white">{{ $tenant->name }}</div>
                                <div class="text-[10px] font-mono text-slate-400">{{ $tenant->slug }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/15 text-white border border-white/25">
                                    {{ $tenant->subscription_plan }}
                                </span>
                                @if($tenant->is_active)
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Active</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30">Suspended</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('superadmin.tenants') }}" class="block text-center text-xs font-black text-white hover:underline pt-2">
                    View all tenants &rarr;
                </a>
            </div>

        </div>

    </div>

</x-layouts.super_admin>
