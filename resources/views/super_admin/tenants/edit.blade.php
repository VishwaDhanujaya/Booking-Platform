<x-layouts.super_admin title="Edit Tenant | SLTDS Super Admin">

    <div class="max-w-4xl mx-auto space-y-8">

        <!-- CLEAN OPERATIONAL PAGE HEADER -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-950 tracking-tight">Edit Tenant: {{ $tenant->name }}</h1>
                <p class="text-xs text-slate-500 font-medium">Update SaaS plan, subscription status, category, tagline, or public directory visibility.</p>
            </div>
            <a href="{{ route('superadmin.tenants') }}" class="px-5 py-2.5 rounded-full text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                &larr; Back to Directory
            </a>
        </div>

        <!-- FORM CARD (Spacious White Card - Max Use of Space) -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-xl">
            <form action="{{ route('superadmin.tenants.update', $tenant->id) }}" method="POST" class="space-y-8 text-xs">
                @csrf

                <!-- Row 1 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Facility Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Facility Name *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $tenant->name) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                        @error('name')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subdomain Slug (Read-only) -->
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Subdomain / Slug (Read-only)</label>
                        <input type="text" readonly value="{{ $tenant->slug }}"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 font-mono font-bold cursor-not-allowed">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label for="category" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Category Tag *</label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-semibold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            <option value="Sports Venues & Facilities" {{ $tenant->category === 'Sports Venues & Facilities' ? 'selected' : '' }}>Sports Venues & Facilities</option>
                            <option value="Group Classes & Courses" {{ $tenant->category === 'Group Classes & Courses' ? 'selected' : '' }}>Group Classes & Courses</option>
                            <option value="Fitness Equipment" {{ $tenant->category === 'Fitness Equipment' ? 'selected' : '' }}>Fitness Equipment & Gyms</option>
                            <option value="Creche" {{ $tenant->category === 'Creche' ? 'selected' : '' }}>Creche & Childcare</option>
                        </select>
                    </div>

                    <!-- Subscription Plan -->
                    <div class="space-y-2">
                        <label for="subscription_plan" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">SaaS Subscription Plan *</label>
                        <select id="subscription_plan" name="subscription_plan" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-semibold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            <option value="starter" {{ $tenant->subscription_plan === 'starter' ? 'selected' : '' }}>Starter Tier ($49/month)</option>
                            <option value="pro" {{ $tenant->subscription_plan === 'pro' ? 'selected' : '' }}>Pro Tier ($99/month)</option>
                            <option value="enterprise" {{ $tenant->subscription_plan === 'enterprise' ? 'selected' : '' }}>Enterprise Plan (Custom)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Subscription Status -->
                    <div class="space-y-2">
                        <label for="subscription_status" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Subscription Status *</label>
                        <select id="subscription_status" name="subscription_status" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-semibold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            <option value="active" {{ $tenant->subscription_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trialing" {{ $tenant->subscription_status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                            <option value="suspended" {{ $tenant->subscription_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="cancelled" {{ $tenant->subscription_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Brand Color -->
                    <div class="space-y-2">
                        <label for="brand_color" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Brand Hex Color</label>
                        <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', $tenant->brand_color ?? '#0056A2') }}"
                               class="w-full h-11 p-1.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer">
                    </div>
                </div>

                <!-- Tagline -->
                <div class="space-y-2">
                    <label for="tagline" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Tagline</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $tenant->tagline) }}"
                           class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                </div>

                <!-- Toggles -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div>
                            <div class="text-xs font-black text-slate-950">Tenant Account Active</div>
                            <div class="text-[11px] text-slate-500">Allows customers to access and book courts.</div>
                        </div>
                        <input type="checkbox" name="is_active" value="1" {{ $tenant->is_active ? 'checked' : '' }} class="w-5 h-5 accent-slate-950 rounded cursor-pointer">
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div>
                            <div class="text-xs font-black text-slate-950">Public Directory Showcase</div>
                            <div class="text-[11px] text-slate-500">Visible on the public live venues directory.</div>
                        </div>
                        <input type="checkbox" name="is_public" value="1" {{ $tenant->is_public ? 'checked' : '' }} class="w-5 h-5 accent-slate-950 rounded cursor-pointer">
                    </div>
                </div>

                <!-- Submit Actions -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('superadmin.tenants') }}" class="px-5 py-3 rounded-full text-xs font-bold text-slate-500 hover:text-slate-950 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-full text-xs font-black text-white bg-slate-950 hover:bg-slate-800 shadow-lg transition-all hover:scale-105">
                        Save Tenant Changes &rarr;
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.super_admin>
