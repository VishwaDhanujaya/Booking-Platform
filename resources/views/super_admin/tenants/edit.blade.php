<x-layouts.super_admin title="Edit Tenant | SLTDS Super Admin">

    <div class="max-w-4xl mx-auto space-y-8">

        <!-- CLEAN OPERATIONAL PAGE HEADER (VADEL Typography) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/15">
            <div class="space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-md">
                    EDIT TENANT: {{ $tenant->name }}
                </h1>
                <p class="text-xs text-slate-300 font-normal leading-relaxed">
                    Update SaaS plan, subscription status, category, tagline, or public directory visibility.
                </p>
            </div>
            <a href="{{ route('superadmin.tenants') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                &larr; Back to Directory
            </a>
        </div>

        <!-- FORM CARD (Specular Liquid Glass Container) -->
        <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl p-8 sm:p-10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.8)]">
            <form action="{{ route('superadmin.tenants.update', $tenant->id) }}" method="POST" class="space-y-8 text-xs">
                @csrf

                <!-- Row 1 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Facility Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Facility Name *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $tenant->name) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        @error('name')
                            <p class="text-xs text-rose-400 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subdomain Slug (Read-only) -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Subdomain / Slug (Read-only)</label>
                        <input type="text" readonly value="{{ $tenant->slug }}"
                               class="w-full px-4 py-3 rounded-2xl bg-white/5 border border-white/10 text-slate-400 font-mono font-bold cursor-not-allowed">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="category" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Category Tag *</label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/60 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                            <option value="Sports Venues & Facilities" class="bg-slate-950 text-white" {{ $tenant->category === 'Sports Venues & Facilities' ? 'selected' : '' }}>Sports Venues & Facilities</option>
                            <option value="Group Classes & Courses" class="bg-slate-950 text-white" {{ $tenant->category === 'Group Classes & Courses' ? 'selected' : '' }}>Group Classes & Courses</option>
                            <option value="Fitness Equipment" class="bg-slate-950 text-white" {{ $tenant->category === 'Fitness Equipment' ? 'selected' : '' }}>Fitness Equipment & Gyms</option>
                            <option value="Creche" class="bg-slate-950 text-white" {{ $tenant->category === 'Creche' ? 'selected' : '' }}>Creche & Childcare</option>
                        </select>
                    </div>

                    <!-- Subscription Plan -->
                    <div class="space-y-2">
                        <label for="subscription_plan" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">SaaS Subscription Plan *</label>
                        <select id="subscription_plan" name="subscription_plan" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/60 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                            <option value="starter" class="bg-slate-950 text-white" {{ $tenant->subscription_plan === 'starter' ? 'selected' : '' }}>Starter Tier ($49/month)</option>
                            <option value="pro" class="bg-slate-950 text-white" {{ $tenant->subscription_plan === 'pro' ? 'selected' : '' }}>Pro Tier ($99/month)</option>
                            <option value="enterprise" class="bg-slate-950 text-white" {{ $tenant->subscription_plan === 'enterprise' ? 'selected' : '' }}>Enterprise Plan (Custom)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div class="space-y-2">
                        <label for="subscription_status" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Subscription Status *</label>
                        <select id="subscription_status" name="subscription_status" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/60 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                            <option value="active" class="bg-slate-950 text-white" {{ $tenant->subscription_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trialing" class="bg-slate-950 text-white" {{ $tenant->subscription_status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                            <option value="suspended" class="bg-slate-950 text-white" {{ $tenant->subscription_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="cancelled" class="bg-slate-950 text-white" {{ $tenant->subscription_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Brand Color -->
                    <div class="space-y-2">
                        <label for="brand_color" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Brand Hex Color</label>
                        <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', $tenant->brand_color ?? '#0056A2') }}"
                               class="w-full h-11 p-1.5 rounded-2xl bg-white/10 border border-white/20 cursor-pointer">
                    </div>
                </div>

                <!-- Row 4: Tagline -->
                <div class="space-y-2">
                    <label for="tagline" class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Tagline</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $tenant->tagline) }}"
                           placeholder="e.g. Premier Badminton Court Facilities in Colombo"
                           class="w-full px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                </div>

                <!-- Toggles Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                        <div>
                            <div class="text-xs font-black text-white">Active Operational Workspace</div>
                            <div class="text-[11px] text-slate-400">Allow staff and customers to access the facility.</div>
                        </div>
                        <input type="checkbox" name="is_active" value="1" {{ $tenant->is_active ? 'checked' : '' }} class="w-5 h-5 accent-white rounded cursor-pointer">
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
                        <div>
                            <div class="text-xs font-black text-white">Publish to Public Directory</div>
                            <div class="text-[11px] text-slate-400">Showcase this venue in customer directory.</div>
                        </div>
                        <input type="checkbox" name="is_public" value="1" {{ $tenant->is_public ? 'checked' : '' }} class="w-5 h-5 accent-white rounded cursor-pointer">
                    </div>
                </div>

                <!-- Form Submit Actions -->
                <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                    <a href="{{ route('superadmin.tenants') }}" class="px-5 py-3 rounded-full text-xs font-bold text-slate-400 hover:text-white transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all hover:scale-105 active:scale-[0.98]">
                        Save Tenant Settings &rarr;
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.super_admin>
