<x-layouts.app title="Book a Court | Colombo Courts Club">

    <!-- Page Header & Filter Banner -->
    <section class="bg-slate-900 text-white py-10 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-semibold text-brand-400 uppercase tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span>
                        Real-Time Reservation Engine
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white">Book a Court</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">Select your sport category, choose a court, and pick open hourly time slots.</p>
                </div>

                <!-- View Mode Toggle -->
                <div class="flex items-center gap-3">
                    <span class="text-xs font-medium text-slate-400">View Mode:</span>
                    <div class="inline-flex p-1 rounded-xl bg-slate-800 border border-slate-700">
                        <a href="{{ route('booking.index', array_merge(request()->query(), ['view' => 'single'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('view', 'single') === 'single' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                            Single Court
                        </a>
                        <a href="{{ route('booking.index', array_merge(request()->query(), ['view' => 'matrix'])) }}" 
                           class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('view') === 'matrix' ? 'bg-brand-600 text-white shadow-sm' : 'text-slate-400 hover:text-white' }}">
                            All Courts Matrix
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sport Category Filter Tabs -->
            <div class="flex items-center justify-between gap-4 border-t border-slate-800 pt-6 flex-wrap">
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                    <a href="{{ route('booking.index', ['sport' => 'all', 'date' => request('date'), 'type' => request('type')]) }}" 
                       class="px-4 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ $selectedSportSlug === 'all' ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700/60' }}">
                        <span>All Sports</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 text-white">{{ $courts->count() }}</span>
                    </a>

                    @foreach($categories as $cat)
                    <a href="{{ route('booking.index', ['sport' => $cat->slug, 'date' => request('date'), 'type' => request('type')]) }}" 
                       class="px-4 py-2.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ $selectedSportSlug === $cat->slug ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700/60' }}">
                        @if($cat->slug === 'tennis')
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="1.8" d="M5.636 5.636a9 9 0 0112.728 0M5.636 18.364a9 9 0 0012.728 0"/></svg>
                        @elseif($cat->slug === 'padel')
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="7" y="3" width="10" height="12" rx="5" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 15v6M10 21h4"/></svg>
                        @elseif($cat->slug === 'badminton')
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19a2 2 0 100-4 2 2 0 000 4z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 15L6 5h12l-4 10" /></svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        @endif
                        <span>{{ $cat->name }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] {{ $selectedSportSlug === $cat->slug ? 'bg-white/20 text-white' : 'bg-slate-700 text-slate-300' }}">{{ $cat->courts_count }}</span>
                    </a>
                    @endforeach
                </div>

                <!-- Environment Filter Pills -->
                <div class="flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 font-medium mr-1 hidden sm:inline">Type:</span>
                    <a href="{{ route('booking.index', ['sport' => request('sport'), 'date' => request('date'), 'type' => 'all']) }}" 
                       class="px-2.5 py-1 rounded-md font-semibold transition-all {{ $courtTypeFilter === 'all' ? 'bg-slate-700 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                        All
                    </a>
                    <a href="{{ route('booking.index', ['sport' => request('sport'), 'date' => request('date'), 'type' => 'indoor']) }}" 
                       class="px-2.5 py-1 rounded-md font-semibold transition-all {{ $courtTypeFilter === 'indoor' ? 'bg-slate-700 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                        Indoor
                    </a>
                    <a href="{{ route('booking.index', ['sport' => request('sport'), 'date' => request('date'), 'type' => 'outdoor']) }}" 
                       class="px-2.5 py-1 rounded-md font-semibold transition-all {{ $courtTypeFilter === 'outdoor' ? 'bg-slate-700 text-white font-bold' : 'text-slate-400 hover:text-white' }}">
                        Outdoor
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Schedule & Calendar Section -->
    <section x-data="{ 
        selectedSlots: [],
        totalPrice: 0,
        courtName: '{{ $selectedCourt ? $selectedCourt->name : '' }}',
        toggleSlot(slotId, price) {
            let index = this.selectedSlots.indexOf(slotId);
            if (index > -1) {
                this.selectedSlots.splice(index, 1);
                this.totalPrice -= price;
            } else {
                this.selectedSlots.push(slotId);
                this.totalPrice += price;
            }
        }
    }" class="py-10 bg-slate-50 min-h-screen pb-36">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- 1. Interactive 7-Day Date Selector Ribbon -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-card space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Select Booking Date
                    </h3>
                    <span class="text-xs font-semibold text-brand-600 hidden sm:inline">7-Day Live Availability Window</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                    @foreach($weekDates as $wd)
                    <a href="{{ route('booking.index', array_merge(request()->query(), ['date' => $wd['date_str']])) }}" 
                       class="p-3.5 rounded-xl border text-center transition-all flex flex-col items-center justify-between gap-1 group relative {{ $wd['is_selected'] ? 'bg-brand-600 border-brand-600 text-white shadow-md shadow-brand-600/20 scale-[1.02]' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-800' }}">
                        
                        <span class="text-[10px] font-bold uppercase tracking-wider {{ $wd['is_selected'] ? 'text-brand-100' : 'text-slate-500 group-hover:text-brand-600' }}">
                            {{ $wd['day_name'] }}
                            @if($wd['is_today'])
                                &bull; Today
                            @endif
                        </span>

                        <span class="text-xl font-extrabold tracking-tight {{ $wd['is_selected'] ? 'text-white' : 'text-slate-900' }}">
                            {{ $wd['day_number'] }} {{ $wd['month_name'] }}
                        </span>

                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ $wd['is_selected'] ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-700' }}">
                            {{ $wd['available_count'] }} open
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>

            @if(request('view') === 'matrix')
                <!-- 2. ALL COURTS SCHEDULE MATRIX VIEW (World-Class Timeline Grid) -->
                <div class="space-y-6">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-card">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Facility Master Schedule</span>
                            <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">All Courts Availability Matrix</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Schedule date: <strong class="text-slate-800">{{ $selectedDate->format('l, F j, Y') }}</strong></p>
                        </div>

                        <!-- Legend Bar (Single row layout) -->
                        <div class="flex items-center gap-3 sm:gap-4 text-xs font-bold text-slate-600 whitespace-nowrap overflow-x-auto pb-1 shrink-0 bg-slate-50 px-4 py-2 rounded-xl border border-slate-200">
                            <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-3 h-3 rounded-md bg-emerald-500 shrink-0"></span> Available</span>
                            <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-3 h-3 rounded-md bg-amber-500 shrink-0"></span> Peak Rate</span>
                            <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-3 h-3 rounded-md bg-slate-300 shrink-0"></span> Booked</span>
                            <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-3 h-3 rounded-md bg-brand-600 shrink-0"></span> Selected</span>
                        </div>
                    </div>

                    <!-- Matrix Scrollable Table Container -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-card overflow-x-auto relative">
                        <table class="w-full text-left border-collapse" style="min-width: 850px;">
                            <thead>
                                <tr class="bg-slate-900 text-white text-xs border-b border-slate-800">
                                    <!-- Sticky Left Header -->
                                    <th class="p-3.5 font-bold sticky left-0 bg-slate-900 z-20 w-64 shadow-md">
                                        Court & Facility Info
                                    </th>
                                    <th class="p-3.5 font-bold">
                                        Hourly Timeline (06:00 – 22:00) &bull; Click to Select Slot
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/80 text-xs">
                                @foreach($courts as $court)
                                <tr class="hover:bg-slate-50/90 transition-colors group">
                                    
                                    <!-- Sticky Left Court Info Column -->
                                    <td class="p-3.5 font-bold text-slate-900 sticky left-0 bg-white group-hover:bg-slate-50/90 z-10 shadow-xs border-r border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $court->image_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shrink-0 shadow-xs" alt="" />
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ $court->name }}</span>
                                                    <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60 uppercase">
                                                        {{ $court->sportCategory->name }}
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-slate-500 font-medium">
                                                    <strong class="text-slate-800">LKR {{ number_format($court->hourly_rate) }}</strong>/hr 
                                                    <span class="text-amber-600 font-semibold">(Peak: {{ number_format($court->peak_hourly_rate) }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Horizontal Timeline Slots Grid (Spacious Single Row) -->
                                    <td class="p-3.5">
                                        <div class="flex items-center gap-1.5 overflow-x-auto py-1">
                                            @if(isset($allCourtsTimeSlots[$court->id]))
                                                @foreach($allCourtsTimeSlots[$court->id] as $slot)
                                                    @if($slot->status === 'available')
                                                        <button type="button" 
                                                                @click="toggleSlot({{ $slot->id }}, {{ $slot->price }})"
                                                                :class="selectedSlots.includes({{ $slot->id }}) ? 'bg-brand-600 text-white border-brand-600 ring-2 ring-brand-500 ring-offset-1 scale-[1.05] shadow-xs' : '{{ $slot->is_peak ? 'bg-amber-50 text-amber-950 border-amber-300 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-950 border-emerald-300 hover:bg-emerald-100' }}'"
                                                                class="px-2.5 py-1.5 rounded-lg text-[11px] font-bold border transition-all shrink-0 flex items-center gap-1">
                                                            @if($slot->is_peak)
                                                                <svg class="w-3 h-3 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                            @endif
                                                            <span>{{ substr($slot->start_time, 0, 5) }}</span>
                                                            <template x-if="selectedSlots.includes({{ $slot->id }})">
                                                                <svg class="w-3 h-3 text-white ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                            </template>
                                                        </button>
                                                    @else
                                                        <span class="px-2.5 py-1.5 rounded-lg text-[11px] font-semibold bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed shrink-0 line-through" title="Booked: {{ $slot->booked_by_name }}">
                                                            {{ substr($slot->start_time, 0, 5) }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            @else
                <!-- 3. SINGLE COURT INTERACTIVE SCHEDULE CALENDAR -->
                @if($selectedCourt)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left Sidebar: Court Selection & Details Card -->
                    <div class="lg:col-span-4 space-y-6">
                        
                        <!-- Court Selection List -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-card space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Available Courts ({{ $courts->count() }})</h3>
                                <span class="text-[10px] text-brand-600 font-bold uppercase">Click to view schedule</span>
                            </div>
                            
                            <div class="space-y-2.5">
                                @foreach($courts as $crt)
                                <a href="{{ route('booking.index', ['court_id' => $crt->id, 'date' => $selectedDate->toDateString(), 'sport' => $selectedSportSlug, 'type' => request('type')]) }}" 
                                   class="p-3.5 rounded-xl border flex items-center justify-between transition-all group {{ $selectedCourt->id === $crt->id ? 'bg-brand-50 border-brand-500 shadow-xs ring-1 ring-brand-500' : 'bg-slate-50 hover:bg-slate-100 border-slate-200/80' }}">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $crt->image_url }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200" alt="" />
                                        <div>
                                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-brand-600 transition-colors">{{ $crt->name }}</h4>
                                            <span class="text-[10px] text-slate-500 font-medium capitalize">{{ $crt->type }} &bull; {{ $crt->surface_type }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-xs font-bold text-slate-900">LKR {{ number_format($crt->hourly_rate) }}</span>
                                        <span class="text-[10px] text-slate-400">/ hour</span>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Selected Court Details Feature Box -->
                        <div class="bg-slate-900 text-white rounded-2xl p-6 border border-slate-800 shadow-card space-y-4">
                            <div class="relative h-44 rounded-xl overflow-hidden bg-slate-800">
                                <img src="{{ $selectedCourt->image_url }}" class="w-full h-full object-cover" alt="" />
                                <div class="absolute top-2 left-2 bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wider">
                                    {{ $selectedCourt->type }}
                                </div>
                                <div class="absolute bottom-2 right-2 bg-brand-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                    {{ $selectedCourt->surface_type }}
                                </div>
                            </div>

                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-400">{{ $selectedCourt->sportCategory->name }} Category</span>
                                <h3 class="text-lg font-bold text-white leading-tight mt-0.5">{{ $selectedCourt->name }}</h3>
                                <p class="text-xs text-slate-400 mt-2 leading-relaxed">{{ $selectedCourt->description }}</p>
                            </div>

                            <div class="pt-4 border-t border-slate-800 grid grid-cols-2 gap-3 text-xs">
                                <div>
                                    <span class="block text-slate-400 text-[10px] uppercase font-semibold">Off-Peak Rate</span>
                                    <strong class="text-white font-bold text-sm">LKR {{ number_format($selectedCourt->hourly_rate) }}/hr</strong>
                                </div>
                                <div>
                                    <span class="block text-amber-400 text-[10px] uppercase font-semibold">Peak Rate (16:00+)</span>
                                    <strong class="text-amber-400 font-bold text-sm">LKR {{ number_format($selectedCourt->peak_hourly_rate) }}/hr</strong>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Interactive Time Slot Calendar Grid -->
                    <div class="lg:col-span-8 space-y-6">
                        
                        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            
                            <!-- Calendar Header -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                                <div>
                                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Hourly Timetable</span>
                                    <h2 class="text-xl font-extrabold text-slate-900 mt-0.5">
                                        {{ $selectedCourt->name }} &bull; {{ $selectedDate->format('l, M j, Y') }}
                                    </h2>
                                </div>

                                <!-- Slot Status Legend (One single row layout) -->
                                <div class="flex items-center gap-3 sm:gap-4 text-[11px] font-bold text-slate-600 whitespace-nowrap overflow-x-auto pb-1 shrink-0">
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span> Available</span>
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span> Peak Rate</span>
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-slate-300 shrink-0"></span> Booked</span>
                                    <span class="inline-flex items-center gap-1.5 whitespace-nowrap"><span class="w-2.5 h-2.5 rounded-full bg-brand-600 shrink-0"></span> Selected</span>
                                </div>
                            </div>

                            <!-- Time Slots Grid -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5">
                                @foreach($timeSlots as $slot)
                                    @if($slot->status === 'available')
                                        <!-- Available Time Slot (Clickable) -->
                                        <button type="button" 
                                                @click="toggleSlot({{ $slot->id }}, {{ $slot->price }})"
                                                :class="selectedSlots.includes({{ $slot->id }}) ? 'bg-brand-600 text-white border-brand-600 ring-2 ring-brand-500 ring-offset-2 scale-[1.02] shadow-md' : '{{ $slot->is_peak ? 'bg-amber-50/80 hover:bg-amber-100 border-amber-200 text-amber-950' : 'bg-emerald-50/80 hover:bg-emerald-100 border-emerald-200 text-emerald-950' }}'"
                                                class="p-3.5 sm:p-4 rounded-xl border text-left transition-all duration-200 relative group flex flex-col justify-between min-h-24">
                                            
                                            <div class="flex items-center justify-between w-full">
                                                <span class="text-xs sm:text-sm font-extrabold tracking-tight">
                                                    {{ substr($slot->start_time, 0, 5) }} – {{ substr($slot->end_time, 0, 5) }}
                                                </span>
                                                <div x-show="selectedSlots.includes({{ $slot->id }})" x-cloak class="w-4 h-4 rounded-full bg-white text-brand-600 flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between w-full mt-2 pt-2 border-t" :class="selectedSlots.includes({{ $slot->id }}) ? 'border-brand-500/40 text-brand-100' : 'border-slate-200/60'">
                                                <span class="text-[10px] font-bold uppercase tracking-wider truncate flex items-center gap-1">
                                                    @if($slot->is_peak)
                                                        <svg class="w-3 h-3 text-amber-600 inline shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                        Peak Rate
                                                    @else
                                                        Off-Peak
                                                    @endif
                                                </span>
                                                <strong class="text-xs font-black shrink-0">LKR {{ number_format($slot->price) }}</strong>
                                            </div>
                                        </button>
                                    @else
                                        <!-- Booked / Unavailable Time Slot -->
                                        <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200 bg-slate-100/90 text-slate-400 text-left flex flex-col justify-between min-h-24 cursor-not-allowed opacity-85">
                                            <div class="flex items-center justify-between w-full">
                                                <span class="text-xs sm:text-sm font-semibold line-through">
                                                    {{ substr($slot->start_time, 0, 5) }} – {{ substr($slot->end_time, 0, 5) }}
                                                </span>
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-slate-200 text-slate-600 uppercase">
                                                    Booked
                                                </span>
                                            </div>
                                            <div class="mt-2 pt-2 border-t border-slate-200 text-[10px] text-slate-500 font-medium truncate">
                                                {{ $slot->booked_by_name ?? 'Reserved' }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Empty Slots Fallback -->
                            @if($timeSlots->isEmpty())
                                <div class="p-12 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300 space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center mx-auto text-xl font-bold">!</div>
                                    <h3 class="text-sm font-bold text-slate-900">No Time Slots Available For This Filter</h3>
                                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Please pick another date from the 7-day strip or clear sport filters.</p>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>
                @endif
            @endif

        </div>

        <!-- Sticky Floating Booking Action Drawer (Appears when 1+ slots selected) -->
        <div x-show="selectedSlots.length > 0" 
             x-cloak 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="fixed bottom-0 inset-x-0 z-50 bg-slate-900 text-white border-t border-slate-800 py-4 px-4 sm:px-8 shadow-2xl backdrop-blur-xl">
            
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-brand-600 text-white font-extrabold flex flex-col items-center justify-center text-sm shadow-md">
                        <span x-text="selectedSlots.length"></span>
                        <span class="text-[9px] font-semibold uppercase">Slot(s)</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold text-white" x-text="courtName"></h4>
                            <span class="text-xs text-brand-300">&bull; {{ $selectedDate->format('M j, Y') }}</span>
                        </div>
                        <p class="text-xs text-slate-400">Selected <strong class="text-white" x-text="selectedSlots.length"></strong> hourly slot(s) for booking.</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end">
                    <div class="text-right">
                        <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Price</span>
                        <span class="text-2xl font-black text-accent-400">LKR <span x-text="totalPrice.toLocaleString()"></span></span>
                    </div>

                    <a :href="'{{ route('booking.checkout') }}?court_id={{ $selectedCourt ? $selectedCourt->id : 1 }}&date={{ $selectedDate->toDateString() }}&slots=' + selectedSlots.join(',')" 
                       class="px-6 py-3.5 rounded-xl font-bold text-slate-950 bg-white hover:bg-brand-50 shadow-xl transition-all hover:scale-[1.02] active:scale-[0.98] text-sm flex items-center gap-2">
                        Continue to Booking Details
                        <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>

    </section>

</x-layouts.app>
