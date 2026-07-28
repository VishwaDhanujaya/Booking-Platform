<x-layouts.parent title="Contact & Demo Request | BookFlow by SLTDS">

    <!-- Page Header Hero (SLTDS Corporate Branding) -->
    <section class="py-16 bg-linear-to-br from-slate-900 via-sltds-endeavour to-slate-950 text-white border-b border-sltds-sky/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <div>
                <span class="inline-block px-4.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-sltds-sky/20 text-sltds-sky border border-sltds-sky/40 mb-4 shadow-xs">
                    Get In Touch
                </span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-black leading-tight text-white">Request a Walkthrough or Ask a Question</h1>
            <p class="text-slate-200 text-sm sm:text-base max-w-2xl mx-auto pt-1">
                Fill in your details below and a team member will reach out to help set up your facility.
            </p>
        </div>
    </section>

    <!-- Main Contact Form Section -->
    <section class="py-16 bg-slate-50 text-slate-900">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Success Alert Banner -->
            @if(session('status'))
                <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-sltds-green shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-lg space-y-6">
                
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Facility Inquiry Form</h2>
                    <p class="text-xs text-slate-500 mt-1">We respond to all requests within 24 business hours.</p>
                </div>

                <form action="{{ route('parent.contact.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <input type="hidden" name="type" value="{{ request()->routeIs('parent.demo') ? 'demo' : 'contact' }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}"
                                   placeholder="e.g. Aruni Fernando"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            @error('name')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Work Email *</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}"
                                   placeholder="e.g. aruni@colombocourts.lk"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            @error('email')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Phone / WhatsApp</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   placeholder="e.g. +94 77 123 4567"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                        </div>

                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility Name</label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}"
                                   placeholder="e.g. Colombo Badminton Arena"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility Type</label>
                        <select id="category" name="category"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            <option value="Sports Venues & Facilities">Sports Venues & Facilities (Courts, Turf, Complex)</option>
                            <option value="Group Classes & Courses">Group Classes & Courses (Yoga, Pilates, Studios)</option>
                            <option value="Fitness Equipment">Fitness Equipment & Gyms</option>
                            <option value="Creche">Creche & Childcare</option>
                            <option value="Other">Other Facility</option>
                        </select>
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Message or Questions *</label>
                        <textarea id="message" name="message" rows="4" required
                                  placeholder="Tell us about your court numbers, schedule needs, or demo questions..."
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Action -->
                    <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-xs font-black text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                        Submit Inquiry &rarr;
                    </button>

                </form>

            </div>

            <!-- Direct Contact Footer Cards -->
            <div class="mt-10 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-xs text-slate-600">
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="font-bold text-slate-900 mb-1">Email Sales</div>
                    <div class="text-sltds-endeavour font-semibold">sales@sltdigital.com</div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="font-bold text-slate-900 mb-1">Office Location</div>
                    <div>Lotus Road, Colombo 01, Sri Lanka</div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                    <div class="font-bold text-slate-900 mb-1">Working Hours</div>
                    <div>Mon - Sat: 8:00 AM - 8:00 PM</div>
                </div>
            </div>

        </div>
    </section>

</x-layouts.parent>
