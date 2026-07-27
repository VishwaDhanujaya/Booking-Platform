<x-layouts.admin title="Bookings Management | Colombo Courts Admin">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-brand-400 uppercase tracking-widest">Master Reservations Log</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-0.5">Bookings Management</h1>
            </div>
            
            <a href="{{ route('booking.index') }}" target="_blank" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 text-white shadow-md transition-all">
                + Create Walk-in Reservation
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-slate-900 rounded-2xl p-5 border border-slate-800 shadow-xl space-y-4">
            <form action="{{ route('admin.bookings') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search customer, reference #, email..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <svg class="w-4 h-4 text-slate-500 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-2 text-xs w-full sm:w-auto overflow-x-auto pb-1">
                    <a href="{{ route('admin.bookings', ['status' => 'all', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-lg font-bold transition-all {{ $statusFilter === 'all' ? 'bg-brand-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                        All Statuses
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'confirmed', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-lg font-bold transition-all {{ $statusFilter === 'confirmed' ? 'bg-brand-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                        Confirmed
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'cancelled', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-lg font-bold transition-all {{ $statusFilter === 'cancelled' ? 'bg-brand-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                        Cancelled
                    </a>
                </div>

            </form>
        </div>

        <!-- Bookings Master Table -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800 bg-slate-950/80">
                        <th class="p-4">Reference #</th>
                        <th class="p-4">Customer Details</th>
                        <th class="p-4">Court Resource</th>
                        <th class="p-4">Date & Time</th>
                        <th class="p-4">Amount</th>
                        <th class="p-4">Booking Status</th>
                        <th class="p-4">Payment</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-xs">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="p-4 font-mono font-bold text-brand-300">
                            {{ $b->booking_reference }}
                        </td>
                        <td class="p-4 font-semibold text-white">
                            {{ $b->customer_name }}
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $b->customer_email }} &bull; {{ $b->customer_phone }}</span>
                        </td>
                        <td class="p-4 font-semibold text-slate-200">
                            {{ $b->court ? $b->court->name : 'Court' }}
                            <span class="block text-[10px] text-slate-500 font-normal capitalize">{{ $b->court && $b->court->sportCategory ? $b->court->sportCategory->name : '' }}</span>
                        </td>
                        <td class="p-4 text-slate-300 font-medium">
                            {{ $b->booking_date }}
                            <span class="block text-[10px] text-slate-400">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</span>
                        </td>
                        <td class="p-4 font-bold text-emerald-400">
                            LKR {{ number_format($b->total_amount) }}
                        </td>
                        <td class="p-4">
                            @if($b->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Confirmed</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-500/20 text-brand-300 border border-brand-500/40 uppercase">
                                {{ strtoupper($b->payment_status ?? 'PAID') }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('booking.confirmation', ['reference' => $b->booking_reference]) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 transition-colors">
                                    Gate Pass
                                </a>
                                <a href="{{ route('admin.bookings.invoice', ['id' => $b->id]) }}" target="_blank" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-indigo-300 bg-indigo-900/40 hover:bg-indigo-800/60 border border-indigo-500/30 transition-colors">
                                    Invoice
                                </a>
                                @if(in_array($b->payment_status, ['pending', 'unpaid', 'payment_pending']))
                                <form action="{{ route('admin.bookings.mark-paid', ['id' => $b->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-emerald-300 bg-emerald-600/30 hover:bg-emerald-600/50 border border-emerald-500/40 transition-colors">
                                        Mark Paid
                                    </button>
                                </form>
                                @endif
                                @if($b->status !== 'cancelled')
                                <form action="{{ route('customer.booking.cancel', ['id' => $b->id]) }}" method="POST" onsubmit="return confirm('Cancel booking reference {{ $b->booking_reference }}?')">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-semibold text-rose-400 hover:bg-rose-500/20 border border-rose-500/30 transition-colors">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-500">
                            No bookings matching your search query.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</x-layouts.admin>
