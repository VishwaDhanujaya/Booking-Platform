<x-layouts.admin title="Bookings Schedule | Facility Management">

    <div x-data="{ 
        cancelModalOpen: false,
        cancelBookingId: null,
        cancelBookingRef: '',
        cancelNotes: '',
        openCancelModal(id, ref) {
            this.cancelBookingId = id;
            this.cancelBookingRef = ref;
            this.cancelNotes = '';
            this.cancelModalOpen = true;
        }
    }" class="max-w-7xl mx-auto space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-sky-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Master Reservations Log
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    Bookings Schedule
                </h1>
            </div>
            
            <a href="{{ route('booking.index') }}" target="_blank" class="px-5 py-2.5 rounded-full font-black text-xs text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-95">
                + Create Walk-in Reservation
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl p-5 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-4">
            <form action="{{ route('admin.bookings') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search customer, reference #, phone, email..." class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-white/10 backdrop-blur-2xl border border-white/20 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-white/50 focus:bg-white/20 font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-2 text-xs w-full sm:w-auto overflow-x-auto pb-1">
                    <a href="{{ route('admin.bookings', ['status' => 'all', 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full font-black text-xs transition-all duration-200 {{ $statusFilter === 'all' ? 'bg-white text-slate-950 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]' : 'bg-white/10 text-slate-300 hover:text-white hover:bg-white/20 border border-white/15' }}">
                        All Statuses
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'confirmed', 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full font-black text-xs transition-all duration-200 {{ $statusFilter === 'confirmed' ? 'bg-white text-slate-950 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]' : 'bg-white/10 text-slate-300 hover:text-white hover:bg-white/20 border border-white/15' }}">
                        Confirmed
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'no_show', 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full font-black text-xs transition-all duration-200 {{ $statusFilter === 'no_show' ? 'bg-white text-slate-950 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]' : 'bg-white/10 text-slate-300 hover:text-white hover:bg-white/20 border border-white/15' }}">
                        No-Show
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'cancelled', 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full font-black text-xs transition-all duration-200 {{ $statusFilter === 'cancelled' ? 'bg-white text-slate-950 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]' : 'bg-white/10 text-slate-300 hover:text-white hover:bg-white/20 border border-white/15' }}">
                        Cancelled
                    </a>
                </div>

            </form>
        </div>

        <!-- Bookings Master Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/10 bg-white/5">
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
                <tbody class="divide-y divide-white/10 text-xs font-medium">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-4 font-mono font-bold text-sky-400">
                            {{ $b->booking_reference }}
                        </td>
                        <td class="p-4 font-bold text-white">
                            {{ $b->customer_name }}
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $b->customer_email }} &bull; {{ $b->customer_phone }}</span>
                        </td>
                        <td class="p-4 font-semibold text-slate-300">
                            {{ $b->court ? $b->court->name : 'Court' }}
                            <span class="block text-[10px] text-slate-400 font-normal capitalize">{{ $b->court && $b->court->sportCategory ? $b->court->sportCategory->name : '' }}</span>
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
                            @elseif($b->status === 'no_show')
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-500/20 text-amber-300 border border-amber-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">No-Show</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-white/10 text-slate-200 border border-white/20 uppercase shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                                {{ strtoupper($b->payment_status ?? 'UNPAID') }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <a href="{{ route('booking.confirmation', ['reference' => $b->booking_reference]) }}" target="_blank" class="px-3 py-1 rounded-full text-[11px] font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                    Pass
                                </a>
                                <a href="{{ route('admin.bookings.invoice', ['id' => $b->id]) }}" target="_blank" class="px-3 py-1 rounded-full text-[11px] font-bold text-sky-300 bg-sky-500/20 hover:bg-sky-500/30 border border-sky-400/30 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                    Invoice
                                </a>

                                @if($b->status !== 'cancelled')
                                    <!-- Mark Paid Button -->
                                    @if(in_array($b->payment_status, ['pending', 'unpaid', 'payment_pending']))
                                    <form action="{{ route('admin.bookings.mark-paid', ['id' => $b->id]) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="inline">
                                        @csrf
                                        <button type="submit" :disabled="submitting" class="px-3 py-1 rounded-full text-[11px] font-black text-emerald-300 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-400/30 transition-all disabled:opacity-50 cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            Mark Paid
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Mark No-Show Button -->
                                    @if($b->status === 'confirmed' && $b->booking_date <= \Carbon\Carbon::today()->toDateString())
                                    <form action="{{ route('admin.bookings.noshow', ['id' => $b->id]) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" onsubmit="return confirm('Record No-Show for {{ $b->booking_reference }}?')" class="inline">
                                        @csrf
                                        <button type="submit" :disabled="submitting" class="px-3 py-1 rounded-full text-[11px] font-black text-amber-300 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-400/30 transition-all disabled:opacity-50 cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            No-Show
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Cancel Button -->
                                    <button type="button" @click="openCancelModal({{ $b->id }}, '{{ $b->booking_reference }}')" class="px-3 py-1 rounded-full text-[11px] font-black text-rose-300 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-400/30 transition-all cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-400 font-medium">
                            No bookings matching your search query.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Bar -->
            @if($bookings->hasPages())
            <div class="p-4 border-t border-white/10 bg-white/5">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>

        <!-- CANCEL BOOKING MODAL -->
        <div x-show="cancelModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div @click.away="cancelModalOpen = false" class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl p-6 sm:p-8 max-w-md w-full space-y-4 border border-white/20 shadow-2xl text-left text-white">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Cancel Reservation</h3>
                    <button @click="cancelModalOpen = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <p class="text-xs text-slate-300 font-medium leading-relaxed">
                    Are you sure you want to cancel booking <strong class="text-white font-mono font-bold" x-text="cancelBookingRef"></strong>? Reserved court time slots will be freed up.
                </p>

                <form :action="'/admin/bookings/' + cancelBookingId + '/cancel'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Cancellation Reason / Notes (Optional)</label>
                        <textarea name="notes" x-model="cancelNotes" rows="3" placeholder="e.g. Customer called to request cancellation due to weather" class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]"></textarea>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">
                            Dismiss
                        </button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full bg-rose-600 hover:bg-rose-500 text-white font-black uppercase tracking-wider shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_20px_rgba(225,29,72,0.4)] disabled:opacity-50 transition-all cursor-pointer">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>

