<x-layouts.super_admin title="Edit Tenant | SLTDS Super Admin">

    <div class="max-w-3xl mx-auto space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 shadow-xs">
                        Tenant Settings
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Edit Tenant: {{ $tenant->name }}</h1>
                <p class="text-xs text-slate-500">Update SaaS subscription plan, status, category, or directory visibility.</p>
            </div>
            
            <a href="{{ route('superadmin.tenants') }}" class="text-xs font-bold text-slate-600 hover:text-sltds-endeavour transition-colors">
                &larr; Back to Directory
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-lg">
            <form action="{{ route('superadmin.tenants.update', $tenant->id) }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Facility Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility Name *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $tenant->name) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                    </div>

                    <!-- Subdomain Slug (Read only) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Subdomain / Slug</label>
                        <input type="text" readonly value="{{ $tenant->slug }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-100 border border-slate-200 text-slate-500 text-xs font-mono cursor-not-allowed">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category Tag *</label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            <option value="Sports Venues & Facilities" {{ $tenant->category === 'Sports Venues & Facilities' ? 'selected' : '' }}>Sports Venues & Facilities</option>
                            <option value="Group Classes & Courses" {{ $tenant->category === 'Group Classes & Courses' ? 'selected' : '' }}>Group Classes & Courses</option>
                            <option value="Fitness Equipment" {{ $tenant->category === 'Fitness Equipment' ? 'selected' : '' }}>Fitness Equipment & Gyms</option>
                            <option value="Creche" {{ $tenant->category === 'Creche' ? 'selected' : '' }}>Creche & Childcare</option>
                        </select>
                    </div>

                    <!-- Subscription Plan -->
                    <div>
                        <label for="subscription_plan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SaaS Subscription Plan *</label>
                        <select id="subscription_plan" name="subscription_plan" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            <option value="starter" {{ $tenant->subscription_plan === 'starter' ? 'selected' : '' }}>Starter ($49/month)</option>
                            <option value="pro" {{ $tenant->subscription_plan === 'pro' ? 'selected' : '' }}>Pro ($99/month)</option>
                            <option value="enterprise" {{ $tenant->subscription_plan === 'enterprise' ? 'selected' : '' }}>Enterprise Plan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Subscription Status -->
                    <div>
                        <label for="subscription_status" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subscription Status *</label>
                        <select id="subscription_status" name="subscription_status" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                            <option value="active" {{ $tenant->subscription_status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="trialing" {{ $tenant->subscription_status === 'trialing' ? 'selected' : '' }}>Trialing</option>
                            <option value="suspended" {{ $tenant->subscription_status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="cancelled" {{ $tenant->subscription_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Brand Color -->
                    <div>
                        <label for="brand_color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Brand Hex Color</label>
                        <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', $tenant->brand_color ?? '#0056A2') }}"
                               class="w-full h-11 p-1 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                    </div>
                </div>

                <!-- Tagline -->
                <div>
                    <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tagline</label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $tenant->tagline) }}"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                </div>

                <!-- Toggles -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Active Tenant Account</div>
                            <div class="text-[10px] text-slate-500">Allow customers to book courts.</div>
                        </div>
                        <input type="checkbox" name="is_active" value="1" {{ $tenant->is_active ? 'checked' : '' }} class="w-5 h-5 accent-sltds-green rounded">
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Stage 1 Customer Directory</div>
                            <div class="text-[10px] text-slate-500">Visible on public SLTDS directory.</div>
                        </div>
                        <input type="checkbox" name="is_public" value="1" {{ $tenant->is_public ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded">
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-4">
                    <a href="{{ route('superadmin.tenants') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                        Save Tenant Changes &rarr;
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.super_admin>
