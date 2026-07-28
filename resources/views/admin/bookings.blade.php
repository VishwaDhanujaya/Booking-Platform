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
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Master Reservations Log</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Bookings Schedule</h1>
            </div>
            
            <a href="{{ route('booking.index') }}" target="_blank" class="px-4 py-2.5 rounded-xl font-extrabold text-xs bg-sltds-endeavour hover:bg-sltds-sky text-white shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01]">
                + Create Walk-in Reservation
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-3xl p-5 border-2 border-slate-200/80 shadow-xl space-y-4">
            <form action="{{ route('admin.bookings') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <!-- Search Input -->
                <div class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Search customer, reference #, phone, email..." class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-sltds-endeavour font-medium">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-2 text-xs w-full sm:w-auto overflow-x-auto pb-1">
                    <a href="{{ route('admin.bookings', ['status' => 'all', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-xl font-extrabold transition-all {{ $statusFilter === 'all' ? 'bg-sltds-endeavour text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                        All Statuses
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'confirmed', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-xl font-extrabold transition-all {{ $statusFilter === 'confirmed' ? 'bg-sltds-endeavour text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                        Confirmed
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'no_show', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-xl font-extrabold transition-all {{ $statusFilter === 'no_show' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                        No-Show
                    </a>
                    <a href="{{ route('admin.bookings', ['status' => 'cancelled', 'search' => request('search')]) }}" 
                       class="px-3.5 py-2 rounded-xl font-extrabold transition-all {{ $statusFilter === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-200' }}">
                        Cancelled
                    </a>
                </div>

            </form>
        </div>

        <!-- Bookings Master Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[11px] font-black text-slate-500 uppercase tracking-wider border-b border-slate-200 bg-slate-50">
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
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-mono font-bold text-sltds-endeavour">
                            {{ $b->booking_reference }}
                        </td>
                        <td class="p-4 font-bold text-slate-900">
                            {{ $b->customer_name }}
                            <span class="block text-[10px] text-slate-500 font-normal">{{ $b->customer_email }} &bull; {{ $b->customer_phone }}</span>
                        </td>
                        <td class="p-4 font-semibold text-slate-800">
                            {{ $b->court ? $b->court->name : 'Court' }}
                            <span class="block text-[10px] text-slate-500 font-normal capitalize">{{ $b->court && $b->court->sportCategory ? $b->court->sportCategory->name : '' }}</span>
                        </td>
                        <td class="p-4 text-slate-700 font-medium">
                            {{ $b->booking_date }}
                            <span class="block text-[10px] text-slate-500">{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</span>
                        </td>
                        <td class="p-4 font-black text-emerald-600">
                            LKR {{ number_format($b->total_amount) }}
                        </td>
                        <td class="p-4">
                            @if($b->status === 'confirmed')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">Confirmed</span>
                            @elseif($b->status === 'no_show')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">No-Show</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">Cancelled</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-sltds-endeavour border border-blue-200 uppercase">
                                {{ strtoupper($b->payment_status ?? 'UNPAID') }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <a href="{{ route('booking.confirmation', ['reference' => $b->booking_reference]) }}" target="_blank" class="px-2.5 py-1 rounded-xl text-[11px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                                    Pass
                                </a>
                                <a href="{{ route('admin.bookings.invoice', ['id' => $b->id]) }}" target="_blank" class="px-2.5 py-1 rounded-xl text-[11px] font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    Invoice
                                </a>

                                @if($b->status !== 'cancelled')
                                    <!-- Mark Paid Button -->
                                    @if(in_array($b->payment_status, ['pending', 'unpaid', 'payment_pending']))
                                    <form action="{{ route('admin.bookings.mark-paid', ['id' => $b->id]) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="inline">
                                        @csrf
                                        <button type="submit" :disabled="submitting" class="px-2.5 py-1 rounded-xl text-[11px] font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 transition-colors disabled:opacity-50 cursor-pointer">
                                            Mark Paid
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Mark No-Show Button -->
                                    @if($b->status === 'confirmed' && $b->booking_date <= \Carbon\Carbon::today()->toDateString())
                                    <form action="{{ route('admin.bookings.noshow', ['id' => $b->id]) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" onsubmit="return confirm('Record No-Show for {{ $b->booking_reference }}?')" class="inline">
                                        @csrf
                                        <button type="submit" :disabled="submitting" class="px-2.5 py-1 rounded-xl text-[11px] font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-300 transition-colors disabled:opacity-50 cursor-pointer">
                                            No-Show
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Cancel Button -->
                                    <button type="button" @click="openCancelModal({{ $b->id }}, '{{ $b->booking_reference }}')" class="px-2.5 py-1 rounded-xl text-[11px] font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-slate-500 font-medium">
                            No bookings matching your search query.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Bar -->
            @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50">
                {{ $bookings->links() }}
            </div>
            @endif
        </div>

        <!-- CANCEL BOOKING MODAL -->
        <div x-show="cancelModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="cancelModalOpen = false" class="bg-white rounded-3xl p-6 max-w-md w-full space-y-4 border-2 border-slate-200 shadow-2xl text-left">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <h3 class="text-base font-black text-slate-900">Cancel Booking Reservation</h3>
                    <button @click="cancelModalOpen = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
                </div>

                <p class="text-xs text-slate-600 font-medium">
                    Are you sure you want to cancel booking <strong class="text-slate-900" x-text="cancelBookingRef"></strong>? Reserved court time slots will be freed up for other users.
                </p>

                <form :action="'/admin/bookings/' + cancelBookingId + '/cancel'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Cancellation Reason / Notes (Optional)</label>
                        <textarea name="notes" x-model="cancelNotes" rows="3" placeholder="e.g. Customer called to request cancellation due to rain" class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-medium focus:outline-none focus:border-sltds-endeavour"></textarea>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-3">
                        <button type="button" @click="cancelModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-300 font-bold text-slate-600 hover:bg-slate-100">
                            Dismiss
                        </button>
                        <button type="submit" :disabled="submitting" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold shadow-md disabled:opacity-50">
                            Confirm Cancellation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>
