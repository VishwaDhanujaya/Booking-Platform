<x-layouts.super_admin title="Provision Tenant | SLTDS Super Admin">

    <div class="max-w-4xl mx-auto space-y-8" x-data="{
        name: '{{ old('name', '') }}',
        slug: '{{ old('slug', '') }}',
        generateSlug() {
            if (!this.slug || this.slug === '') {
                this.slug = this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            }
        }
    }">

        <!-- CLEAN OPERATIONAL PAGE HEADER -->
        <div class="flex items-center justify-between pb-6 border-b border-slate-200">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-950 tracking-tight">Provision New Facility Tenant</h1>
                <p class="text-xs text-slate-500 font-medium">Staff-assisted onboarding: create a new facility workspace and initial owner account credentials.</p>
            </div>
            <a href="{{ route('superadmin.tenants') }}" class="px-5 py-2.5 rounded-full text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                &larr; Back to Directory
            </a>
        </div>

        <!-- FORM CARD (Spacious White Card - Max Use of Space) -->
        <div class="bg-white border border-slate-200/80 rounded-3xl p-8 sm:p-10 shadow-xl space-y-10">
            <form action="{{ route('superadmin.tenants.store') }}" method="POST" class="space-y-8 text-xs">
                @csrf

                <!-- Section 1: Business & Workspace Info -->
                <div class="space-y-6">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-black text-slate-950 tracking-tight">1. Business & Workspace Info</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Core details for site subdomain URL and public directory listing.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Facility Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Facility Name *</label>
                            <input type="text" id="name" name="name" x-model="name" @input="generateSlug()" required
                                   placeholder="e.g. Kandy Badminton Hub"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 placeholder-slate-400 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            @error('name')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subdomain / Slug -->
                        <div class="space-y-2">
                            <label for="slug" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Subdomain / Slug *</label>
                            <input type="text" id="slug" name="slug" x-model="slug" required
                                   placeholder="e.g. kandy-badminton"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 placeholder-slate-400 font-mono font-bold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            <p class="text-[10px] text-slate-400">URL: <span class="text-slate-700 font-mono font-bold" x-text="(slug || 'slug') + '.{{ parse_url(config('app.url'), PHP_URL_HOST) }}'"></span></p>
                            @error('slug')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Category -->
                        <div class="space-y-2">
                            <label for="category" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Category Tag *</label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-semibold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                                <option value="Sports Venues & Facilities">Sports Venues & Facilities</option>
                                <option value="Group Classes & Courses">Group Classes & Courses</option>
                                <option value="Fitness Equipment">Fitness Equipment & Gyms</option>
                                <option value="Creche">Creche & Childcare</option>
                            </select>
                        </div>

                        <!-- Subscription Plan -->
                        <div class="space-y-2">
                            <label for="subscription_plan" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">SaaS Subscription Plan *</label>
                            <select id="subscription_plan" name="subscription_plan" required
                                    class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-semibold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                                <option value="pro" selected>Pro Tier ($99/month)</option>
                                <option value="starter">Starter Tier ($49/month)</option>
                                <option value="enterprise">Enterprise Plan (Custom)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Tagline -->
                        <div class="space-y-2">
                            <label for="tagline" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Tagline</label>
                            <input type="text" id="tagline" name="tagline" value="{{ old('tagline') }}"
                                   placeholder="e.g. Premier Indoor Courts in Kandy"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 placeholder-slate-400 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                        </div>

                        <!-- Brand Color -->
                        <div class="space-y-2">
                            <label for="brand_color" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Brand Hex Color</label>
                            <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', '#0056A2') }}"
                                   class="w-full h-11 p-1.5 rounded-2xl bg-slate-50 border border-slate-200 cursor-pointer">
                        </div>
                    </div>

                    <!-- Directory Toggle -->
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-200">
                        <div>
                            <div class="text-xs font-black text-slate-950">Publish to Public Customer Directory</div>
                            <div class="text-[11px] text-slate-500">Make this tenant visible on the public venue showcase.</div>
                        </div>
                        <input type="checkbox" name="is_public" value="1" checked class="w-5 h-5 accent-slate-950 rounded cursor-pointer">
                    </div>
                </div>

                <!-- Section 2: Initial Owner Account -->
                <div class="space-y-6 pt-2">
                    <div class="pb-4 border-b border-slate-100">
                        <h2 class="text-base font-black text-slate-950 tracking-tight">2. Initial Tenant Owner Account</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Staff will share these credentials with the client on first handoff.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Owner Name -->
                        <div class="space-y-2">
                            <label for="owner_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Owner Full Name *</label>
                            <input type="text" id="owner_name" name="owner_name" required value="{{ old('owner_name') }}"
                                   placeholder="e.g. Kasun Jayawardena"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 placeholder-slate-400 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            @error('owner_name')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Owner Email -->
                        <div class="space-y-2">
                            <label for="owner_email" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Owner Work Email *</label>
                            <input type="email" id="owner_email" name="owner_email" required value="{{ old('owner_email') }}"
                                   placeholder="e.g. owner@kandybadminton.lk"
                                   class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 placeholder-slate-400 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            @error('owner_email')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Owner Password -->
                    <div class="space-y-2">
                        <label for="owner_password" class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Initial Password *</label>
                        <input type="text" id="owner_password" name="owner_password" required value="password123"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-mono font-bold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                        <p class="text-[10px] text-slate-400">Default temporary password. The owner can change this from their account settings after first login.</p>
                        @error('owner_password')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Submit Actions -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                    <a href="{{ route('superadmin.tenants') }}" class="px-5 py-3 rounded-full text-xs font-bold text-slate-500 hover:text-slate-950 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3.5 rounded-full text-xs font-black text-white bg-slate-950 hover:bg-slate-800 shadow-lg transition-all hover:scale-105">
                        Provision Facility Tenant &rarr;
                    </button>
                </div>

            </form>
        </div>

    </div>

</x-layouts.super_admin>
