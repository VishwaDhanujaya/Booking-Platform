<x-layouts.super_admin title="Platform Overview | SLTDS Super Admin">

    <div class="space-y-10">

        <!-- Page Top Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 shadow-xs">
                        System Metrics
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Platform Central Command</h1>
                <p class="text-xs text-slate-500">Real-time aggregate platform metrics across all tenant environments.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-4.5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                    + Provision New Tenant
                </a>
                <a href="{{ route('superadmin.tenants') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-colors shadow-xs">
                    Manage Tenants
                </a>
            </div>
        </div>

        <!-- 4 Real Aggregate Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Metric 1: Total Tenants -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-sltds-endeavour hover:shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Total Tenants</span>
                    <span class="p-2 rounded-xl bg-blue-50 text-sltds-endeavour border border-blue-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-slate-900">{{ number_format($totalTenants) }}</div>
                <div class="text-[11px] text-slate-500 font-medium">
                    <span class="text-sltds-green font-bold">{{ $activeTenants }} Active</span> &bull; {{ $suspendedTenants }} Suspended
                </div>
            </div>

            <!-- Metric 2: Active Facilities -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-sltds-green hover:shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Active Facilities</span>
                    <span class="p-2 rounded-xl bg-emerald-50 text-sltds-green border border-emerald-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-sltds-green">{{ number_format($activeTenants) }}</div>
                <div class="text-[11px] text-slate-500 font-medium">
                    {{ round(($totalTenants > 0 ? ($activeTenants / $totalTenants) * 100 : 0)) }}% Platform Health Ratio
                </div>
            </div>

            <!-- Metric 3: Bookings This Week -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-sltds-sky hover:shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Bookings This Week</span>
                    <span class="p-2 rounded-xl bg-cyan-50 text-sltds-sky border border-cyan-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-sltds-sky">{{ number_format($totalBookingsThisWeek) }}</div>
                <div class="text-[11px] text-slate-500 font-medium">
                    Across all live tenant court grids
                </div>
            </div>

            <!-- Metric 4: Signups This Month -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-3 hover:border-purple-500 hover:shadow-xl transition-all">
                <div class="flex items-center justify-between text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <span>Signups This Month</span>
                    <span class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-200">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </span>
                </div>
                <div class="text-4xl font-black text-purple-600">{{ number_format($signupsThisMonth) }}</div>
                <div class="text-[11px] text-slate-500 font-medium">
                    New tenant registrations
                </div>
            </div>

        </div>

        <!-- 2 Column Layout: Recent Tenants & Recent Users -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Recent Tenants -->
            <div class="lg:col-span-7 bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Recently Provisioned Tenants</h3>
                        <p class="text-xs text-slate-500">Latest tenant accounts onboarded to the platform.</p>
                    </div>
                    <a href="{{ route('superadmin.tenants') }}" class="text-xs text-sltds-endeavour font-bold hover:underline">View All &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 uppercase tracking-wider border-b border-slate-200 pb-3 font-bold">
                                <th class="pb-3">Facility</th>
                                <th class="pb-3">Category</th>
                                <th class="pb-3">Plan</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentTenants as $tenant)
                                <tr>
                                    <td class="py-3.5">
                                        <div class="font-bold text-slate-900">{{ $tenant->name }}</div>
                                        <div class="text-[10px] text-slate-500 font-mono">{{ $tenant->domain ?? ($tenant->slug . '.localhost') }}</div>
                                    </td>
                                    <td class="py-3.5 text-slate-600">{{ $tenant->category }}</td>
                                    <td class="py-3.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-50 text-sltds-endeavour border border-blue-200">
                                            {{ $tenant->subscription_plan }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        @if($tenant->is_active)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-sltds-green border border-emerald-200">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 text-right">
                                        <a href="{{ route('superadmin.tenants.users', $tenant->id) }}" class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-slate-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors">
                                            Users &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Recent Tenant Users -->
            <div class="lg:col-span-5 bg-white border-2 border-slate-200/80 rounded-3xl p-6 space-y-6 shadow-sm">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Recent Tenant Users & Staff</h3>
                    <p class="text-xs text-slate-500">Latest user accounts registered across all facilities.</p>
                </div>

                @if($recentUsers->isEmpty())
                    <div class="p-6 text-center text-xs text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
                        No tenant user accounts recorded yet.
                    </div>
                @else
                    <div class="space-y-3 max-h-95 overflow-y-auto pr-1">
                        @foreach($recentUsers as $u)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">{{ $u->name }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-blue-50 text-sltds-endeavour border border-blue-200">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                </div>
                                <div class="text-slate-600">
                                    Email: <strong class="text-slate-800">{{ $u->email }}</strong>
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium">
                                    Facility: <span class="text-sltds-endeavour font-bold">{{ $u->tenant->name ?? 'System' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</x-layouts.super_admin>
