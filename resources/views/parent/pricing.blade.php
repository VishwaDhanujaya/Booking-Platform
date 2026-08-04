<x-layouts.parent title="Platform SaaS Pricing Plans | BookFlow by SLTDS">

    <!-- PAGE HERO CANVAS (Features Inspired Minimalist Dark VADEL Aesthetic) -->
    <div class="p-3 sm:p-4 bg-slate-50">
        <section class="relative rounded-4xl sm:rounded-5xl overflow-hidden py-16 sm:py-24 flex flex-col justify-center items-center p-6 sm:p-10 shadow-2xl bg-slate-950 text-white">
            
            <!-- High-Res Background Image -->
            <img src="/images/contact_stadium_lights.png" alt="Sports Facility Lights" class="absolute inset-0 w-full h-full object-cover z-0 opacity-75 scale-105">
            <div class="absolute inset-0 bg-linear-to-b from-slate-950/50 via-slate-950/30 to-slate-950/75 z-0"></div>

            <!-- Header Content -->
            <div class="relative z-10 text-center space-y-4 sm:space-y-6 max-w-4xl mx-auto px-4">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black italic tracking-tighter text-white uppercase drop-shadow-2xl leading-tight">
                    Simple, Transparent Venue Pricing
                </h1>

                <p class="text-xs sm:text-base text-slate-200 max-w-2xl mx-auto font-normal leading-relaxed tracking-wide drop-shadow-md">
                    Select the plan that fits your court or studio setup. Zero booking commissions or hidden payment gate fees.
                </p>
            </div>

        </section>
    </div>

    <!-- PRICING PLANS & FEATURES WRAPPER -->
    <div x-data="{ 
        billingCycle: 'monthly',
        courts: 4,
        hourlyRate: 30,
        hoursOccupied: 5,
        getMonthlyRevenue() {
            return this.courts * this.hourlyRate * this.hoursOccupied * 30;
        },
        getTimeSaved() {
            return Math.round(this.courts * 4 * 1.5);
        },
        getBookingsCount() {
            return this.courts * this.hoursOccupied * 30;
        }
    }" class="bg-slate-50 py-20 space-y-24 text-slate-950">

        <!-- Tier Pricing Cards Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Billing Cycle Switcher Toggle (Liquid Glass Pill Style) -->
            <div class="flex items-center justify-center gap-4">
                <span class="text-xs font-bold transition-colors" :class="billingCycle === 'monthly' ? 'text-slate-950 font-black' : 'text-slate-500'">Monthly</span>
                <button 
                    @click="billingCycle = billingCycle === 'monthly' ? 'annual' : 'monthly'"
                    class="relative w-14 h-7 rounded-full bg-slate-200 border border-slate-300 transition-colors focus:outline-none flex items-center p-1 cursor-pointer"
                    :class="billingCycle === 'annual' ? 'bg-slate-950 border-slate-900' : 'bg-slate-200'"
                >
                    <span 
                        class="w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-300"
                        :class="billingCycle === 'annual' ? 'translate-x-7' : 'translate-x-0'"
                    ></span>
                </button>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold transition-colors" :class="billingCycle === 'annual' ? 'text-slate-950 font-black' : 'text-slate-500'">Annual Billing</span>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500 text-white shadow-xs">Save 20%</span>
                </div>
            </div>

            <!-- 3 Tier Grid (Liquid Glass VADEL Floating Cards) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

                <!-- Tier 1: Starter -->
                <div class="bg-white border border-slate-200/80 rounded-4xl p-8 sm:p-10 flex flex-col justify-between space-y-8 hover:border-slate-400 shadow-xl transition-all duration-300 group">
                    <div class="space-y-6">
                        <div>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-slate-100 text-slate-700 border border-slate-200 inline-block">
                                Starter Tier
                            </span>
                            <h2 class="text-2xl font-black text-slate-900 mt-3 tracking-tight">Single Venue</h2>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">For single courts, boutique studios, and facility test pilots.</p>
                        </div>

                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-950 tracking-tight" x-text="billingCycle === 'monthly' ? '$49' : '$39'">$49</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>

                        <div class="space-y-3 border-t border-slate-100 pt-6 text-xs text-slate-700">
                            <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider">Includes:</div>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Up to 2 Courts or Roster Tracks</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Online Day Matrix Booking</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Pay-at-Venue Checkout</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Automated PDF Invoices</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <a :href="'{{ route('tenant.register') }}?plan=starter&cycle=' + billingCycle" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs">
                        Select Starter &rarr;
                    </a>
                </div>

                <!-- Tier 2: Pro (Recommended VADEL Dark Highlight Card) -->
                <div class="bg-slate-950 text-white rounded-4xl p-8 sm:p-10 flex flex-col justify-between space-y-8 shadow-2xl relative border border-white/10 overflow-hidden group hover:scale-[1.01] transition-all duration-300">
                    <img src="/images/vadel_hero_court.png" alt="Facility Pro" class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-linear-to-t from-slate-950 via-slate-950/80 to-slate-950/60"></div>

                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/20 backdrop-blur-xl text-white border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]">
                                Facility Pro
                            </span>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/40">
                                Recommended
                            </span>
                        </div>

                        <div>
                            <h2 class="text-2xl font-black text-white tracking-tight">Facility Pro</h2>
                            <p class="text-xs text-slate-300 mt-1 leading-relaxed">Complete, multi-court reservation schedules with custom rules and passes.</p>
                        </div>

                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-white tracking-tight" x-text="billingCycle === 'monthly' ? '$99' : '$79'">$99</span>
                            <span class="text-xs text-slate-400 font-bold">/ month</span>
                        </div>

                        <div class="space-y-3 border-t border-white/10 pt-6 text-xs text-slate-300">
                            <div class="font-bold text-white uppercase text-[10px] tracking-wider">Everything in Starter +</div>
                            <ul class="space-y-3 font-medium">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Unlimited Courts & Schedules</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Custom Venue Branding & Logo</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Dynamic Peak Hour Pricing Surcharges</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Member Credit Ledgers & Passes</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Automatic Floodlight Triggers & Relays</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="relative z-10">
                        <a :href="'{{ route('tenant.register') }}?plan=pro&cycle=' + billingCycle" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 transition-all shadow-lg hover:scale-105">
                            Launch Pro Platform &rarr;
                        </a>
                    </div>
                </div>

                <!-- Tier 3: Enterprise -->
                <div class="bg-white border border-slate-200/80 rounded-4xl p-8 sm:p-10 flex flex-col justify-between space-y-8 hover:border-slate-400 shadow-xl transition-all duration-300 group">
                    <div class="space-y-6">
                        <div>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-slate-100 text-slate-700 border border-slate-200 inline-block">
                                Enterprise Tier
                            </span>
                            <h2 class="text-2xl font-black text-slate-900 mt-3 tracking-tight">Enterprise Network</h2>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">For multi-location sport networks, complexes, and franchise lines.</p>
                        </div>

                        <div class="flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-950 tracking-tight">Custom</span>
                            <span class="text-xs text-slate-500 font-bold">/ annual contract</span>
                        </div>

                        <div class="space-y-3 border-t border-slate-100 pt-6 text-xs text-slate-700">
                            <div class="font-bold text-slate-900 uppercase text-[10px] tracking-wider">Enterprise Features:</div>
                            <ul class="space-y-3">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Multi-location Central Dashboard</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Custom Domains & CNAME Routing</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Gate Turnstile Hardware Access APIs</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-slate-900 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Dedicated Setup Coordinator</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <a href="{{ route('parent.contact') }}" class="w-full inline-flex items-center justify-center px-6 py-4 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs">
                        Contact Sales &rarr;
                    </a>
                </div>

            </div>
        </section>

        <!-- ROI & REVENUE CALCULATOR (VADEL Dark Card Canvas) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-950 text-white rounded-4xl sm:rounded-5xl p-8 sm:p-14 space-y-12 shadow-2xl border border-white/10 relative overflow-hidden">
                <img src="/images/padel_tennis_arena.png" alt="Court Facility" class="absolute inset-0 w-full h-full object-cover opacity-20 scale-105">
                <div class="absolute inset-0 bg-linear-to-b from-slate-950 via-slate-950/90 to-slate-950"></div>

                <div class="relative z-10 text-center space-y-3 max-w-2xl mx-auto">
                    <h3 class="text-3xl sm:text-4xl font-black italic uppercase tracking-tighter text-white">
                        Estimate Your Facility's Monthly Revenue
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300">Adjust the sliders to see how fast BookFlow pays for itself.</p>
                </div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center max-w-5xl mx-auto">
                    <!-- Calculator Range Sliders -->
                    <div class="space-y-8 bg-white/5 backdrop-blur-2xl p-6 sm:p-8 rounded-3xl border border-white/10">
                        <!-- Courts Slider -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs font-bold text-slate-300">
                                <span>NUMBER OF COURTS</span>
                                <span class="text-white font-black" x-text="courts + ' Courts / Tracks'"></span>
                            </div>
                            <input type="range" min="1" max="15" x-model="courts" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-white">
                        </div>

                        <!-- Hourly Rate Slider -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs font-bold text-slate-300">
                                <span>AVG. HOURLY BOOKING FEE</span>
                                <span class="text-white font-black" x-text="'$' + hourlyRate"></span>
                            </div>
                            <input type="range" min="10" max="80" step="5" x-model="hourlyRate" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-white">
                        </div>

                        <!-- Daily Occupancy Slider -->
                        <div class="space-y-3">
                            <div class="flex justify-between text-xs font-bold text-slate-300">
                                <span>EST. OCCUPIED HOURS / DAY</span>
                                <span class="text-white font-black" x-text="hoursOccupied + ' hours'"></span>
                            </div>
                            <input type="range" min="2" max="12" x-model="hoursOccupied" class="w-full h-2 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-white">
                        </div>
                    </div>

                    <!-- Output Results Display Card -->
                    <div class="bg-white/10 backdrop-blur-3xl border border-white/20 rounded-3xl p-8 space-y-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]">
                        <div class="space-y-1">
                            <span class="text-[9px] font-black tracking-widest text-slate-300 uppercase block">Estimated Monthly Revenue</span>
                            <div class="text-4xl sm:text-5xl font-black text-white tracking-tight" x-text="'$' + getMonthlyRevenue().toLocaleString()"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-6">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-bold">EST. BOOKINGS / MONTH</span>
                                <span class="text-base font-black text-white" x-text="getBookingsCount() + ' Bookings'"></span>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block font-bold">ADMIN HOURS SAVED / WEEK</span>
                                <span class="text-base font-black text-emerald-300" x-text="getTimeSaved() + ' hours'"></span>
                            </div>
                        </div>

                        <div class="bg-white/10 rounded-2xl p-4 border border-white/15 text-xs text-slate-200 flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <span>BookFlow costs less than <strong class="text-white font-black" x-text="((billingCycle === 'monthly' ? (courts > 2 ? 99 : 49) : (courts > 2 ? 79 : 39)) / getMonthlyRevenue() * 100).toFixed(1) + '%'"></strong> of your estimated revenue.</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- DETAILED FEATURE CHECKLIST -->
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-2">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Compare Plan Features</h3>
                <p class="text-xs sm:text-sm text-slate-600 max-w-xl mx-auto">Detailed feature alignment to select your operational fit.</p>
            </div>

            <div class="overflow-x-auto rounded-4xl border border-slate-200/80 shadow-xl bg-white">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-950 text-white border-b border-slate-900">
                            <th class="p-5 font-black text-white uppercase text-[10px] tracking-wider">CORE MODULES</th>
                            <th class="p-5 font-black text-white text-center uppercase text-[10px] tracking-wider">Starter</th>
                            <th class="p-5 font-black text-white text-center uppercase text-[10px] tracking-wider">Pro</th>
                            <th class="p-5 font-black text-white text-center uppercase text-[10px] tracking-wider">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Court / Schedule limit</td>
                            <td class="p-5 text-center text-slate-600">Up to 2</td>
                            <td class="p-5 text-center text-slate-950 font-black">Unlimited</td>
                            <td class="p-5 text-center text-slate-950 font-black">Unlimited</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Day Matrix Schedule View</td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Dynamic Peak Surcharge Rule Config</td>
                            <td class="p-5 text-center text-slate-400">No</td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Member Passes & Credits Ledgers</td>
                            <td class="p-5 text-center text-slate-400">No</td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Automated Floodlight Trigger APIs</td>
                            <td class="p-5 text-center text-slate-400">No</td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                        </tr>
                        <tr>
                            <td class="p-5 font-bold text-slate-900">Dedicated Domain & CNAME</td>
                            <td class="p-5 text-center text-slate-400">No</td>
                            <td class="p-5 text-center text-slate-700 font-bold">Add-on ($15/mo)</td>
                            <td class="p-5 text-center"><svg class="w-4 h-4 text-slate-950 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- MINIMALIST FAQS SECTION -->
        <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8" x-data="{ activeFaq: null }">
            <div class="text-center space-y-2">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Frequently Asked Questions</h3>
                <p class="text-xs sm:text-sm text-slate-600">Get fast answers to common questions about BookFlow subscriptions.</p>
            </div>

            <div class="space-y-4 font-medium">
                <!-- FAQ 1 -->
                <div class="border border-slate-200/80 rounded-3xl bg-white overflow-hidden shadow-lg transition-all duration-300">
                    <button 
                        @click="activeFaq = activeFaq === 1 ? null : 1" 
                        class="w-full p-6 flex justify-between items-center font-black text-left text-xs sm:text-sm text-slate-950 focus:outline-none cursor-pointer"
                    >
                        <span>Are there any booking commissions or transaction fees?</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" :class="activeFaq === 1 ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="px-6 pb-6 text-xs text-slate-600 leading-relaxed">
                        No. Unlike other scheduling platforms, BookFlow charges a flat monthly fee. We do not extract commissions from your player reservations or charge extra gateway percentages beyond your standard payment provider rates.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-slate-200/80 rounded-3xl bg-white overflow-hidden shadow-lg transition-all duration-300">
                    <button 
                        @click="activeFaq = activeFaq === 2 ? null : 2" 
                        class="w-full p-6 flex justify-between items-center font-black text-left text-xs sm:text-sm text-slate-950 focus:outline-none cursor-pointer"
                    >
                        <span>How does the automated floodlight trigger work?</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" :class="activeFaq === 2 ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="px-6 pb-6 text-xs text-slate-600 leading-relaxed">
                        Our platform integrates with standard internet-connected relay boards (such as Sonoff or ESP32). When a court booking is paid and active, the system dispatches an API ping to toggle the lights on exactly 5 minutes before the slot starts and switches them off when the reservation expires.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-slate-200/80 rounded-3xl bg-white overflow-hidden shadow-lg transition-all duration-300">
                    <button 
                        @click="activeFaq = activeFaq === 3 ? null : 3" 
                        class="w-full p-6 flex justify-between items-center font-black text-left text-xs sm:text-sm text-slate-950 focus:outline-none cursor-pointer"
                    >
                        <span>Can I cancel or change my plan anytime?</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" :class="activeFaq === 3 ? 'rotate-180' : 'rotate-0'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="px-6 pb-6 text-xs text-slate-600 leading-relaxed">
                        Yes, absolutely. Our monthly plans are pay-as-you-go. You can upgrade, downgrade, or cancel your subscription at any time directly through your primary owner dashboard. Annual plans can be changed at the end of the yearly billing cycle.
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- BOTTOM DEMO CTA SECTION -->
    <section class="py-24 bg-slate-950 text-white relative overflow-hidden">
        <img src="/images/contact_stadium_lights.png" alt="Sports Complex Lounge" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-105">
        <div class="absolute inset-0 bg-linear-to-t from-slate-950/60 via-slate-950/30 to-slate-950/40"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 text-center space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight uppercase italic">Ready to streamline your sports venue?</h2>
            <p class="text-xs sm:text-sm text-slate-200 max-w-xl mx-auto font-normal leading-relaxed">
                Get in touch with our solutions engineers for a customized setup consultation.
            </p>
            <div class="pt-4">
                <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-8 py-4 rounded-full text-xs sm:text-sm font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-105">
                    Request a Free Demo &rarr;
                </a>
            </div>
        </div>
    </section>

</x-layouts.parent>
