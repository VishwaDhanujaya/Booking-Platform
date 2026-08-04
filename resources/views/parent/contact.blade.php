<x-layouts.parent title="Contact & Demo Request | BookFlow by SLTDS">

    <!-- PAGE HERO CANVAS (Features Inspired Minimalist Dark VADEL Aesthetic) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden py-16 sm:py-24 flex flex-col justify-center items-center p-6 sm:p-10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white">
            
            <!-- High-Res Background Image -->
            <img src="/images/contact_stadium_lights.png" alt="Sports Facility Lounge" class="absolute inset-0 w-full h-full object-cover z-0 opacity-75 scale-105">
            <div class="absolute inset-0 bg-linear-to-b from-slate-950/50 via-slate-950/30 to-slate-950/75 z-0"></div>

            <!-- Header Content -->
            <div class="relative z-10 text-center space-y-4 sm:space-y-6 max-w-4xl mx-auto px-4">
                
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black italic tracking-tighter text-white uppercase drop-shadow-[0_10px_30px_rgba(0,0,0,0.8)] leading-tight">
                    Get In Touch With Our Solutions Team
                </h1>

                <p class="text-xs sm:text-base text-slate-200 max-w-2xl mx-auto font-normal leading-relaxed tracking-wide drop-shadow-md">
                    Fill in your facility details below and a venue automation expert will reach out to help set up your facility.
                </p>
            </div>

        </section>
    </div>

    <!-- MAIN CONTACT FORM SECTION -->
    <section class="py-20 bg-slate-50 text-slate-900 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- Success Alert Banner -->
            @if(session('status'))
                <div class="p-5 rounded-3xl bg-emerald-500/20 backdrop-blur-xl border border-emerald-500/40 text-emerald-900 text-xs font-bold flex items-center gap-3 shadow-lg">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Form Card (VADEL Dark Canvas Card) -->
            <div class="bg-slate-950 text-white rounded-4xl sm:rounded-5xl p-8 sm:p-12 shadow-2xl border border-white/10 relative overflow-hidden">
                <img src="/images/vadel_hero_court.png" alt="Indoor Sports Facility" class="absolute inset-0 w-full h-full object-cover opacity-20 scale-105">
                <div class="absolute inset-0 bg-linear-to-b from-slate-950 via-slate-950/90 to-slate-950"></div>

                <div class="relative z-10 space-y-8">
                    <div class="text-center space-y-2">
                        <h2 class="text-2xl sm:text-4xl font-black italic uppercase tracking-tighter text-white">Facility Inquiry Form</h2>
                        <p class="text-xs sm:text-sm text-slate-300">We respond to all requests within 24 business hours.</p>
                    </div>

                    <form action="{{ route('parent.contact.submit') }}" method="POST" class="space-y-6 text-xs">
                        @csrf

                        <input type="hidden" name="type" value="{{ request()->routeIs('parent.demo') ? 'demo' : 'contact' }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Full Name *</label>
                                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                       placeholder="e.g. Aruni Fernando"
                                       class="w-full px-5 py-4 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                                @error('name')
                                    <p class="text-xs text-rose-400 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Work Email *</label>
                                <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                       placeholder="e.g. aruni@colombocourts.lk"
                                       class="w-full px-5 py-4 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                                @error('email')
                                    <p class="text-xs text-rose-400 font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Phone -->
                            <div class="space-y-2">
                                <label for="phone" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Phone / WhatsApp</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       placeholder="e.g. +94 77 123 4567"
                                       class="w-full px-5 py-4 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>

                            <!-- Business Name -->
                            <div class="space-y-2">
                                <label for="business_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Facility Name</label>
                                <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}"
                                       placeholder="e.g. Colombo Badminton Arena"
                                       class="w-full px-5 py-4 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="space-y-2">
                            <label for="category" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Facility Type</label>
                            <select id="category" name="category"
                                    class="w-full px-5 py-4 rounded-full bg-slate-900 border border-white/20 text-white text-xs focus:outline-none focus:border-white/60 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                                <option value="Sports Venues & Facilities" class="bg-slate-900 text-white">Sports Venues & Facilities (Courts, Turf, Complex)</option>
                                <option value="Group Classes & Courses" class="bg-slate-900 text-white">Group Classes & Courses (Yoga, Pilates, Studios)</option>
                                <option value="Fitness Equipment" class="bg-slate-900 text-white">Fitness Equipment & Gyms</option>
                                <option value="Creche" class="bg-slate-900 text-white">Creche & Childcare</option>
                                <option value="Other" class="bg-slate-900 text-white">Other Facility</option>
                            </select>
                        </div>

                        <!-- Message -->
                        <div class="space-y-2">
                            <label for="message" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Message or Questions *</label>
                            <textarea id="message" name="message" rows="4" required
                                      placeholder="Tell us about your court numbers, schedule needs, or demo questions..."
                                      class="w-full px-5 py-4 rounded-3xl bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all resize-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-rose-400 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Action -->
                        <button type="submit" class="w-full py-4 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-[1.01]">
                            Submit Inquiry &rarr;
                        </button>

                    </form>
                </div>
            </div>

            <!-- Direct Contact Footer Cards (Liquid Glass Floating Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-xs">
                <div class="bg-white border border-slate-200/80 rounded-4xl p-6 shadow-xl space-y-2 group hover:border-slate-400 transition-all">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Email Sales</div>
                    <div class="font-black text-slate-950 text-sm">sales@sltdigital.com</div>
                </div>
                <div class="bg-white border border-slate-200/80 rounded-4xl p-6 shadow-xl space-y-2 group hover:border-slate-400 transition-all">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Office Location</div>
                    <div class="font-bold text-slate-900">Lotus Road, Colombo 01, Sri Lanka</div>
                </div>
                <div class="bg-white border border-slate-200/80 rounded-4xl p-6 shadow-xl space-y-2 group hover:border-slate-400 transition-all">
                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Working Hours</div>
                    <div class="font-bold text-slate-900">Mon - Sat: 8:00 AM - 8:00 PM</div>
                </div>
            </div>

        </div>
    </section>

</x-layouts.parent>
