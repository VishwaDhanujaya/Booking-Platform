<x-layouts.super_admin title="Provision Tenant | SLTDS Super Admin">

    <div class="max-w-3xl mx-auto space-y-8" x-data="{
        name: '{{ old('name', '') }}',
        slug: '{{ old('slug', '') }}',
        generateSlug() {
            if (!this.slug || this.slug === '') {
                this.slug = this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            }
        }
    }">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 shadow-xs">
                        Manual Provisioning
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Provision New Tenant</h1>
                <p class="text-xs text-slate-500">Staff-assisted onboarding flow for creating a new facility workspace & initial owner credentials.</p>
            </div>
            
            <a href="{{ route('superadmin.tenants') }}" class="text-xs font-bold text-slate-600 hover:text-sltds-endeavour transition-colors">
                &larr; Back to Directory
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-lg">
            <form action="{{ route('superadmin.tenants.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Section 1: Tenant Business Details -->
                <div class="space-y-6">
                    <div class="border-b border-slate-200 pb-3">
                        <h2 class="text-base font-bold text-slate-900">1. Business & Workspace Info</h2>
                        <p class="text-xs text-slate-500">Core details for site URL and directory listing.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Facility Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility Name *</label>
                            <input type="text" id="name" name="name" x-model="name" @input="generateSlug()" required
                                   placeholder="e.g. Kandy Badminton Hub"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-medium">
                            @error('name')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subdomain / Slug -->
                        <div>
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Subdomain / Slug *</label>
                            <input type="text" id="slug" name="slug" x-model="slug" required
                                   placeholder="e.g. kandy-badminton"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-mono font-bold">
                            <p class="text-[10px] text-slate-400 mt-1">Tenant URL will be: <code x-text="(slug || 'slug') + '.localhost'"></code></p>
                            @error('slug')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Category Tag *</label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-semibold">
                                <option value="Sports Venues & Facilities">Sports Venues & Facilities</option>
                                <option value="Group Classes & Courses">Group Classes & Courses</option>
                                <option value="Fitness Equipment">Fitness Equipment & Gyms</option>
                                <option value="Creche">Creche & Childcare</option>
                            </select>
                        </div>

                        <!-- Subscription Plan -->
                        <div>
                            <label for="subscription_plan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">SaaS Subscription Plan *</label>
                            <select id="subscription_plan" name="subscription_plan" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-semibold">
                                <option value="pro" selected>Pro Tier (LKR 35,000/month)</option>
                                <option value="starter">Starter Tier (LKR 15,000/month)</option>
                                <option value="enterprise">Enterprise Plan (LKR 75,000/month)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Tagline -->
                        <div>
                            <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tagline</label>
                            <input type="text" id="tagline" name="tagline" value="{{ old('tagline') }}"
                                   placeholder="e.g. Premier Indoor Badminton Courts in Kandy"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-medium">
                        </div>

                        <!-- Brand Color -->
                        <div>
                            <label for="brand_color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Brand Hex Color</label>
                            <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', '#0056A2') }}"
                                   class="w-full h-11 p-1 rounded-xl bg-slate-50 border border-slate-200 cursor-pointer">
                        </div>
                    </div>

                    <!-- Directory Public Toggle -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-slate-900">Publish to Public Customer Directory</div>
                            <div class="text-[11px] text-slate-500">Make this tenant visible on the public showcase directory.</div>
                        </div>
                        <input type="checkbox" name="is_public" value="1" checked class="w-5 h-5 accent-sltds-endeavour rounded">
                    </div>
                </div>

                <!-- Section 2: Initial Owner Credentials -->
                <div class="space-y-6 pt-4 border-t border-slate-200">
                    <div class="border-b border-slate-200 pb-3">
                        <h2 class="text-base font-bold text-slate-900">2. Initial Tenant Owner Account</h2>
                        <p class="text-xs text-slate-500">Staff will provide these credentials to the client for their first login.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Owner Name -->
                        <div>
                            <label for="owner_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Owner Full Name *</label>
                            <input type="text" id="owner_name" name="owner_name" required value="{{ old('owner_name') }}"
                                   placeholder="e.g. Kasun Jayawardena"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-medium">
                            @error('owner_name')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Email -->
                        <div>
                            <label for="owner_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Owner Work Email *</label>
                            <input type="email" id="owner_email" name="owner_email" required value="{{ old('owner_email') }}"
                                   placeholder="e.g. owner@kandybadminton.lk"
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour font-medium">
                            @error('owner_email')
                                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Owner Password -->
                    <div>
                        <label for="owner_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Initial Password *</label>
                        <input type="text" id="owner_password" name="owner_password" required value="password123"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-mono font-bold focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                        <p class="text-[10px] text-slate-500 mt-1">Default temporary password set to <code>password123</code>. The owner can change this in their settings.</p>
                        @error('owner_password')
                            <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Action -->
                <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-4">
                    <a href="{{ route('superadmin.tenants') }}" class="px-5 py-3 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                        Provision Facility Tenant &rarr;
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.super_admin>
