<x-layouts.parent title="Register Venue | BookFlow by SLTDS">

    <section class="py-16 bg-slate-50 min-h-[calc(100vh-4rem)] flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto px-4" x-data="{ 
            step: 1, 
            selectedPlan: '{{ request()->query('plan', 'pro') }}',
            businessName: '{{ old('business_name') }}',
            slug: '{{ old('slug') }}',
            ownerName: '{{ old('owner_name') }}',
            ownerEmail: '{{ old('owner_email') }}',
            ownerPhone: '{{ old('owner_phone') }}',
            password: '',
            
            // Basic client-side validation helpers
            validateStep1() {
                return this.businessName.trim() !== '' && this.slug.trim() !== '';
            },
            validateStep2() {
                return this.selectedPlan !== '';
            },
            nextStep() {
                if (this.step === 1 && this.validateStep1()) {
                    this.step = 2;
                } else if (this.step === 2 && this.validateStep2()) {
                    this.step = 3;
                }
            },
            prevStep() {
                if (this.step > 1) {
                    this.step--;
                }
            }
        }">
            
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-2xl p-8 sm:p-12 space-y-8 transition-all duration-300">
                
                <!-- Wizard Header with Step Progress Track -->
                <div class="text-center space-y-4 pb-6 border-b border-slate-100">
                    <span class="px-3 py-1 rounded-full bg-blue-50 text-sltds-endeavour text-[10px] font-black uppercase tracking-wider border border-blue-200/50 shadow-xs">
                        Self-Serve Business Onboarding
                    </span>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Register Your Sports Venue</h1>
                    
                    <!-- Progress Stepper Track -->
                    <div class="flex items-center justify-between max-w-md mx-auto pt-4 relative">
                        <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-slate-100 -translate-y-1/2 z-0"></div>
                        <div class="absolute left-0 top-1/2 h-0.5 bg-sltds-endeavour -translate-y-1/2 z-0 transition-all duration-300" :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                        
                        <!-- Step 1 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5 cursor-pointer" @click="if(validateStep1()) step = 1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                :class="step >= 1 ? 'bg-sltds-endeavour text-white shadow-md shadow-sltds-endeavour/25' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                1
                            </div>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider" :class="step === 1 ? 'text-sltds-endeavour' : 'text-slate-400'">Venue</span>
                        </div>

                        <!-- Step 2 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5 cursor-pointer" @click="if(validateStep1()) step = 2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                :class="step >= 2 ? 'bg-sltds-endeavour text-white shadow-md shadow-sltds-endeavour/25' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                2
                            </div>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider" :class="step === 2 ? 'text-sltds-endeavour' : 'text-slate-400'">Plan</span>
                        </div>

                        <!-- Step 3 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300"
                                :class="step >= 3 ? 'bg-sltds-endeavour text-white shadow-md shadow-sltds-endeavour/25' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                3
                            </div>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider" :class="step === 3 ? 'text-sltds-endeavour' : 'text-slate-400'">Owner</span>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs space-y-1">
                        <strong class="font-bold block text-rose-900">Please resolve the following inputs:</strong>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.register.perform') }}" method="POST" class="space-y-6 text-xs">
                    @csrf
                    
                    <!-- Section 1: Business Details -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms class="space-y-4">
                        <div class="space-y-1">
                            <h2 class="text-xs font-black uppercase tracking-wider text-sltds-endeavour">Facility Details</h2>
                            <p class="text-[11px] text-slate-500">Give your venue online coordinates to launch reservation grids.</p>
                        </div>
                        
                        <div class="space-y-1.5 pt-2">
                            <label class="block font-bold text-slate-700 uppercase tracking-wider">Business / Venue Name *</label>
                            <input type="text" name="business_name" required x-model="businessName" placeholder="e.g. Royal Tennis & Squash Club" class="w-full px-4 py-3 rounded-xl border border-slate-250 font-medium text-slate-900 focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-700 uppercase tracking-wider">Subdomain Slug *</label>
                            <div class="flex items-center">
                                <input type="text" name="slug" required x-model="slug" placeholder="royal-tennis" class="w-full px-4 py-3 rounded-l-xl border border-r-0 border-slate-250 font-mono font-bold text-slate-900 focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                                <span class="bg-slate-100 px-4 py-3 rounded-r-xl border border-slate-250 font-mono font-bold text-slate-500 text-xs whitespace-nowrap">.localhost</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium block mt-1">This forms your workspace address: <code class="text-slate-600 font-bold" x-text="slug ? slug + '.localhost' : 'your-slug.localhost'"></code></span>
                        </div>
                    </div>

                    <!-- Section 2: Subscription Plan Selector -->
                    <div x-show="step === 2" x-transition.opacity.duration.300ms class="space-y-4" style="display: none;">
                        <div class="space-y-1">
                            <h2 class="text-xs font-black uppercase tracking-wider text-sltds-endeavour">Select Subscription Tier</h2>
                            <p class="text-[11px] text-slate-500">Review your tier features. Starter is perfect to pilot; Pro has full-suite automation.</p>
                        </div>
                        
                        <input type="hidden" name="subscription_plan" :value="selectedPlan">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <!-- Starter -->
                            <div @click="selectedPlan = 'starter'" 
                                :class="selectedPlan === 'starter' ? 'border-sltds-endeavour bg-blue-50/60 shadow-md' : 'border-slate-200 bg-white'" 
                                class="p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 space-y-2 flex flex-col justify-between hover:border-sltds-endeavour/50 select-none">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-extrabold uppercase text-slate-400 block">Starter</span>
                                    <strong class="text-lg font-black text-slate-900 block">$49<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-500 font-medium">Up to 2 courts, visual day grid booking matrix.</p>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'starter' ? 'text-sltds-endeavour' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'starter' ? 'SELECTED' : 'SELECT PLAN'"></span>
                                </div>
                            </div>

                            <!-- Pro -->
                            <div @click="selectedPlan = 'pro'" 
                                :class="selectedPlan === 'pro' ? 'border-sltds-endeavour bg-blue-50/60 shadow-md' : 'border-slate-200 bg-white'" 
                                class="p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 space-y-2 flex flex-col justify-between hover:border-sltds-endeavour/50 relative select-none">
                                <span class="absolute -top-2.5 right-3 bg-sltds-endeavour text-white text-[8px] font-black uppercase px-2 py-0.5 rounded-full shadow-xs tracking-wider">Recommended</span>
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black uppercase text-sltds-endeavour block">Facility Pro</span>
                                    <strong class="text-lg font-black text-slate-900 block">$99<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-500 font-medium">Unlimited courts, peak dynamic rules, passes & lighting.</p>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'pro' ? 'text-sltds-endeavour' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'pro' ? 'SELECTED' : 'SELECT PLAN'"></span>
                                </div>
                            </div>

                            <!-- Enterprise -->
                            <div @click="selectedPlan = 'enterprise'" 
                                :class="selectedPlan === 'enterprise' ? 'border-sltds-endeavour bg-blue-50/60 shadow-md' : 'border-slate-200 bg-white'" 
                                class="p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 space-y-2 flex flex-col justify-between hover:border-sltds-endeavour/50 select-none">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-extrabold uppercase text-slate-400 block">Enterprise</span>
                                    <strong class="text-lg font-black text-slate-900 block">Custom<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-500 font-medium">Multi-location networks, API integration, setup support.</p>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'enterprise' ? 'text-slate-400' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'enterprise' ? 'SELECTED' : 'SELECT PLAN'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Owner Credentials -->
                    <div x-show="step === 3" x-transition.opacity.duration.300ms class="space-y-4" style="display: none;">
                        <div class="space-y-1">
                            <h2 class="text-xs font-black uppercase tracking-wider text-sltds-endeavour">Primary Owner Account</h2>
                            <p class="text-[11px] text-slate-500">This establishes your owner admin session credentials to govern your courts.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider">Owner Full Name *</label>
                                <input type="text" name="owner_name" required x-model="ownerName" placeholder="e.g. Dhananjaya Perera" class="w-full px-4 py-3 rounded-xl border border-slate-250 font-medium focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider">Email Address *</label>
                                <input type="email" name="owner_email" required x-model="ownerEmail" placeholder="owner@royaltennis.lk" class="w-full px-4 py-3 rounded-xl border border-slate-250 font-medium focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider">Contact Phone</label>
                                <input type="text" name="owner_phone" x-model="ownerPhone" placeholder="+94 77 123 4567" class="w-full px-4 py-3 rounded-xl border border-slate-250 font-medium focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-700 uppercase tracking-wider">Account Password *</label>
                                <input type="password" name="password" required minlength="6" x-model="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-250 font-medium focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Wizard Navigation Footer -->
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <!-- Back Button -->
                        <button 
                            type="button" 
                            @click="prevStep()" 
                            x-show="step > 1" 
                            class="px-5 py-3 rounded-xl font-bold border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors focus:outline-none flex items-center gap-1.5 select-none"
                            style="display: none;"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back
                        </button>
                        
                        <div x-show="step === 1" class="text-slate-400 font-medium">Step 1 of 3</div>

                        <!-- Next Button (Step 1 & 2) -->
                        <button 
                            type="button" 
                            @click="nextStep()" 
                            x-show="step < 3"
                            :disabled="step === 1 && !validateStep1()"
                            :class="(step === 1 && !validateStep1()) ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-sltds-endeavour text-white hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/15'"
                            class="px-6 py-3 rounded-xl font-black transition-all duration-200 ml-auto flex items-center gap-1.5 select-none focus:outline-none"
                        >
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- Final Provision Button (Step 3) -->
                        <button 
                            type="submit" 
                            x-show="step === 3"
                            :disabled="!ownerName || !ownerEmail || password.length < 6"
                            :class="(!ownerName || !ownerEmail || password.length < 6) ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-sltds-endeavour text-white hover:bg-sltds-sky shadow-lg shadow-sltds-endeavour/20'"
                            class="px-6 py-3 rounded-xl font-black transition-all duration-200 ml-auto flex items-center gap-1.5 focus:outline-none"
                            style="display: none;"
                        >
                            Provision Workspace &rarr;
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2">
                    <p class="text-xs text-slate-500 font-medium">Already registered? <a href="{{ route('login') }}" class="font-bold text-sltds-endeavour hover:underline">Log in to your Admin Portal</a></p>
                </div>

            </div>

        </div>
    </section>

</x-layouts.parent>
