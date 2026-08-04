<x-layouts.parent title="Register Venue | BookFlow by SLTDS">

    <!-- PAGE CANVAS (VADEL Minimalist Dark Sports Court Canvas with Liquid Glass Onboarding Card) -->
    <section class="relative bg-slate-950 text-white min-h-[calc(100vh-4.5rem)] flex items-center justify-center py-16 px-4 overflow-hidden">
        
        <!-- Sports Court High-Res Background Image -->
        <img src="/images/vadel_hero_court.png" alt="Indoor Sports Court Facility" class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105 z-0">
        <div class="absolute inset-0 bg-linear-to-b from-slate-950/60 via-slate-950/40 to-slate-950/80 z-0"></div>

        <div class="relative z-10 max-w-2xl w-full mx-auto" x-data="{ 
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
            
            <!-- Liquid Glass Specular Card Container -->
            <div class="bg-slate-950/80 backdrop-blur-3xl rounded-4xl sm:rounded-5xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.7)] p-8 sm:p-12 space-y-8 transition-all duration-300">
                
                <!-- Wizard Header with Step Progress Track -->
                <div class="text-center space-y-4 pb-6 border-b border-white/10">
                    <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-[0_10px_25px_rgba(0,0,0,0.9)]">
                        Register Your Sports Venue
                    </h1>
                    
                    <!-- Progress Stepper Track (Liquid Glass Pills) -->
                    <div class="flex items-center justify-between max-w-md mx-auto pt-4 relative">
                        <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-white/15 -translate-y-1/2 z-0"></div>
                        <div class="absolute left-0 top-1/2 h-0.5 bg-white -translate-y-1/2 z-0 transition-all duration-300 shadow-[0_0_10px_rgba(255,255,255,0.8)]" :style="'width: ' + ((step - 1) * 50) + '%'"></div>
                        
                        <!-- Step 1 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5 cursor-pointer" @click="if(validateStep1()) step = 1">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-xs transition-all duration-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]"
                                :class="step >= 1 ? 'bg-white text-slate-950 shadow-[0_4px_15px_rgba(255,255,255,0.4)]' : 'bg-white/10 backdrop-blur-xl text-slate-400 border border-white/20'">
                                1
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider" :class="step === 1 ? 'text-white' : 'text-slate-400'">Venue</span>
                        </div>

                        <!-- Step 2 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5 cursor-pointer" @click="if(validateStep1()) step = 2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-xs transition-all duration-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]"
                                :class="step >= 2 ? 'bg-white text-slate-950 shadow-[0_4px_15px_rgba(255,255,255,0.4)]' : 'bg-white/10 backdrop-blur-xl text-slate-400 border border-white/20'">
                                2
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider" :class="step === 2 ? 'text-white' : 'text-slate-400'">Plan</span>
                        </div>

                        <!-- Step 3 Button/Label -->
                        <div class="relative z-10 flex flex-col items-center gap-1.5">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-xs transition-all duration-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]"
                                :class="step >= 3 ? 'bg-white text-slate-950 shadow-[0_4px_15px_rgba(255,255,255,0.4)]' : 'bg-white/10 backdrop-blur-xl text-slate-400 border border-white/20'">
                                3
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-wider" :class="step === 3 ? 'text-white' : 'text-slate-400'">Owner</span>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-500/20 backdrop-blur-xl border border-rose-400/40 text-rose-200 p-4 rounded-2xl text-xs space-y-1 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        <strong class="font-black block text-rose-100">Please resolve the following inputs:</strong>
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
                            <h2 class="text-xs font-black uppercase tracking-wider text-white">Facility Details</h2>
                            <p class="text-[11px] text-slate-300 font-normal">Give your venue online coordinates to launch reservation grids.</p>
                        </div>
                        
                        <div class="space-y-1.5 pt-2">
                            <label class="block font-bold text-slate-300 uppercase tracking-wider">Business / Venue Name *</label>
                            <input type="text" name="business_name" required x-model="businessName" placeholder="e.g. Royal Tennis & Squash Club" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-bold text-slate-300 uppercase tracking-wider">Subdomain Slug *</label>
                            <div class="flex items-center">
                                <input type="text" name="slug" required x-model="slug" placeholder="royal-tennis" class="w-full px-5 py-3.5 rounded-l-full bg-white/10 backdrop-blur-2xl border border-r-0 border-white/20 text-white placeholder-slate-400 font-mono font-bold focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                                <span class="bg-white/15 backdrop-blur-2xl px-5 py-3.5 rounded-r-full border border-white/20 font-mono font-bold text-slate-300 text-xs whitespace-nowrap shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">.localhost</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium block mt-1">This forms your workspace address: <code class="text-slate-200 font-bold" x-text="slug ? slug + '.localhost' : 'your-slug.localhost'"></code></span>
                        </div>
                    </div>

                    <!-- Section 2: Subscription Plan Selector -->
                    <div x-show="step === 2" x-transition.opacity.duration.300ms class="space-y-4" style="display: none;">
                        <div class="space-y-1">
                            <h2 class="text-xs font-black uppercase tracking-wider text-white">Select Subscription Tier</h2>
                            <p class="text-[11px] text-slate-300 font-normal">Review your tier features. Starter is perfect to pilot; Pro has full-suite automation.</p>
                        </div>
                        
                        <input type="hidden" name="subscription_plan" :value="selectedPlan">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <!-- Starter -->
                            <div @click="selectedPlan = 'starter'" 
                                :class="selectedPlan === 'starter' ? 'border-white bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_25px_rgba(0,0,0,0.5)]' : 'border-white/15 bg-white/5 backdrop-blur-2xl hover:border-white/40 hover:bg-white/10'" 
                                class="p-5 rounded-3xl border cursor-pointer transition-all duration-300 space-y-2 flex flex-col justify-between select-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Starter</span>
                                    <strong class="text-xl font-black text-white block">$49<span class="text-[10px] font-normal text-slate-300">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-300 font-normal">Up to 2 courts, visual day grid booking matrix.</p>
                                </div>
                                <div class="pt-2 border-t border-white/10 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'starter' ? 'text-white' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'starter' ? 'SELECTED ✓' : 'SELECT PLAN'"></span>
                                </div>
                            </div>

                            <!-- Pro -->
                            <div @click="selectedPlan = 'pro'" 
                                :class="selectedPlan === 'pro' ? 'border-white bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_25px_rgba(0,0,0,0.5)]' : 'border-white/15 bg-white/5 backdrop-blur-2xl hover:border-white/40 hover:bg-white/10'" 
                                class="p-5 rounded-3xl border cursor-pointer transition-all duration-300 space-y-2 flex flex-col justify-between relative select-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                <span class="absolute -top-2.5 right-3 bg-white text-slate-950 text-[8px] font-black uppercase px-2.5 py-0.5 rounded-full shadow-md tracking-wider">Recommended</span>
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black uppercase tracking-wider text-white block">Facility Pro</span>
                                    <strong class="text-xl font-black text-white block">$99<span class="text-[10px] font-normal text-slate-300">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-300 font-normal">Unlimited courts, peak dynamic rules, passes & lighting.</p>
                                </div>
                                <div class="pt-2 border-t border-white/10 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'pro' ? 'text-white' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'pro' ? 'SELECTED ✓' : 'SELECT PLAN'"></span>
                                </div>
                            </div>

                            <!-- Enterprise -->
                            <div @click="selectedPlan = 'enterprise'" 
                                :class="selectedPlan === 'enterprise' ? 'border-white bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_25px_rgba(0,0,0,0.5)]' : 'border-white/15 bg-white/5 backdrop-blur-2xl hover:border-white/40 hover:bg-white/10'" 
                                class="p-5 rounded-3xl border cursor-pointer transition-all duration-300 space-y-2 flex flex-col justify-between select-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                <div class="space-y-1">
                                    <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 block">Enterprise</span>
                                    <strong class="text-xl font-black text-white block">Custom<span class="text-[10px] font-normal text-slate-300">/mo</span></strong>
                                    <p class="text-[10px] leading-relaxed text-slate-300 font-normal">Multi-location networks, API integration, setup support.</p>
                                </div>
                                <div class="pt-2 border-t border-white/10 flex items-center gap-1.5 text-[9px] font-black" :class="selectedPlan === 'enterprise' ? 'text-white' : 'text-slate-400'">
                                    <span x-text="selectedPlan === 'enterprise' ? 'SELECTED ✓' : 'SELECT PLAN'"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Owner Credentials -->
                    <div x-show="step === 3" x-transition.opacity.duration.300ms class="space-y-4" style="display: none;">
                        <div class="space-y-1">
                            <h2 class="text-xs font-black uppercase tracking-wider text-white">Primary Owner Account</h2>
                            <p class="text-[11px] text-slate-300 font-normal">This establishes your owner admin session credentials to govern your courts.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-300 uppercase tracking-wider">Owner Full Name *</label>
                                <input type="text" name="owner_name" required x-model="ownerName" placeholder="e.g. Dhananjaya Perera" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-300 uppercase tracking-wider">Email Address *</label>
                                <input type="email" name="owner_email" required x-model="ownerEmail" placeholder="owner@royaltennis.lk" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-300 uppercase tracking-wider">Contact Phone</label>
                                <input type="text" name="owner_phone" x-model="ownerPhone" placeholder="+94 77 123 4567" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block font-bold text-slate-300 uppercase tracking-wider">Account Password *</label>
                                <input type="password" name="password" required minlength="6" x-model="password" placeholder="••••••••" class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Wizard Navigation Footer -->
                    <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                        <!-- Back Button -->
                        <button 
                            type="button" 
                            @click="prevStep()" 
                            x-show="step > 1" 
                            class="px-6 py-3 rounded-full font-bold border border-white/20 bg-white/10 hover:bg-white/20 backdrop-blur-xl text-white transition-all focus:outline-none flex items-center gap-1.5 select-none shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]"
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
                            :class="(step === 1 && !validateStep1()) ? 'bg-white/20 text-slate-500 cursor-not-allowed border border-white/10' : 'bg-white text-slate-950 hover:bg-slate-100 shadow-[0_10px_25px_rgba(255,255,255,0.3)] hover:scale-105'"
                            class="px-7 py-3 rounded-full font-black transition-all duration-200 ml-auto flex items-center gap-1.5 select-none focus:outline-none"
                        >
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>

                        <!-- Final Provision Button (Step 3) -->
                        <button 
                            type="submit" 
                            x-show="step === 3"
                            :disabled="!ownerName || !ownerEmail || password.length < 6"
                            :class="(!ownerName || !ownerEmail || password.length < 6) ? 'bg-white/20 text-slate-500 cursor-not-allowed border border-white/10' : 'bg-white text-slate-950 hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.4)] hover:scale-105'"
                            class="px-7 py-3 rounded-full font-black transition-all duration-200 ml-auto flex items-center gap-1.5 focus:outline-none"
                            style="display: none;"
                        >
                            Provision Workspace &rarr;
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2">
                    <p class="text-xs text-slate-400 font-medium">Already registered? <a href="{{ route('login') }}" class="font-bold text-white hover:underline">Log in to your Admin Portal &rarr;</a></p>
                </div>

            </div>

        </div>
    </section>

</x-layouts.parent>
