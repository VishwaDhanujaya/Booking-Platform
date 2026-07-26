@php
    $t = \App\Services\TenantResolver::getActiveTenant();
@endphp
<x-layouts.app title="{{ $t['name'] }} | Multi-Tenant Court Engine by SLT Digital">

    <!-- FULLSCREEN HERO SECTION -->
    <section class="relative overflow-hidden bg-slate-950 text-white min-h-[calc(100vh-7rem)] flex items-center py-12 lg:py-0">
        <!-- Background Decorative Glows -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-brand-600/25 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-32 w-96 h-96 rounded-full bg-accent-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Hero Copy -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold bg-brand-500/20 text-brand-300 border border-brand-500/40 uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-accent-400 animate-pulse"></span>
                        <span>{{ $t['badge'] }}</span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white leading-tight">
                        {{ $t['hero_title'] }}
                    </h1>

                    <p class="text-lg text-slate-300 font-normal leading-relaxed max-w-2xl">
                        {{ $t['hero_subtitle'] }}
                    </p>

                    <!-- Hero CTA Buttons -->
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                        <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold text-slate-950 bg-white hover:bg-brand-50 shadow-xl shadow-brand-500/10 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Book a Court Now
                            <svg class="w-5 h-5 ml-2 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#courts" class="inline-flex items-center justify-center px-6 py-4 rounded-xl text-base font-semibold text-white bg-slate-900/90 hover:bg-slate-900 border border-slate-800 transition-colors">
                            Explore Facilities & Courts
                        </a>
                    </div>

                    <!-- Trust indicators -->
                    <div class="pt-6 flex flex-wrap items-center gap-6 text-xs text-slate-400">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>No Membership Required</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Free Cancellation (4h)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Instant QR Entry</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Quick Booking Card Widget -->
                <div class="lg:col-span-5">
                    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl relative">
                        
                        <h3 class="text-xl font-bold text-white mb-2">Quick Booking Finder</h3>
                        <p class="text-xs text-slate-400 mb-6">Select your sport and date to check instant open court slots.</p>

                        <form action="{{ route('booking.index') }}" method="GET" class="space-y-4">
                            <!-- Sport Category Selector with SVG Icons -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Select Sport</label>
                                <div class="grid grid-cols-2 gap-2.5">
                                    
                                    <!-- Tennis Radio -->
                                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border border-slate-700/80 bg-slate-800/80 cursor-pointer hover:border-brand-500 hover:bg-slate-800 transition-all group">
                                        <input type="radio" name="sport" value="tennis" checked class="sr-only peer">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 peer-checked:text-brand-400">
                                            <svg class="w-4 h-4 text-slate-400 peer-checked:text-brand-400 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <circle cx="12" cy="12" r="9" stroke-width="2" />
                                                <path stroke-linecap="round" stroke-width="1.8" d="M5.636 5.636a9 9 0 0112.728 0M5.636 18.364a9 9 0 0012.728 0" />
                                            </svg>
                                            <span>Tennis</span>
                                        </div>
                                        <div class="absolute inset-0 rounded-xl border-2 border-brand-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                    </label>

                                    <!-- Padel Radio -->
                                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border border-slate-700/80 bg-slate-800/80 cursor-pointer hover:border-brand-500 hover:bg-slate-800 transition-all group">
                                        <input type="radio" name="sport" value="padel" class="sr-only peer">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 peer-checked:text-brand-400">
                                            <svg class="w-4 h-4 text-slate-400 peer-checked:text-brand-400 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <rect x="7" y="3" width="10" height="12" rx="5" stroke-width="2" />
                                                <path stroke-linecap="round" stroke-width="2" d="M12 15v6M10 21h4" />
                                            </svg>
                                            <span>Padel</span>
                                        </div>
                                        <div class="absolute inset-0 rounded-xl border-2 border-brand-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                    </label>

                                    <!-- Badminton Radio -->
                                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border border-slate-700/80 bg-slate-800/80 cursor-pointer hover:border-brand-500 hover:bg-slate-800 transition-all group">
                                        <input type="radio" name="sport" value="badminton" class="sr-only peer">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 peer-checked:text-brand-400">
                                            <svg class="w-4 h-4 text-slate-400 peer-checked:text-brand-400 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19a2 2 0 100-4 2 2 0 000 4z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 15L6 5h12l-4 10" />
                                            </svg>
                                            <span>Badminton</span>
                                        </div>
                                        <div class="absolute inset-0 rounded-xl border-2 border-brand-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                    </label>

                                    <!-- Squash Radio -->
                                    <label class="relative flex items-center justify-center p-3.5 rounded-xl border border-slate-700/80 bg-slate-800/80 cursor-pointer hover:border-brand-500 hover:bg-slate-800 transition-all group">
                                        <input type="radio" name="sport" value="squash" class="sr-only peer">
                                        <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 peer-checked:text-brand-400">
                                            <svg class="w-4 h-4 text-slate-400 peer-checked:text-brand-400 group-hover:text-brand-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            <span>Squash</span>
                                        </div>
                                        <div class="absolute inset-0 rounded-xl border-2 border-brand-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                    </label>

                                </div>
                            </div>

                            <!-- Date Picker Input -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Booking Date</label>
                                <div class="relative">
                                    <input type="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 transition-colors">
                                </div>
                            </div>

                            <!-- Duration Selection -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Session Duration</label>
                                <select class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-brand-500 transition-colors">
                                    <option value="60">60 Minutes (1 Hour)</option>
                                    <option value="90">90 Minutes (1.5 Hours)</option>
                                    <option value="120" selected>120 Minutes (2 Hours - Recommended)</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-brand-600 hover:bg-brand-500 shadow-lg shadow-brand-600/30 transition-all hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2">
                                Find Available Slots
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TRUSTED BY / PARTNERS STRIP WITH SVG ICONS -->
    <section class="border-y border-slate-200/80 bg-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
                Official Venue Partner for Premier Sri Lankan Sports Organizations
            </p>
            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-14 opacity-80 transition-all">
                <div class="flex items-center gap-2 text-slate-700 font-extrabold text-sm tracking-wider">
                    <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs font-black">SLT</div>
                    SLT DIGITAL SERVICES
                </div>
                <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="1.8" d="M5.636 5.636a9 9 0 0112.728 0M5.636 18.364a9 9 0 0012.728 0"/></svg>
                    SRI LANKA TENNIS ASSOC.
                </div>
                <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="7" y="3" width="10" height="12" rx="5" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 15v6M10 21h4"/></svg>
                    CEYLON PADEL LEAGUE
                </div>
                <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                    <svg class="w-4 h-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    DUNLOP SPORTS LK
                </div>
            </div>
        </div>
    </section>

    <!-- OUR COURTS & SPORTS SECTION -->
    <section id="courts" class="py-20 sm:py-28 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-100 text-brand-700">
                    <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    World-Class Facilities
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Explore Our Professional Courts
                </h2>
                <p class="text-slate-600 text-base leading-relaxed">
                    Designed according to international BWF, ITF, and FIP federation specifications. All courts feature tournament LED lighting, high-grip synthetic surfaces, and air-conditioned changing lounges.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 xl:gap-8">
                
                <!-- Court 1: Tennis -->
                <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover overflow-hidden transition-all duration-300 flex flex-col">
                    <div class="relative h-52 overflow-hidden bg-slate-900">
                        <img src="/images/courts/tennis.png" alt="Tennis Court" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-white/10 shadow-sm">
                            Outdoor &bull; 4 Courts
                        </div>
                        <div class="absolute bottom-3 right-3 bg-slate-950/90 backdrop-blur-md border border-accent-400/40 text-accent-400 font-bold text-xs px-2.5 py-1 rounded-lg shadow-md">
                            From LKR 3,500<span class="text-[10px] font-normal text-slate-300">/hr</span>
                        </div>
                    </div>
                    <div class="p-6 grow flex flex-col justify-between space-y-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md uppercase tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="1.8" d="M5.636 5.636a9 9 0 0112.728 0M5.636 18.364a9 9 0 0012.728 0"/></svg>
                                Tennis Category
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                Championship Hard Courts
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Professional Plexicushion acrylic hard court surface with high-performance night floodlighting.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-medium text-slate-500">60 / 90 / 120 min slots</span>
                            <a href="{{ route('booking.index') }}?sport=tennis" class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-700">
                                Book Tennis
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 2: Padel -->
                <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover overflow-hidden transition-all duration-300 flex flex-col">
                    <div class="relative h-52 overflow-hidden bg-slate-900">
                        <img src="/images/courts/padel.png" alt="Padel Court" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-white/10 shadow-sm">
                            Indoor Covered &bull; 3 Courts
                        </div>
                        <div class="absolute bottom-3 right-3 bg-slate-950/90 backdrop-blur-md border border-accent-400/40 text-accent-400 font-bold text-xs px-2.5 py-1 rounded-lg shadow-md">
                            From LKR 4,500<span class="text-[10px] font-normal text-slate-300">/hr</span>
                        </div>
                    </div>
                    <div class="p-6 grow flex flex-col justify-between space-y-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md uppercase tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="7" y="3" width="10" height="12" rx="5" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 15v6M10 21h4"/></svg>
                                Padel Arenas
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                Panoramic Glass Padel Arenas
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                International FIP standard glass-wall enclosure with anti-injury monofilament synthetic turf.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-medium text-slate-500">Rackets Available</span>
                            <a href="{{ route('booking.index') }}?sport=padel" class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-700">
                                Book Padel
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 3: Badminton -->
                <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover overflow-hidden transition-all duration-300 flex flex-col">
                    <div class="relative h-52 overflow-hidden bg-slate-900">
                        <img src="/images/courts/badminton.png" alt="Badminton Court" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-white/10 shadow-sm">
                            Air-Conditioned &bull; 6 Courts
                        </div>
                        <div class="absolute bottom-3 right-3 bg-slate-950/90 backdrop-blur-md border border-accent-400/40 text-accent-400 font-bold text-xs px-2.5 py-1 rounded-lg shadow-md">
                            From LKR 2,500<span class="text-[10px] font-normal text-slate-300">/hr</span>
                        </div>
                    </div>
                    <div class="p-6 grow flex flex-col justify-between space-y-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md uppercase tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19a2 2 0 100-4 2 2 0 000 4z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 15L6 5h12l-4 10" /></svg>
                                Badminton Arena
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                BWF Synthetic Mat Courts
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Premium anti-slip green PVC mats over wooden subfloor. Anti-glare shadowless LED fixtures.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-medium text-slate-500">Singles & Doubles</span>
                            <a href="{{ route('booking.index') }}?sport=badminton" class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-700">
                                Book Badminton
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Court 4: Squash -->
                <div class="group bg-white rounded-2xl border border-slate-200/90 shadow-card hover:shadow-hover overflow-hidden transition-all duration-300 flex flex-col">
                    <div class="relative h-52 overflow-hidden bg-slate-900">
                        <img src="/images/courts/squash.png" alt="Squash Court" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <div class="absolute top-3 left-3 bg-slate-950/80 backdrop-blur-md text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-white/10 shadow-sm">
                            Glass-Backed &bull; 2 Courts
                        </div>
                        <div class="absolute bottom-3 right-3 bg-slate-950/90 backdrop-blur-md border border-accent-400/40 text-accent-400 font-bold text-xs px-2.5 py-1 rounded-lg shadow-md">
                            From LKR 2,800<span class="text-[10px] font-normal text-slate-300">/hr</span>
                        </div>
                    </div>
                    <div class="p-6 grow flex flex-col justify-between space-y-4">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-md uppercase tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                Squash Courts
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-900 group-hover:text-brand-600 transition-colors leading-snug">
                                WSF Hardwood Courts
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Sprung maple flooring and clear glass backwalls with spectator gallery seating.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-medium text-slate-500">WSF Approved</span>
                            <a href="{{ route('booking.index') }}?sport=squash" class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:text-brand-700">
                                Book Squash
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- WHY BOOK WITH US SECTION -->
    <section class="py-20 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">SaaS Powered Convenience</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Why Players Choose Colombo Courts</h2>
                <p class="text-slate-600 text-sm">Experience seamless booking with SLT Digital Services' cloud reservation platform.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Feature 1 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Instant Real-Time Lock</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        No double bookings or phone calls. See exact open slots by court and lock your session in under 30 seconds.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-accent-500 text-slate-950 flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Flexible Cancellation</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Plans change? Cancel or reschedule your booking online up to 4 hours before court time with 100% wallet refund.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Lighting & Equipment Add-ons</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Add night floodlighting, fresh ball tubes, or pro racket rentals directly during checkout in one click.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 text-brand-400 flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2a1 1 0 001-1v-5a1 1 0 00-1-1h-2M3 8h2a1 1 0 011 1v5a1 1 0 01-1 1H3m0 0v5a1 1 0 001 1h16a1 1 0 001-1v-5" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Digital Entry QR Code</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Receive instant digital pass on SMS/Email with calendar export (.ics) and contactless turnstile entry.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- PRICING & MEMBERSHIP TEASER SECTION -->
    <section class="py-20 sm:py-28 bg-slate-900 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-500/20 text-brand-300 border border-brand-500/30">
                    Transparent Rates & Memberships
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Simple, Flexible Court Pricing
                </h2>
                <p class="text-slate-400 text-sm">
                    Book as a casual guest or upgrade to a monthly membership for peak hour discounts and advance reservations.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                
                <!-- Tier 1: Casual Player -->
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-8 flex flex-col justify-between hover:border-slate-600 transition-all space-y-6">
                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pay As You Play</span>
                        <h3 class="text-2xl font-bold text-white">Casual Player</h3>
                        <p class="text-xs text-slate-400">Perfect for weekend matches & casual practice sessions.</p>
                        
                        <div class="pt-4 border-t border-slate-700">
                            <span class="text-3xl font-extrabold text-white">LKR 2,500</span>
                            <span class="text-xs text-slate-400">/ hour starting rate</span>
                        </div>

                        <ul class="space-y-3 pt-4 text-xs text-slate-300">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                3-day advance booking window
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Off-peak discounts available
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Digital QR pass included
                            </li>
                            <li class="flex items-center gap-2 text-slate-500">
                                <span>&bull; Equipment rental at regular fee</span>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('booking.index') }}" class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-slate-700 hover:bg-slate-600 text-center transition-colors">
                        Book as Casual Guest
                    </a>
                </div>

                <!-- Tier 2: Club Member (Featured) -->
                <div class="bg-linear-to-b from-brand-900 to-slate-900 border-2 border-brand-500 rounded-2xl p-8 flex flex-col justify-between shadow-2xl relative space-y-6">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-500 text-slate-950 font-black text-[11px] px-3.5 py-1 rounded-full uppercase tracking-wider shadow-md">
                        Most Popular
                    </div>

                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-300">Monthly Membership</span>
                        <h3 class="text-2xl font-bold text-white">Club Pass Member</h3>
                        <p class="text-xs text-brand-100/80">Designed for frequent players & tournament enthusiasts.</p>
                        
                        <div class="pt-4 border-t border-brand-800">
                            <span class="text-4xl font-black text-white">LKR 12,500</span>
                            <span class="text-xs text-brand-200">/ month</span>
                        </div>

                        <ul class="space-y-3 pt-4 text-xs text-white">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <strong>20% Discount</strong> on all court bookings
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                7-day priority booking window
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Free monthly racket & ball rentals
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Member lounge & locker access
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('booking.index') }}" class="w-full py-3.5 px-4 rounded-xl text-sm font-bold text-slate-950 bg-white hover:bg-brand-50 text-center shadow-lg transition-all hover:scale-[1.02]">
                        Get Started as Member &rarr;
                    </a>
                </div>

                <!-- Tier 3: Corporate & Teams -->
                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-8 flex flex-col justify-between hover:border-slate-600 transition-all space-y-6">
                    <div class="space-y-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Organizations & Clubs</span>
                        <h3 class="text-2xl font-bold text-white">Corporate & Teams</h3>
                        <p class="text-xs text-slate-400">Tailored multi-court reservations & corporate tournaments.</p>
                        
                        <div class="pt-4 border-t border-slate-700">
                            <span class="text-3xl font-extrabold text-white">Custom</span>
                            <span class="text-xs text-slate-400">/ volume pricing</span>
                        </div>

                        <ul class="space-y-3 pt-4 text-xs text-slate-300">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Multi-court simultaneous locking
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Dedicated tournament coordinator
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Invoicing & corporate billing
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('pricing') }}" class="w-full py-3.5 px-4 rounded-xl text-sm font-semibold text-white bg-slate-700 hover:bg-slate-600 text-center transition-colors">
                        Inquire Corporate Rates
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Player Experiences</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Loved by Colombo's Tennis & Padel Community</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-card space-y-4">
                    <div class="flex items-center gap-1 text-amber-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed italic">
                        "Booking padel courts used to take 5 phone calls to check availability. The Colombo Courts Club online system lets me lock a 2-hour slot in 20 seconds!"
                    </p>
                    <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-sm">
                            KP
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Kavinda Perera</h4>
                            <span class="text-[11px] text-slate-500">Padel League Member</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-card space-y-4">
                    <div class="flex items-center gap-1 text-amber-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed italic">
                        "The night lighting and instant QR ticket make evening tennis matches effortless. No waiting in line, just scan and hit the court."
                    </p>
                    <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-accent-100 text-accent-700 font-bold flex items-center justify-center text-sm">
                            AS
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Ananya Senanayake</h4>
                            <span class="text-[11px] text-slate-500">Amateur Tennis Tour</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-card space-y-4">
                    <div class="flex items-center gap-1 text-amber-400">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed italic">
                        "We run weekly corporate badminton matches here. The court quality is superb and the digital booking system is top tier."
                    </p>
                    <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-700 font-bold flex items-center justify-center text-sm">
                            RD
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">Rohan De Silva</h4>
                            <span class="text-[11px] text-slate-500">Corporate Team Captain</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- BOTTOM CTA BANNER -->
    <section class="bg-linear-to-r from-brand-700 to-brand-900 py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl font-extrabold tracking-tight">Ready to Play? Book Your Court Today</h2>
            <p class="text-brand-100 text-sm max-w-xl mx-auto">
                Join hundreds of local athletes playing at Colombo Courts Club. Instant booking, zero hassle.
            </p>
            <div>
                <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-xl text-base font-bold text-slate-950 bg-white hover:bg-brand-50 shadow-xl transition-transform hover:scale-[1.02]">
                    Book Your Court Slot Now &rarr;
                </a>
            </div>
        </div>
    </section>

</x-layouts.app>
