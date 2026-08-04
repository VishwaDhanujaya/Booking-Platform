<x-layouts.admin title="Dashboard Overview | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header Title Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-emerald-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Real-Time Facility Control
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    {{ $tenant['name'] ?? 'Facility' }} Management
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings') }}" class="px-5 py-2.5 rounded-full font-black text-xs text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-95">
                    View All Bookings &rarr;
                </a>
                <a href="{{ route('admin.courts') }}" class="px-5 py-2.5 rounded-full font-black text-xs text-white bg-white/10 hover:bg-white/20 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] transition-all duration-200 hover:scale-105 active:scale-95">
                    + Add New Court
                </a>
            </div>
        </div>

        <!-- 4 Summary KPI Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Card 1: Bookings Today -->
            <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-6 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Bookings Today</span>
                    <div class="w-10 h-10 rounded-2xl bg-white/10 text-emerald-400 border border-white/20 flex items-center justify-center font-bold text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-sm">{{ $stats['bookings_today'] }}</h2>
                    <span class="text-[11px] text-emerald-400 font-extrabold flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +14% vs yesterday
                    </span>
                </div>
            </div>

            <!-- Card 2: Revenue This Week -->
            <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-6 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Weekly Revenue</span>
                    <div class="w-10 h-10 rounded-2xl bg-white/10 text-emerald-400 border border-white/20 flex items-center justify-center font-bold text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-sm">LKR {{ number_format($stats['weekly_revenue']) }}</h2>
                    <span class="text-[11px] text-emerald-400 font-extrabold flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +22% vs last week
                    </span>
                </div>
            </div>

            <!-- Card 3: Occupancy Rate -->
            <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-6 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Peak Occupancy</span>
                    <div class="w-10 h-10 rounded-2xl bg-white/10 text-amber-400 border border-white/20 flex items-center justify-center font-bold text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-sm">{{ $stats['occupancy_rate'] }}%</h2>
                    <span class="text-[11px] text-amber-400 font-extrabold flex items-center gap-1 mt-1">
                        Top performance in region
                    </span>
                </div>
            </div>

            <!-- Card 4: Active Members -->
            <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-6 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400">Active Members</span>
                    <div class="w-10 h-10 rounded-2xl bg-white/10 text-sky-400 border border-white/20 flex items-center justify-center font-bold text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-sm">{{ $stats['active_members'] }}</h2>
                    <span class="text-[11px] text-sky-400 font-extrabold flex items-center gap-1 mt-1">
                        +8 new signups this week
                    </span>
                </div>
            </div>

        </div>

        <!-- Recent Live Reservations Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4 p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-white/10 pb-5">
                <div>
                    <h3 class="text-lg font-black italic tracking-tighter uppercase text-white drop-shadow-sm">Recent Live Bookings</h3>
                    <p class="text-xs text-slate-400 font-medium">Real-time bookings recorded across all facility courts.</p>
                </div>
                <a href="{{ route('admin.bookings') }}" class="text-xs font-black text-slate-300 hover:text-white uppercase tracking-wider transition-colors">
                    View Full Bookings Log &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-200">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/10 bg-white/5">
                            <th class="p-4">Reference #</th>
                            <th class="p-4">Customer</th>
                            <th class="p-4">Court Resource</th>
                            <th class="p-4">Date & Time</th>
                            <th class="p-4">Amount</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-xs font-medium">
                        @foreach($recentBookings as $b)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4 font-mono font-bold text-sky-400">
                                {{ $b->booking_reference }}
                            </td>
                            <td class="p-4 font-bold text-white">
                                {{ $b->customer_name }}
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $b->customer_phone }}</span>
                            </td>
                            <td class="p-4 font-semibold text-slate-300">
                                {{ $b->court ? $b->court->name : 'Court' }}
                            </td>
                            <td class="p-4 text-slate-300 font-medium">
                                {{ $b->booking_date }}
                                <span class="block text-[10px] text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</span>
                            </td>
                            <td class="p-4 font-black text-emerald-400">
                                LKR {{ number_format($b->total_amount) }}
                            </td>
                            <td class="p-4">
                                @if($b->status === 'confirmed')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Confirmed</span>
                                @elseif($b->status === 'completed')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-white/10 text-slate-300 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Completed</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('booking.confirmation', ['reference' => $b->booking_reference]) }}" target="_blank" class="px-3.5 py-1.5 rounded-full text-[11px] font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
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

