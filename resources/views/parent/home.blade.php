<x-layouts.parent title="BookFlow | Universal Booking & Space Management Platform by SLTDS">

    <!-- SECTION 1: VADEL-INSPIRED FULL-SCREEN HERO CANVAS (100% SINGLE VIEWPORT FIT ON LOAD & FLUSH TOP MERGE) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50 h-[calc(100vh-4rem)] overflow-hidden">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden h-full flex flex-col justify-center items-center p-6 sm:p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white">
            
            <!-- High-Res VADEL Style Sports Court Background Image -->
            <img src="/images/vadel_hero_court.png" alt="Indoor Sports Court Facility" class="absolute inset-0 w-full h-full object-cover z-0 opacity-80 scale-105">
            <div class="absolute inset-0 bg-linear-to-b from-slate-950/50 via-slate-950/20 to-slate-950/70 z-0"></div>

            <!-- Center Display Title & Tagline (Scaled for Single Viewport Fit) -->
            <div class="relative z-10 my-auto text-center space-y-4 sm:space-y-6 max-w-5xl mx-auto px-4">

                <h1 class="text-5xl sm:text-7xl lg:text-8xl xl:text-[9rem] font-black italic tracking-tighter text-white uppercase drop-shadow-[0_10px_35px_rgba(0,0,0,0.8)] leading-none">
                    BOOKFLOW
                </h1>

                <p class="text-sm sm:text-lg lg:text-xl text-slate-200 max-w-2xl mx-auto font-normal leading-relaxed tracking-wide drop-shadow-md">
                    The all-in-one operating system for sports venues. Automate court bookings, manage member passes, and scale your revenue with zero hassle.
                </p>

                <div class="pt-2 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                    <a href="{{ route('tenant.register') }}" class="px-8 py-3.5 rounded-full text-xs sm:text-sm font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_10px_30px_-5px_rgba(255,255,255,0.5)] transition-all hover:scale-105">
                        Register Venue &rarr;
                    </a>
                    <!-- Liquid Glass CTA Button -->
                    <a href="{{ route('parent.features') }}" class="px-8 py-3.5 rounded-full text-xs sm:text-sm font-black text-white bg-white/15 hover:bg-white/25 backdrop-blur-2xl border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_-5px_rgba(0,0,0,0.4)] transition-all hover:scale-105">
                        Explore Features
                    </a>
                </div>
            </div>

        </section>
    </div>

    <!-- SECTION 2: VADEL-INSPIRED DARK STATEMENT BANNER -->
    <section class="py-20 bg-slate-950 text-white border-y border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Featured Graphic Showcase (Liquid Glass Framed Graphic) -->
            <div class="lg:col-span-5">
                <div class="relative rounded-5xl overflow-hidden bg-white/5 backdrop-blur-3xl border border-white/15 p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_20px_50px_rgba(0,0,0,0.5)] group">
                    <img src="/images/padel_tennis_arena.png" alt="Outdoor Padel Arena" class="w-full h-72 object-cover rounded-2xl group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 flex items-center justify-between text-white">
                        <span class="text-xs font-bold">Racquet Arenas</span>
                        <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-sltds-sky/80 backdrop-blur-xl border border-white/30 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]">Automated Relays</span>
                    </div>
                </div>
            </div>

            <!-- B2B Core Statement -->
            <div class="lg:col-span-7 space-y-6">
                <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-300 leading-snug tracking-tight">
                    Stop losing revenue to double bookings and manual admin work. <span class="text-white underline decoration-sltds-sky underline-offset-8">BookFlow puts your entire venue on autopilot—from online reservations to automated court lighting.</span>
                </p>
                <div class="flex flex-wrap items-center gap-6 pt-2 text-xs font-bold text-slate-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Keep 100% of Your Revenue</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Launch Your Booking Site Instantly</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 3: VADEL-INSPIRED "CURRENTLY AVAILABLE COURTS" PORTRAIT CARDS -->
    <section class="py-24 bg-slate-50 text-slate-900 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
                <div class="space-y-2">
                    <h2 class="text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">Built for High-Demand Facilities</h2>
                    <p class="text-xs text-slate-600 max-w-xl">Powerful tools designed to eliminate administrative overhead and maximize your court utilization.</p>
                </div>
                <a href="{{ route('parent.features') }}" class="px-6 py-3 rounded-full text-xs font-black text-slate-900 bg-white hover:bg-slate-100 border border-slate-300 shadow-xs transition-all">
                    View All Features &rarr;
                </a>
            </div>

            <!-- 3 Portrait Category Cards Grid (Liquid Glass Framed) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Card 1: Badminton & Squash -->
                <div class="relative rounded-5xl overflow-hidden h-115 flex flex-col justify-end p-6 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.4)] group hover:scale-[1.02] transition-all duration-500 bg-slate-950/80 backdrop-blur-xl">
                    <img src="/images/courts/badminton.png" alt="Badminton & Squash Courts" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                    <div class="relative z-10 space-y-4 text-white">
                        <div class="space-y-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-sltds-sky">Category 01</span>
                            <h3 class="text-2xl font-black">Multi-Court Reservation Matrix</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">Give your players a seamless booking experience while you manage a side-by-side day matrix with automated conflict prevention.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[10px] font-extrabold">
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Visual Schedule</span>
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Conflict Prevention</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Padel & Tennis -->
                <div class="relative rounded-5xl overflow-hidden h-115 flex flex-col justify-end p-6 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.4)] group hover:scale-[1.02] transition-all duration-500 bg-slate-950/80 backdrop-blur-xl">
                    <img src="/images/padel_tennis_arena.png" alt="Outdoor Padel Court" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                    <div class="relative z-10 space-y-4 text-white">
                        <div class="space-y-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-sltds-green">Category 02</span>
                            <h3 class="text-2xl font-black">Peak Pricing & Member Credits</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">Maximize profit margins with dynamic peak-hour surcharges and secure recurring revenue through digital member pass ledgers.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[10px] font-extrabold">
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Dynamic Pricing</span>
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Pre-paid Wallets</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Gym & Fitness Studios -->
                <div class="relative rounded-5xl overflow-hidden h-115 flex flex-col justify-end p-6 border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.4)] group hover:scale-[1.02] transition-all duration-500 bg-slate-950/80 backdrop-blur-xl">
                    <img src="/images/gym_fitness_studio.png" alt="Gym & Fitness Studio" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                    <div class="relative z-10 space-y-4 text-white">
                        <div class="space-y-1">
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-amber-400">Category 03</span>
                            <h3 class="text-2xl font-black">Capacity Caps & Waitlists</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">Never overbook a class again. Automated headcount tracking and standby queues keep your fitness sessions full and organized.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-[10px] font-extrabold">
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Automated Waitlists</span>
                            <span class="px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-xl border border-white/25 text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">Headcount Limits</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 4: VADEL-INSPIRED "EXPLORE OUR FACILITIES" PLATFORM SHOWCASE -->
    <section class="py-24 bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-5xl bg-slate-950/80 backdrop-blur-3xl border border-white/15 p-8 sm:p-14 overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.15),0_30px_60px_rgba(0,0,0,0.6)] space-y-12">
                
                <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
                    <div class="space-y-2">
                        <h2 class="text-3xl sm:text-4xl font-black text-white">The Technology Powering Your Venue</h2>
                        <p class="text-xs text-slate-400">Seamlessly bridge the gap between your physical facility and digital operations.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('parent.features') }}" class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-white/10 hover:bg-white/20 backdrop-blur-xl border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] transition-all">
                            Full Feature Matrix &rarr;
                        </a>
                    </div>
                </div>

                <!-- 3 Capability Cards (Liquid Glass Effect) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Capability 1 -->
                    <div class="bg-white/5 backdrop-blur-2xl border border-white/15 rounded-3xl p-6 space-y-4 hover:border-white/40 hover:bg-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] transition-all duration-500 group">
                        <div class="w-12 h-12 rounded-2xl bg-sltds-sky/15 backdrop-blur-md text-sltds-sky flex items-center justify-center font-black shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-white group-hover:text-sltds-sky transition-colors">IoT Floodlight Automation</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Smart relay switches automatically trigger court lights precisely when a booking starts and shuts them off when time is up, saving electricity costs.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 2 -->
                    <div class="bg-white/5 backdrop-blur-2xl border border-white/15 rounded-3xl p-6 space-y-4 hover:border-white/40 hover:bg-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] transition-all duration-500 group">
                        <div class="w-12 h-12 rounded-2xl bg-sltds-green/15 backdrop-blur-md text-sltds-green flex items-center justify-center font-black shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-white group-hover:text-sltds-green transition-colors">Centralized Front-Desk Grid</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                A real-time, unified dashboard for your staff to oversee all court activity, process walk-ins, and manage online bookings instantly.
                            </p>
                        </div>
                    </div>

                    <!-- Capability 3 -->
                    <div class="bg-white/5 backdrop-blur-2xl border border-white/15 rounded-3xl p-6 space-y-4 hover:border-white/40 hover:bg-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] transition-all duration-500 group">
                        <div class="w-12 h-12 rounded-2xl bg-amber-400/15 backdrop-blur-md text-amber-400 flex items-center justify-center font-black shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-bold text-white group-hover:text-amber-400 transition-colors">Automated Membership Ledgers</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Sell digital session bundles. QR check-ins automatically deduct credits and track expiry dates without relying on manual tracking.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 5: VADEL-INSPIRED "COURT STORIES" -> VENUE SUCCESS DIRECTORY -->
    <section class="py-24 bg-slate-50 text-slate-900 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Trusted by Growing Sports Venues</h2>
                <p class="text-xs text-slate-600">Join the network of facility owners who have upgraded their operations with BookFlow.</p>
            </div>

            <!-- Filter Pills (Interactive Category Filter Bar) -->
            <div class="flex flex-wrap items-center justify-center gap-2.5" id="venue-filter-pills">
                <button onclick="filterVenues('all', this)" class="venue-filter-btn px-5 py-2 rounded-full text-xs font-black bg-slate-950 text-white shadow-md cursor-pointer border border-slate-900 transition-all">
                    All Venues
                </button>
                <button onclick="filterVenues('badminton', this)" class="venue-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-100 cursor-pointer shadow-xs transition-all">
                    Badminton & Squash
                </button>
                <button onclick="filterVenues('padel', this)" class="venue-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-100 cursor-pointer shadow-xs transition-all">
                    Padel & Racquet
                </button>
                <button onclick="filterVenues('fitness', this)" class="venue-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-100 cursor-pointer shadow-xs transition-all">
                    Gym & Fitness
                </button>
            </div>

            <!-- Venue Showcase Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="venue-cards-grid">
                @foreach($featuredTenants->take(4) as $t)
                    @php
                        $catLower = strtolower($t->category ?? '');
                        if (str_contains($catLower, 'badminton') || str_contains($catLower, 'squash')) {
                            $catGroup = 'badminton';
                            $imgSrc = $t->hero_image_url ?: '/images/courts/badminton.png';
                        } elseif (str_contains($catLower, 'padel') || str_contains($catLower, 'tennis') || str_contains($catLower, 'racquet')) {
                            $catGroup = 'padel';
                            $imgSrc = $t->hero_image_url ?: '/images/padel_tennis_arena.png';
                        } elseif (str_contains($catLower, 'gym') || str_contains($catLower, 'fitness') || str_contains($catLower, 'health')) {
                            $catGroup = 'fitness';
                            $imgSrc = $t->hero_image_url ?: '/images/gym_fitness_studio.png';
                        } else {
                            $catGroup = 'badminton';
                            $imgSrc = $t->hero_image_url ?: '/images/vadel_hero_court.png';
                        }
                    @endphp
                    <div data-venue-cat="{{ $catGroup }}" class="venue-card relative rounded-4xl overflow-hidden h-96 flex flex-col justify-end p-6 border border-white/10 shadow-[0_20px_40px_rgba(0,0,0,0.3)] group hover:scale-[1.02] transition-all duration-500 bg-slate-950">
                        <img src="{{ $imgSrc }}" alt="{{ $t->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80">
                        <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/60 to-transparent"></div>

                        <div class="relative z-10 space-y-3 text-white">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/15 backdrop-blur-xl text-white border border-white/25 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)] inline-block">
                                {{ $t->category }}
                            </span>
                            <div>
                                <h4 class="text-lg font-black text-white leading-tight">{{ $t->name }}</h4>
                                <p class="text-xs text-slate-300 line-clamp-1 mt-0.5">{{ $t->tagline ?? $t->description }}</p>
                            </div>
                            <a href="{{ \App\Services\TenantResolver::tenantUrl('booking.index', [], $t) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 transition-colors shadow-md">
                                Visit Venue Site &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- Inline Script for Venue Filtering -->
    <script>
        function filterVenues(category, btnElement) {
            // Update active pill button styles
            const buttons = document.querySelectorAll('.venue-filter-btn');
            buttons.forEach(btn => {
                btn.className = 'venue-filter-btn px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:bg-slate-100 cursor-pointer shadow-xs transition-all';
            });
            btnElement.className = 'venue-filter-btn px-5 py-2 rounded-full text-xs font-black bg-slate-950 text-white shadow-md cursor-pointer border border-slate-900 transition-all';

            // Filter cards
            const cards = document.querySelectorAll('.venue-card');
            cards.forEach(card => {
                if (category === 'all' || card.getAttribute('data-venue-cat') === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>

    <!-- SECTION 6: VADEL-INSPIRED "GET IN TOUCH" LIQUID GLASS DEMO CARD -->
    <section class="py-24 bg-slate-950 text-white relative overflow-hidden">
        <!-- Dedicated Sports Complex Lounge Background Image -->
        <img src="/images/contact_stadium_lights.png" alt="Sports Complex Lounge" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-105">
        <div class="absolute inset-0 bg-linear-to-t from-slate-950/60 via-slate-950/30 to-slate-950/40"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="text-center space-y-2">
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight uppercase italic">Ready to put your venue on autopilot?</h2>
                <p class="text-xs sm:text-sm text-slate-200 font-normal leading-relaxed">Book a personalized demo and see how BookFlow can transform your daily operations.</p>
            </div>

            <form action="{{ route('parent.contact.submit') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <input type="hidden" name="type" value="demo">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="name" required placeholder="Full Name *" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-300 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                    <input type="email" name="email" required placeholder="Email Address *" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-300 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" name="business_name" placeholder="Venue / Club Name" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-300 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                    <input type="text" name="phone" placeholder="Phone Number" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-300 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                </div>

                <textarea name="message" required rows="3" placeholder="Tell us about your courts or facility setup..." class="w-full px-5 py-3.5 rounded-3xl bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-300 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all resize-none"></textarea>

                <button type="submit" class="w-full py-4 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-[1.01]">
                    Submit Request &rarr;
                </button>
            </form>
        </div>
    </section>

</x-layouts.parent>
