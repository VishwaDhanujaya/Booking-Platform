<x-layouts.app title="Start Free Trial | Provision Venue Platform">

    <div class="min-h-[85vh] py-12 flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto px-4">
            
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl p-8 sm:p-12 space-y-8">
                
                <!-- Form Header -->
                <div class="text-center space-y-2 border-b border-slate-100 pb-6">
                    <span class="px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-[10px] font-extrabold uppercase tracking-wider">Self-Serve Business Onboarding</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Provision Your Sports Venue</h1>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Launch your branded court reservation platform with automatic starter courts, pricing rules, and instant booking slots.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs space-y-1">
                        <strong class="font-bold block">Please resolve the following inputs:</strong>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.register.perform') }}" method="POST" class="space-y-6 text-xs" x-data="{ selectedPlan: 'pro' }">
                    @csrf

                    <!-- Section 1: Business Details -->
                    <div class="space-y-4">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">1. Facility Details</h2>
                        
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Business / Venue Name</label>
                            <input type="text" name="business_name" required value="{{ old('business_name') }}" placeholder="e.g. Royal Tennis & Squash Club" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-medium text-slate-900 focus:ring-2 focus:ring-brand-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Subdomain Slug</label>
                            <div class="flex items-center">
                                <input type="text" name="slug" required value="{{ old('slug') }}" placeholder="royal-tennis" class="w-full px-4 py-3 rounded-l-xl border border-r-0 border-slate-300 font-mono font-bold text-slate-900">
                                <span class="bg-slate-100 px-4 py-3 rounded-r-xl border border-slate-300 font-mono font-bold text-slate-500 text-xs whitespace-nowrap">.bookingplatform.lk</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Subscription Plan Selector -->
                    <div class="space-y-3">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">2. Select Plan Tier</h2>
                        <input type="hidden" name="subscription_plan" :value="selectedPlan">

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div @click="selectedPlan = 'starter'" :class="selectedPlan === 'starter' ? 'border-brand-600 bg-brand-50/50 shadow-sm' : 'border-slate-200 bg-white'" class="p-4 rounded-2xl border cursor-pointer transition-all space-y-1">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Starter</span>
                                <strong class="text-sm font-extrabold text-slate-900 block">LKR 15,000<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                <p class="text-[10px] text-slate-500">Up to 2 courts, core bookings.</p>
                            </div>

                            <div @click="selectedPlan = 'pro'" :class="selectedPlan === 'pro' ? 'border-brand-600 bg-brand-50/50 shadow-sm' : 'border-slate-200 bg-white'" class="p-4 rounded-2xl border cursor-pointer transition-all space-y-1 relative">
                                <span class="absolute -top-2.5 right-3 bg-brand-600 text-white text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full">Popular</span>
                                <span class="text-[10px] font-extrabold uppercase text-brand-700 block">Pro Venue</span>
                                <strong class="text-sm font-extrabold text-slate-900 block">LKR 35,000<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                <p class="text-[10px] text-slate-500">Unlimited courts, pricing engine, passes & credits.</p>
                            </div>

                            <div @click="selectedPlan = 'enterprise'" :class="selectedPlan === 'enterprise' ? 'border-brand-600 bg-brand-50/50 shadow-sm' : 'border-slate-200 bg-white'" class="p-4 rounded-2xl border cursor-pointer transition-all space-y-1">
                                <span class="text-[10px] font-extrabold uppercase text-slate-400 block">Enterprise</span>
                                <strong class="text-sm font-extrabold text-slate-900 block">LKR 75,000<span class="text-[10px] font-normal text-slate-500">/mo</span></strong>
                                <p class="text-[10px] text-slate-500">Multi-location, API, dedicated support.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Owner Credentials -->
                    <div class="space-y-4 pt-2">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">3. Primary Owner Account</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Owner Full Name</label>
                                <input type="text" name="owner_name" required value="{{ old('owner_name') }}" placeholder="e.g. Dhananjaya Perera" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-medium">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                                <input type="email" name="owner_email" required value="{{ old('owner_email') }}" placeholder="owner@royaltennis.lk" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Contact Phone</label>
                                <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" placeholder="+94 77 123 4567" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-medium">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Account Password</label>
                                <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-300 font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" class="w-full py-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-xl transition-all">
                            Provision Venue Platform & Provision Courts &rarr;
                        </button>
                    </div>
                </form>

                <div class="text-center pt-2">
                    <p class="text-xs text-slate-500">Already registered your business? <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:underline">Log in to Admin Portal</a></p>
                </div>

            </div>

        </div>
    </div>

</x-layouts.app>
