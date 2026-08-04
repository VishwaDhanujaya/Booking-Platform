@php
    $tenant = \App\Services\TenantResolver::getActiveTenant();
    $tenantName = $tenant['name'];
@endphp
<x-layouts.app title="My Account & Bookings | {{ $tenantName }}">

    <!-- Page Header & Customer Profile Banner -->
    <section class="bg-slate-900 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <!-- User Profile Intro -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-brand-600 text-white font-black text-2xl flex items-center justify-center border-2 border-brand-400 shadow-xl">
                        KP
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-extrabold text-white">{{ $customer['name'] }}</h1>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-500/20 text-brand-300 border border-brand-500/40 uppercase tracking-wider">
                                {{ $customer['tier'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-3">
                            <span>ID: <strong class="text-slate-200 font-mono">{{ $customer['member_id'] }}</strong></span> &bull; 
                            <span>{{ $customer['email'] }}</span> &bull; 
                            <span>{{ $customer['phone'] }}</span>
                        </p>
                    </div>
                </div>

                <!-- Sign Out Form -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 transition-colors">
                        Sign Out of Account
                    </button>
                </form>
            </div>

        </div>
    </section>

    <!-- Main Customer Dashboard Area with Alpine.js Tabs -->
    <section x-data="{ 
        activeTab: 'bookings',
        bookingFilter: 'upcoming',
        showQrModal: false,
        activeQrRef: '',
        activeQrCourt: '',
        activeQrDate: '',
        openQrPass(ref, court, date) {
            this.activeQrRef = ref;
            this.activeQrCourt = court;
            this.activeQrDate = date;
            this.showQrModal = true;
        }
    }" class="py-10 bg-slate-50 min-h-screen">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Flash Session Status Message -->
            @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-5 py-4 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
            @endif

            <!-- Navigation Tabs Bar -->
            <div class="flex items-center justify-between border-b border-slate-200 pb-4 flex-wrap gap-4">
                <div class="flex items-center gap-2 overflow-x-auto">
                    
                    <button @click="activeTab = 'bookings'"
                            :class="activeTab === 'bookings' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>My Court Bookings</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'bookings' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                            {{ $upcomingBookings->count() + $pastBookings->count() }}
                        </span>
                    </button>

                    <button @click="activeTab = 'credits'"
                            :class="activeTab === 'credits' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Credits & Membership Passes</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'credits' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600'">
                            LKR {{ number_format($credits['balance']) }}
                        </span>
                    </button>

                    <button @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Account Profile</span>
                    </button>

                </div>

                <a href="{{ route('booking.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-accent-600 hover:bg-accent-500 shadow-md transition-all flex items-center gap-1.5">
                    + Book New Court Slot
                </a>
            </div>

            <!-- TAB 1: MY COURT BOOKINGS -->
            <div x-show="activeTab === 'bookings'" class="space-y-6">
                
                <!-- Filter Pills -->
                <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
                    <button @click="bookingFilter = 'upcoming'"
                            :class="bookingFilter === 'upcoming' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all">
                        Upcoming ({{ $upcomingBookings->count() }})
                    </button>
                    <button @click="bookingFilter = 'past'"
                            :class="bookingFilter === 'past' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all">
                        Completed / Past ({{ $pastBookings->count() }})
                    </button>
                    <button @click="bookingFilter = 'cancelled'"
                            :class="bookingFilter === 'cancelled' ? 'bg-slate-900 text-white font-bold' : 'text-slate-600 hover:text-slate-900 font-semibold'"
                            class="px-3.5 py-1.5 rounded-lg text-xs transition-all">
                        Cancelled ({{ $cancelledBookings->count() }})
                    </button>
                </div>

                <!-- Upcoming Bookings List -->
                <div x-show="bookingFilter === 'upcoming'" class="space-y-4">
                    @forelse($upcomingBookings as $b)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6 transition-all hover:border-slate-300">
                        <div class="flex items-center gap-4">
                            <img src="{{ $b->court ? $b->court->image_url : '/images/courts/tennis.png' }}" class="w-20 h-20 rounded-xl object-cover border border-slate-200" alt="" />
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md">
                                        {{ $b->court && $b->court->sportCategory ? $b->court->sportCategory->name : 'Court' }}
                                    </span>
                                    <span class="text-xs font-mono font-bold text-slate-400">{{ $b->booking_reference }}</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900">{{ $b->court ? $b->court->name : 'Center Court' }}</h3>
                                <p class="text-xs text-slate-600 font-medium flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <strong>{{ \Carbon\Carbon::parse($b->booking_date)->format('D, M j, Y') }}</strong>
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 3"/></svg>
                                        <strong>{{ substr($b->start_time, 0, 5) }} – {{ substr($b->end_time, 0, 5) }}</strong>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-6 pt-4 md:pt-0 border-t md:border-0 border-slate-100">
                            <div class="text-left md:text-right">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Paid</span>
                                <strong class="text-sm font-black text-slate-900">LKR {{ number_format($b->total_amount) }}</strong>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button" @click="openQrPass('{{ $b->booking_reference }}', '{{ $b->court ? $b->court->name : '' }}', '{{ $b->booking_date }}')" 
                                        class="px-4 py-2.5 rounded-xl font-bold text-xs bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 transition-colors flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    View QR Gate Pass
                                </button>

                                <a href="{{ route('booking.invoice', ['reference' => $b->booking_reference]) }}" target="_blank" 
                                   class="px-3.5 py-2.5 rounded-xl font-bold text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Invoice
                                </a>

                                <form action="{{ route('customer.booking.cancel', ['id' => $b->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking reference ({{ $b->booking_reference }})?')">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-2.5 rounded-xl font-semibold text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 transition-colors">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center bg-white rounded-2xl border border-slate-200 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center mx-auto text-xl font-bold">!</div>
                        <h3 class="text-sm font-bold text-slate-900">No Upcoming Court Reservations</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto">Lock your next match on Colombo's premier sports courts.</p>
                        <a href="{{ route('booking.index') }}" class="inline-flex px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md">
                            Browse Open Time Slots &rarr;
                        </a>
                    </div>
                    @endforelse
                </div>

                <!-- Past Bookings List -->
                <div x-show="bookingFilter === 'past'" x-cloak class="space-y-4">
                    @forelse($pastBookings as $pb)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6 opacity-85">
                        <div class="flex items-center gap-4">
                            <img src="{{ $pb->court ? $pb->court->image_url : '/images/courts/tennis.png' }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200" alt="" />
                            <div>
                                <span class="text-xs font-mono text-slate-400">{{ $pb->booking_reference }}</span>
                                <h3 class="text-sm font-bold text-slate-900">{{ $pb->court ? $pb->court->name : 'Court' }}</h3>
                                <p class="text-xs text-slate-500">{{ $pb->booking_date }} &bull; {{ substr($pb->start_time, 0, 5) }} - {{ substr($pb->end_time, 0, 5) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Completed</span>
                            <a href="{{ route('booking.index', ['court_id' => $pb->court_id]) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-800 text-white hover:bg-slate-700">
                                Rebook Court &rarr;
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                        No past match history found.
                    </div>
                    @endforelse
                </div>

                <!-- Cancelled Bookings List -->
                <div x-show="bookingFilter === 'cancelled'" x-cloak class="space-y-4">
                    @forelse($cancelledBookings as $cb)
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6 opacity-75">
                        <div class="flex items-center gap-4">
                            <img src="{{ $cb->court ? $cb->court->image_url : '/images/courts/tennis.png' }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 grayscale" alt="" />
                            <div>
                                <span class="text-xs font-mono text-slate-400">{{ $cb->booking_reference }}</span>
                                <h3 class="text-sm font-bold text-slate-900 line-through">{{ $cb->court ? $cb->court->name : 'Court' }}</h3>
                                <p class="text-xs text-slate-500">{{ $cb->booking_date }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">Cancelled</span>
                    </div>
                    @empty
                    <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                        No cancelled bookings.
                    </div>
                    @endforelse
                </div>

            </div>

            <!-- TAB 2: CREDITS & MEMBERSHIP PASSES -->
            <div x-show="activeTab === 'credits'" x-cloak class="space-y-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Credits Balance Widget (5 cols) -->
                    <div class="lg:col-span-5 bg-linear-to-br from-slate-900 via-brand-950 to-slate-900 text-white rounded-3xl p-8 border border-slate-800 shadow-2xl space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-brand-300">Club Store Wallet</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/40">Active Balance</span>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Available Court Credits</span>
                            <h2 class="text-4xl font-black text-white tracking-tight mt-1">LKR {{ number_format($credits['balance']) }}</h2>
                        </div>

                        <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs">
                            <span class="text-slate-400">Auto Top-Up: <strong>Enabled</strong></span>
                            <button type="button" onclick="alert('Credits top-up simulation: Added LKR 5,000 to wallet balance.')" class="px-4 py-2 rounded-xl font-bold text-slate-950 bg-accent-400 hover:bg-accent-300 transition-colors">
                                + Top-Up Credits
                            </button>
                        </div>
                    </div>

                    <!-- Active Membership Passes List (7 cols) -->
                    <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-200/90 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Active Subscription & Multi-Passes</span>
                            <h3 class="text-xl font-extrabold text-slate-900 mt-1">Your Membership Passes ({{ count($activePasses) }})</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach($activePasses as $pass)
                            <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-extrabold text-slate-900">{{ $pass['name'] }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ $pass['status'] }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium">{{ $pass['sport'] }}</p>
                                    <p class="text-xs font-bold text-brand-600">{{ $pass['discount'] }}</p>
                                </div>

                                <div class="text-left sm:text-right text-xs text-slate-500">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Valid Until</span>
                                    <strong class="text-slate-900 font-bold">{{ $pass['valid_until'] }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

            <!-- TAB 3: ACCOUNT PROFILE SETTINGS -->
            <div x-show="activeTab === 'profile'" x-cloak class="max-w-3xl bg-white rounded-3xl p-8 border border-slate-200/90 shadow-card space-y-6">
                <div>
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Player Settings</span>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">Account Profile & Preferences</h3>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ $customer['name'] }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 text-sm font-bold text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                            <input type="email" value="{{ $customer['email'] }}" readonly class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-sm text-slate-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone</label>
                            <input type="tel" value="{{ $customer['phone'] }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm text-slate-900">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Preferred Sports</label>
                            <input type="text" value="{{ $customer['favorite_sport'] }}" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm text-slate-900">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md text-xs">
                            Save Profile Changes
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- DIGITAL QR GATE PASS MODAL POPUP -->
        <div x-show="showQrModal" x-cloak 
             class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
            
            <div @click.away="showQrModal = false" class="bg-white rounded-3xl p-8 max-w-sm w-full space-y-6 text-center border border-slate-200 shadow-2xl relative">
                <button @click="showQrModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 font-bold text-sm">✕</button>

                <div>
                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">Scan at Gate Barrier</span>
                    <h3 class="text-lg font-extrabold text-slate-900 mt-0.5" x-text="activeQrCourt"></h3>
                    <span class="text-xs font-mono font-bold text-slate-400" x-text="activeQrRef"></span>
                </div>

                <div class="bg-slate-900 text-white p-6 rounded-2xl border border-slate-800 space-y-3 flex flex-col items-center">
                    <div class="w-32 h-32 bg-white p-2.5 rounded-xl grid grid-cols-4 gap-1.5">
                        <div class="bg-slate-950"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-white"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-white"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-white"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-white"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-slate-950"></div>
                        <div class="bg-white"></div>
                        <div class="bg-slate-950"></div>
                    </div>
                    <span class="text-[10px] font-mono text-slate-400">AUTOMATED ENTRY PASS</span>
                </div>

                <button @click="showQrModal = false" class="w-full py-3 rounded-xl font-bold text-slate-900 bg-slate-100 hover:bg-slate-200 text-xs">
                    Close Pass
                </button>
            </div>

        </div>

    </section>

</x-layouts.app>
