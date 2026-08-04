<x-layouts.parent title="Features | BookFlow by SLTDS">

    <!-- PAGE HERO CANVAS (Home Inspired Minimalist Dark VADEL Aesthetic) -->
    <div class="p-3 sm:p-4 bg-slate-50">
        <section class="relative rounded-4xl sm:rounded-5xl overflow-hidden py-16 sm:py-24 flex flex-col justify-center items-center p-6 sm:p-10 shadow-2xl bg-slate-950 text-white">
            
            <!-- High-Res Background Image -->
            <img src="/images/vadel_hero_court.png" alt="Indoor Sports Court Facility" class="absolute inset-0 w-full h-full object-cover z-0 opacity-75 scale-105">
            <div class="absolute inset-0 bg-linear-to-b from-slate-950/50 via-slate-950/30 to-slate-950/75 z-0"></div>

            <!-- Header Content -->
            <div class="relative z-10 text-center space-y-4 sm:space-y-6 max-w-4xl mx-auto px-4">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black italic tracking-tighter text-white uppercase drop-shadow-2xl leading-tight">
                    Built for Facilities. Easy for Players.
                </h1>

                <p class="text-xs sm:text-base text-slate-200 max-w-2xl mx-auto font-normal leading-relaxed tracking-wide drop-shadow-md">
                    Explore the core tools that automate your venue scheduling, member pass checkout, and staff operations into one seamless operating system.
                </p>
            </div>

        </section>
    </div>

    <!-- 6 CORE MODULES SECTION (Original Structure Preserved) -->
    <section class="py-20 bg-slate-50 text-slate-900 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Module 01: Visual Multi-Court Day Grid -->
            <div id="calendar-booking" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 01
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Visual Multi-Court Day Grid</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Courts are displayed as rows with hourly time slot columns. Per-hour pricing is visible directly under each time header, allowing players to book single or consecutive hours in one step.
                    </p>
                    <ul class="space-y-2.5 text-xs font-semibold text-slate-700 pt-2">
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>7-Day horizontal date strip for single-tap day switching</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Color-coded availability: Available, Booked, Peak, and Maintenance</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Automated lighting relay triggers for reserved courts</span>
                        </li>
                    </ul>
                </div>
                <div class="lg:col-span-6 rounded-3xl overflow-hidden border border-slate-200 shadow-xl group">
                    <img src="/images/hero_sports_court.png" alt="Indoor Badminton and Tennis Court Facility" class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
            </div>

            <!-- Module 02: Capacity & Waitlist -->
            <div id="capacity-waitlist" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 order-2 lg:order-1 rounded-3xl overflow-hidden border border-slate-200 shadow-xl group">
                    <img src="/images/gym_fitness_studio.png" alt="Fitness Gym and Group Class Studio" class="w-full h-72 object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="lg:col-span-6 order-1 lg:order-2 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 02
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Capacity & Automated Waitlists</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        For group fitness classes and peak court slots. When a session fills up, players can join an automated waitlist. If someone cancels, the system automatically notifies the next person in line.
                    </p>
                    <ul class="space-y-2.5 text-xs font-semibold text-slate-700 pt-2">
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Class headcount tracking and instructor rosters</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Single-tap waitlist queue signup for players</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Module 03: Dynamic Pricing Rules -->
            <div id="pricing-rules" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 03
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Dynamic Pricing Engine & Rules</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Set peak hour rates for weekday evenings (5PM – 10PM), weekend surcharges, or seasonal discounts. Rates apply automatically across your booking matrix without manual adjustments.
                    </p>
                </div>
                <div class="lg:col-span-6 bg-slate-950 text-white border border-slate-900 rounded-3xl p-6 space-y-3 shadow-lg">
                    <div class="text-[10px] font-black uppercase tracking-widest text-sltds-sky">Active Rule: Peak Hour Surcharge</div>
                    <div class="p-4 bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 flex justify-between items-center text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <div>
                            <div class="font-extrabold text-white">Weekday Evenings (5:00 PM – 10:00 PM)</div>
                            <div class="text-slate-300 text-[11px]">+25% Hourly Rate Multiplier</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 backdrop-blur-md border border-emerald-400/40 text-emerald-300 font-black text-[10px]">ACTIVE</span>
                    </div>
                </div>
            </div>

            <!-- Module 04: Member Passes & Credits -->
            <div id="credits-passes" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 order-2 lg:order-1 bg-slate-950 text-white border border-slate-900 rounded-3xl p-6 space-y-3 shadow-lg">
                    <div class="text-[10px] font-black uppercase tracking-widest text-sltds-green">Customer Credit Ledger & Digital Pass</div>
                    <div class="p-4 bg-white/10 backdrop-blur-xl rounded-xl border border-white/20 space-y-2 text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                        <div class="flex justify-between font-extrabold text-white">
                            <span>10-Visit Badminton Pass</span>
                            <span class="text-emerald-400 font-black">8 / 10 Remaining</span>
                        </div>
                        <div class="w-full bg-white/20 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-400 h-full w-[80%]"></div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 order-1 lg:order-2 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 04
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Customer Credits & Member Passes</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Sell multi-visit passes (such as a 10-Court Pass) and pre-funded credit ledgers. Regular players check out instantly using their stored credit balance.
                    </p>
                </div>
            </div>

            <!-- Module 05: Invoicing -->
            <div id="invoicing" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 05
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Automated Tax Invoicing</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Every online or front-desk reservation generates an itemized PDF tax invoice detailing court hours, add-on equipment rentals, and tax breakdown.
                    </p>
                </div>
                <div class="lg:col-span-6 bg-slate-950 text-white border border-slate-900 rounded-3xl p-6 text-xs space-y-2 shadow-lg">
                    <div class="text-slate-400 font-mono text-[11px]">INVOICE #INV-2026-8801</div>
                    <div class="text-white font-extrabold text-sm">Colombo Courts Club &bull; LKR 8,200.00</div>
                    <div class="text-slate-300 text-[11px]">Includes: 2 hrs Badminton Court + Racket Rental x2 + Night LED Lighting</div>
                </div>
            </div>

            <!-- Module 06: Staff Admin -->
            <div id="admin-tools" class="bg-white border border-slate-200/80 rounded-4xl p-8 lg:p-10 shadow-xl grid grid-cols-1 lg:grid-cols-12 gap-8 items-center hover:border-slate-400 transition-all duration-300">
                <div class="lg:col-span-6 order-2 lg:order-1 bg-slate-950 text-white border border-slate-900 rounded-3xl p-6 space-y-2 text-xs shadow-lg">
                    <div class="text-[10px] font-black uppercase tracking-widest text-sltds-sky">Staff Role Access Control</div>
                    <div class="grid grid-cols-2 gap-2.5 text-white pt-1">
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Owner: Full Settings</div>
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Manager: Pricing</div>
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Front Desk: Cash</div>
                        <div class="p-3 rounded-xl bg-white/10 backdrop-blur-xl border border-white/20 font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Instructor: Classes</div>
                    </div>
                </div>
                <div class="lg:col-span-6 order-1 lg:order-2 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase bg-slate-950 text-white border border-slate-800 inline-block">
                        Module 06
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Multi-Role Staff Administration</h2>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        Assign role-based permissions for your staff: Facility Owner, Manager, Front Desk Cashier, and Instructors. Keep financial settings restricted while empowering front desk staff.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- BOTTOM CTA SECTION -->
    <section class="py-24 bg-slate-950 text-white relative overflow-hidden">
        <img src="/images/contact_stadium_lights.png" alt="Sports Complex Lounge" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-105">
        <div class="absolute inset-0 bg-linear-to-t from-slate-950/60 via-slate-950/30 to-slate-950/40"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 text-center space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight uppercase italic">Interested in seeing these features for your facility?</h2>
            <p class="text-xs sm:text-sm text-slate-200 max-w-xl mx-auto font-normal leading-relaxed">
                Schedule a 15-minute platform demo and learn how BookFlow can streamline your court operations.
            </p>
            <div class="pt-4">
                <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-8 py-4 rounded-full text-xs sm:text-sm font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-105">
                    Request a Free Demo &rarr;
                </a>
            </div>
        </div>
    </section>

</x-layouts.parent>
