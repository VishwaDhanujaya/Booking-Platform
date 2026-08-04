<x-layouts.super_admin title="Platform Overview | SLTDS Super Admin">

    <div class="space-y-10">

        <!-- CLEAN OPERATIONAL PAGE HEADER (No Marketing Subhero Canvas) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-950 tracking-tight">Platform Central Command</h1>
                <p class="text-xs text-slate-500 font-medium">Real-time aggregate platform metrics and facility operations across all tenant environments.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-black text-white bg-slate-950 hover:bg-slate-800 transition-all shadow-md">
                    + Provision New Tenant &rarr;
                </a>
                <a href="{{ route('superadmin.tenants') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Manage Tenants &rarr;
                </a>
            </div>
        </div>

        <!-- 4 REAL AGGREGATE METRIC CARDS (High-Density & Zero Status Dots) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Metric 1: Total Tenants -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-slate-400 shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Total Tenants</span>
                    <span class="p-2 rounded-full bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-slate-950 tracking-tight">{{ number_format($totalTenants) }}</div>
                <div class="text-xs text-slate-500 font-medium pt-1 border-t border-slate-100">
                    <span class="text-slate-950 font-black">{{ $activeTenants }} Active</span> &bull; {{ $suspendedTenants }} Suspended
                </div>
            </div>

            <!-- Metric 2: Active Facilities -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-slate-400 shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Active Facilities</span>
                    <span class="p-2 rounded-full bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-slate-950 tracking-tight">{{ number_format($activeTenants) }}</div>
                <div class="text-xs text-slate-500 font-medium pt-1 border-t border-slate-100">
                    {{ round(($totalTenants > 0 ? ($activeTenants / $totalTenants) * 100 : 0)) }}% Platform Health Ratio
                </div>
            </div>

            <!-- Metric 3: Bookings This Week -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-slate-400 shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Bookings This Week</span>
                    <span class="p-2 rounded-full bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-slate-950 tracking-tight">{{ number_format($totalBookingsThisWeek) }}</div>
                <div class="text-xs text-slate-500 font-medium pt-1 border-t border-slate-100">
                    Across all live tenant court grids
                </div>
            </div>

            <!-- Metric 4: Signups This Month -->
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-slate-400 shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Signups This Month</span>
                    <span class="p-2 rounded-full bg-slate-100 text-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-slate-950 tracking-tight">{{ number_format($signupsThisMonth) }}</div>
                <div class="text-xs text-slate-500 font-medium pt-1 border-t border-slate-100">
                    New tenant registrations
                </div>
            </div>

        </div>

        <!-- 2 COLUMN LAYOUT: RECENT TENANTS & OVERVIEW (High-Density B2B Tables) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Recent Tenants -->
            <div class="lg:col-span-7 bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 tracking-tight">Recently Provisioned Tenants</h3>
                        <p class="text-xs text-slate-500">Latest tenant accounts onboarded to the platform.</p>
                    </div>
                    <a href="{{ route('superadmin.tenants') }}" class="text-xs font-black text-slate-950 hover:underline">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-200 pb-3 font-black text-[10px]">
                                <th class="pb-3">Facility</th>
                                <th class="pb-3">Category</th>
                                <th class="pb-3">Plan</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($recentTenants as $tenant)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5">
                                        <div class="font-black text-slate-950 text-sm">{{ $tenant->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-mono">{{ $tenant->domain ?? ($tenant->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST)) }}</div>
                                    </td>
                                    <td class="py-3.5 text-slate-600 font-medium">{{ $tenant->category }}</td>
                                    <td class="py-3.5">
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $tenant->subscription_plan }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        @if($tenant->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-50 text-rose-800 border border-rose-200">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <a href="{{ route('superadmin.tenants') }}" class="px-3 py-1 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 transition-colors">
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
            <div class="lg:col-span-5 bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div>
                    <h3 class="text-lg font-black text-slate-950 tracking-tight">Platform Overview</h3>
                    <p class="text-xs text-slate-500">Key subscription and tenant status metrics.</p>
                </div>

                <div class="space-y-3">
                    @foreach($recentTenants->take(5) as $tenant)
                        <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <div>
                                <div class="text-xs font-black text-slate-950">{{ $tenant->name }}</div>
                                <div class="text-[10px] font-mono text-slate-500">{{ $tenant->slug }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-950 text-white">
                                    {{ $tenant->subscription_plan }}
                                </span>
                                @if($tenant->is_active)
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-50 text-rose-800 border border-rose-200">Suspended</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('superadmin.tenants') }}" class="block text-center text-xs font-black text-slate-950 hover:underline pt-1">
                    View all tenants &rarr;
                </a>
            </div>

        </div>

    </div>

</x-layouts.super_admin>
