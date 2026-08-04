<x-layouts.parent title="BookFlow | Universal Booking & Space Management System by SLTDS">

    <!-- HERO SECTION (Above the fold fit) -->
    <section class="relative overflow-hidden bg-linear-to-br from-slate-900 via-sltds-endeavour to-slate-950 text-white min-h-[calc(100vh-4rem)] flex items-center py-6 lg:py-0 border-b border-sltds-sky/30">
        <!-- Ambient Brand Accent Lighting -->
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-sltds-sky/20 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 -left-32 w-96 h-96 rounded-full bg-sltds-green/15 blur-[100px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Hero Left Copy -->
                <div class="lg:col-span-7 space-y-6">

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.12]">
                        Booking Software for <span class="bg-linear-to-r from-sltds-sky via-white to-sltds-green bg-clip-text text-transparent">Sports Courts & Venues.</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-200 leading-relaxed max-w-2xl font-normal">
                        Give your players a fast online booking experience while giving your staff complete control over court availability, member passes, peak pricing, and automated lighting.
                    </p>

                    <!-- Hero Action CTAs -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-1">
                        <a href="{{ route('parent.demo') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-xl text-sm font-extrabold text-slate-950 bg-white hover:bg-slate-100 shadow-xl shadow-white/10 transition-all hover:scale-[1.02]">
                            Request a Free Demo
                            <svg class="w-4 h-4 ml-2 text-sltds-endeavour" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="{{ route('parent.customers') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl text-sm font-bold text-white bg-slate-900/80 hover:bg-slate-900 border border-sltds-sky/40 transition-colors">
                            View Live Customer Sites
                        </a>
                    </div>

                    <!-- Trust Metrics Bar -->
                    <div class="pt-6 border-t border-white/15 grid grid-cols-2 sm:grid-cols-4 gap-4 text-white">
                        <div>
                            <div class="text-2xl font-black text-white">50+</div>
                            <div class="text-xs font-medium text-slate-300">Live Facilities</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-sltds-sky">15,000+</div>
                            <div class="text-xs font-medium text-slate-300">Bookings Handled</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-sltds-green">99.9%</div>
                            <div class="text-xs font-medium text-slate-300">Uptime</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-amber-300">4.9 / 5</div>
                            <div class="text-xs font-medium text-slate-300">Owner Rating</div>
                        </div>
                    </div>

                </div>

                <!-- Hero Right Real Image & Live Availability Widget -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md lg:max-w-none space-y-3">
                        
                        <!-- Main Hero Real Photo Container -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-white/20">
                            <img src="/images/hero_sports_court.png" alt="Indoor Badminton & Sports Court Facility" class="w-full h-64 sm:h-72 object-cover">
                            <div class="absolute inset-0 bg-linear-to-t from-slate-950/90 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-5 right-5 text-white flex items-center justify-between">
                                <div>
                                    <div class="text-base font-bold text-white">Colombo Courts Club</div>
                                    <div class="text-xs text-slate-300">Badminton & Tennis Complex</div>
                                </div>
                                <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase bg-sltds-green text-white shadow-xs">
                                    Verified Live
                                </span>
                            </div>
                        </div>

                        <!-- Live Availability Preview Widget -->
                        <div class="bg-white text-slate-900 border border-slate-200 rounded-2xl p-4 shadow-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-sltds-endeavour">Live Court Day Matrix</span>
                                <span class="text-[10px] font-bold text-sltds-green bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-sltds-green animate-pulse"></span>
                                    Slots Open
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">05:00 PM<br><span class="text-[10px] text-emerald-600">Available</span></div>
                                <div class="p-2 rounded-xl bg-rose-50 text-rose-800 font-bold border border-rose-200">06:00 PM<br><span class="text-[10px] text-rose-600">Booked</span></div>
                                <div class="p-2 rounded-xl bg-blue-50 text-sltds-endeavour font-bold border border-blue-200">07:00 PM<br><span class="text-[10px] text-sltds-endeavour">Peak $40</span></div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SUB-HERO SECTION 1: CAPABILITIES STRIP (WITH SVG ICONS, NO EMOJIS) -->
    <section class="py-6 bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center text-xs font-semibold">
                <!-- Day Grid Icon -->
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center gap-2.5 hover:bg-white/10 transition-colors">
                    <span class="w-8 h-8 rounded-xl bg-sltds-endeavour flex items-center justify-center text-white shrink-0 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span class="text-slate-200">Multi-Court Day Matrix</span>
                </div>
                <!-- Pass & Credit Icon -->
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center gap-2.5 hover:bg-white/10 transition-colors">
                    <span class="w-8 h-8 rounded-xl bg-sltds-sky flex items-center justify-center text-white shrink-0 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <span class="text-slate-200">Member Passes & Credits</span>
                </div>
                <!-- Pricing Rules Icon -->
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center gap-2.5 hover:bg-white/10 transition-colors">
                    <span class="w-8 h-8 rounded-xl bg-sltds-green flex items-center justify-center text-white shrink-0 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </span>
                    <span class="text-slate-200">Peak Hour Pricing Rules</span>
                </div>
                <!-- Automated Lighting Icon -->
                <div class="p-3 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center gap-2.5 hover:bg-white/10 transition-colors">
                    <span class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center text-white shrink-0 shadow-md">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span class="text-slate-200">Automated Floodlights</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SUB-HERO SECTION 2: HOW IT WORKS -->
    <section class="py-20 bg-linear-to-b from-white via-slate-50 to-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <div>
                    <span class="inline-block px-4.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 mb-4 shadow-xs">
                        Operational Setup
                    </span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">How SLT Digital Services Fits Your Facility</h2>
                <p class="text-slate-600 text-sm">Launch a branded booking site for your venue in 3 simple, guided steps.</p>
            </div>

            <!-- Step Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                
                <!-- Step 1 -->
                <div class="bg-white border-2 border-slate-200/90 rounded-3xl p-8 space-y-5 hover:border-sltds-endeavour hover:shadow-xl transition-all group relative">
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-tr from-sltds-endeavour to-sltds-sky text-white flex items-center justify-center font-black text-xl shadow-lg shadow-sltds-endeavour/20 group-hover:scale-110 transition-transform">
                            01
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-50 text-sltds-endeavour border border-blue-200">Branding</span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-sltds-endeavour transition-colors">Configure Venue Branding</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Upload your logo, pick your custom brand color palette, set your tagline, location address, and bind your custom venue domain.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center text-xs font-bold text-sltds-endeavour gap-1">
                        <span>Logo & Colors Setup</span>
                        <svg class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white border-2 border-slate-200/90 rounded-3xl p-8 space-y-5 hover:border-sltds-sky hover:shadow-xl transition-all group relative">
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-tr from-sltds-sky to-cyan-400 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-sltds-sky/20 group-hover:scale-110 transition-transform">
                            02
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-cyan-50 text-sltds-sky border border-cyan-200">Schedules</span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-sltds-sky transition-colors">Add Courts & Hourly Rates</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Set up courts or studio tracks, define opening hours, peak hour multipliers, buffer times, and 10-visit member passes.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center text-xs font-bold text-sltds-sky gap-1">
                        <span>Courts & Pricing Rules</span>
                        <svg class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white border-2 border-slate-200/90 rounded-3xl p-8 space-y-5 hover:border-sltds-green hover:shadow-xl transition-all group relative">
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl bg-linear-to-tr from-sltds-green to-emerald-400 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-sltds-green/20 group-hover:scale-110 transition-transform">
                            03
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-sltds-green border border-emerald-200">Automate</span>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-sltds-green transition-colors">Accept Bookings & Lighting</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Players reserve slots on your day grid, receive automated SMS confirmations, and court lights toggle on automatically.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center text-xs font-bold text-sltds-green gap-1">
                        <span>Live Bookings & Relays</span>
                        <svg class="w-4 h-4 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SUB-HERO SECTION 3: REAL PHOTO VISUAL FEATURE SHOWCASE -->
    <section class="py-20 bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <div>
                    <span class="inline-block px-4.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-white/10 text-sltds-sky border border-white/20 mb-4 shadow-xs">
                        Product Spotlight
                    </span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">Built for Multi-Court Facilities & Studios</h2>
                <p class="text-slate-300 text-sm">Experience the interactive multi-court day grid and automated waitlist queues in action.</p>
            </div>

            <!-- Row 1: Padel & Sports Complex Showcase -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center bg-slate-950/80 border border-slate-800 rounded-3xl p-6 lg:p-10 shadow-2xl">
                <div class="lg:col-span-6 rounded-2xl overflow-hidden border border-slate-700 shadow-xl group">
                    <img src="/images/padel_tennis_arena.png" alt="Outdoor Padel and Tennis Arena" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="lg:col-span-6 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase bg-sltds-endeavour text-white">
                        <span class="w-2 h-2 rounded-full bg-sltds-sky"></span>
                        Racquet Sports & Arenas
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">Interactive Multi-Court Day Grid</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Display all your courts side-by-side as rows with hourly time slot columns. Players instantly see which courts are open, check hourly pricing under each header, and select consecutive slots in seconds.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold text-slate-200">
                        <div class="flex items-center gap-2 bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <svg class="w-4 h-4 text-sltds-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> 7-Day Date Tab Strip
                        </div>
                        <div class="flex items-center gap-2 bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <svg class="w-4 h-4 text-sltds-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Floodlight Relays
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('parent.features') }}" class="inline-flex items-center text-xs font-bold text-sltds-sky hover:underline">
                            Explore Racquet Solutions &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <!-- Row 2: Gym & Studio Spaces Showcase -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center bg-slate-950/80 border border-slate-800 rounded-3xl p-6 lg:p-10 shadow-2xl">
                <div class="lg:col-span-6 order-2 lg:order-1 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase bg-sltds-green text-slate-950">
                        <span class="w-2 h-2 rounded-full bg-slate-950"></span>
                        Gyms & Fitness Studios
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">Capacity Control & Waitlist Queues</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        For group fitness classes, reformer studios, and gym floor slots. When a session reaches maximum capacity, players can join an automated waitlist and receive notifications if a spot opens up.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold text-slate-200">
                        <div class="flex items-center gap-2 bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <svg class="w-4 h-4 text-sltds-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Member Credit Ledger
                        </div>
                        <div class="flex items-center gap-2 bg-white/5 p-2.5 rounded-xl border border-white/10">
                            <svg class="w-4 h-4 text-sltds-green" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> PDF Tax Receipts
                        </div>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('parent.features') }}" class="inline-flex items-center text-xs font-bold text-sltds-green hover:underline">
                            Explore Studio Solutions &rarr;
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-6 order-1 lg:order-2 rounded-2xl overflow-hidden border border-slate-700 shadow-xl group">
                    <img src="/images/gym_fitness_studio.png" alt="Fitness Gym and Yoga Group Studio" class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            </div>

        </div>
    </section>

    <!-- SUB-HERO SECTION 4: LIVE CUSTOMER DIRECTORY PREVIEW -->
    <section class="py-20 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
                <div>
                    <span class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-blue-200 mb-3 shadow-xs">
                        Directory
                    </span>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight leading-tight">Active Venues Operating on SLTDS</h2>
                </div>
                <a href="{{ route('parent.customers') }}" class="inline-flex items-center text-xs font-extrabold text-sltds-endeavour hover:text-sltds-sky bg-blue-50 hover:bg-blue-100 px-4 py-2.5 rounded-xl border border-blue-200 transition-all">
                    Browse All Customer Sites &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredTenants as $t)
                    <div class="bg-slate-50 border-2 border-slate-200/80 rounded-2xl p-6 flex flex-col justify-between hover:border-sltds-endeavour hover:shadow-xl transition-all group">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white text-xl shadow-md group-hover:scale-105 transition-transform" style="background-color: {{ $t->brand_color ?? '#0056A2' }}">
                                    {{ strtoupper(substr($t->name, 0, 1)) }}
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-white text-slate-700 border border-slate-200">
                                    {{ $t->category }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 group-hover:text-sltds-endeavour transition-colors">{{ $t->name }}</h4>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $t->tagline ?? $t->description }}</p>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-200 mt-5">
                            <a href="{{ \App\Services\TenantResolver::tenantUrl('booking.index', [], $t) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-sltds-endeavour hover:bg-sltds-sky transition-colors shadow-xs">
                                Launch Customer Site &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- PROMINENT DEMO REQUEST CTA BANNER -->
    <section class="py-16 bg-linear-to-r from-sltds-endeavour via-blue-900 to-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <h2 class="text-3xl sm:text-4xl font-black text-white max-w-2xl mx-auto">
                Ready to setup online court booking for your venue?
            </h2>
            <p class="text-slate-200 text-sm max-w-xl mx-auto">
                Schedule a 15-minute platform walkthrough with an SLT Digital Services team member.
            </p>
            <div class="pt-2 flex items-center justify-center gap-4">
                <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-8 py-4 rounded-xl text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-xl transition-all hover:scale-105">
                    Request a Demo
                </a>
            </div>
        </div>
    </section>

</x-layouts.parent>
