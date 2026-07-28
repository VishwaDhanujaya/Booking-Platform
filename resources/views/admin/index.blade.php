<x-layouts.admin title="Dashboard Overview | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header Title Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Real-Time Facility Overview</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">{{ $tenant['name'] ?? 'Facility' }} Management</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings') }}" class="px-4 py-2.5 rounded-xl font-extrabold text-xs bg-sltds-endeavour hover:bg-sltds-sky text-white shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01]">
                    View All Bookings &rarr;
                </a>
                <a href="{{ route('admin.courts') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 transition-all">
                    + Add New Court
                </a>
            </div>
        </div>

        <!-- 4 Summary KPI Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Bookings Today -->
            <div class="bg-white rounded-3xl p-6 border-2 border-slate-200/80 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Bookings Today</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-sltds-endeavour flex items-center justify-center font-bold text-xs border border-blue-200">
                        <svg class="w-5 h-5 text-sltds-endeavour" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['bookings_today'] }}</h2>
                    <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +14% vs yesterday
                    </span>
                </div>
            </div>

            <!-- Card 2: Revenue This Week -->
            <div class="bg-white rounded-3xl p-6 border-2 border-slate-200/80 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Weekly Revenue</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-sltds-green flex items-center justify-center font-bold text-xs border border-emerald-200">
                        <svg class="w-5 h-5 text-sltds-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">LKR {{ number_format($stats['weekly_revenue']) }}</h2>
                    <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +22% vs last week
                    </span>
                </div>
            </div>

            <!-- Card 3: Occupancy Rate -->
            <div class="bg-white rounded-3xl p-6 border-2 border-slate-200/80 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Peak Occupancy</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs border border-amber-200">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['occupancy_rate'] }}%</h2>
                    <span class="text-[11px] text-amber-700 font-bold flex items-center gap-1 mt-1">
                        Top performance in region
                    </span>
                </div>
            </div>

            <!-- Card 4: Active Members -->
            <div class="bg-white rounded-3xl p-6 border-2 border-slate-200/80 shadow-xl space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Active Members</span>
                    <div class="w-9 h-9 rounded-xl bg-cyan-50 text-sltds-sky flex items-center justify-center font-bold text-xs border border-cyan-200">
                        <svg class="w-5 h-5 text-sltds-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $stats['active_members'] }}</h2>
                    <span class="text-[11px] text-sltds-endeavour font-bold flex items-center gap-1 mt-1">
                        +8 new signups this week
                    </span>
                </div>
            </div>

        </div>

        <!-- Recent Live Reservations Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-4 p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Recent Live Bookings</h3>
                    <p class="text-xs text-slate-500">Real-time bookings recorded across all facility courts.</p>
                </div>
                <a href="{{ route('admin.bookings') }}" class="text-xs font-extrabold text-sltds-endeavour hover:underline">
                    View Full Bookings Log &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-200">
                    <thead>
                        <tr class="text-[11px] font-black text-slate-500 uppercase tracking-wider border-b border-slate-200 bg-slate-50">
                            <th class="p-3">Reference #</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Court Resource</th>
                            <th class="p-3">Date & Time</th>
                            <th class="p-3">Amount</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @foreach($recentBookings as $b)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3 font-mono font-bold text-sltds-endeavour">
                                {{ $b->booking_reference }}
                            </td>
                            <td class="p-3 font-bold text-slate-900">
                                {{ $b->customer_name }}
                                <span class="block text-[10px] text-slate-500 font-normal">{{ $b->customer_phone }}</span>
                            </td>
                            <td class="p-3 font-semibold text-slate-800">
                                {{ $b->court ? $b->court->name : 'Court' }}
                            </td>
                            <td class="p-3 text-slate-700 font-medium">
                                {{ $b->booking_date }}
                                <span class="block text-[10px] text-slate-500">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</span>
                            </td>
                            <td class="p-3 font-black text-emerald-600">
                                LKR {{ number_format($b->total_amount) }}
                            </td>
                            <td class="p-3">
                                @if($b->status === 'confirmed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">Confirmed</span>
                                @elseif($b->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-300">Completed</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('booking.confirmation', ['reference' => $b->booking_reference]) }}" target="_blank" class="px-3 py-1 rounded-xl text-[11px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                                    View Pass
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.admin>
