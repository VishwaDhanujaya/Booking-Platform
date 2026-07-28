<x-layouts.admin title="Site Settings & Branding | Tenant Admin">

    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Site Customization & Branding</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Site Settings & Customization</h1>
                <p class="text-xs text-slate-500 mt-1">Customize your venue website's branding, contact info, hero content, notices, navigation, and custom domain.</p>
            </div>
            
            <a href="{{ route('booking.index') }}" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 shadow-xs transition-colors">
                View Live Site ↗
            </a>
        </div>

        <!-- Form Card -->
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-8">
            @csrf

            <!-- SECTION 1: BRANDING & VISUAL IDENTITIES -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-sltds-endeavour"></span>
                            1. Branding & Visual Identity
                        </h2>
                        <p class="text-xs text-slate-500">Upload your logo, brand primary color, and favicon directly for your live website.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Primary Brand Color -->
                    <div>
                        <label for="brand_color" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Primary Brand Color *</label>
                        <div class="flex items-center gap-3">
                            <input type="color" id="brand_color" name="brand_color" value="{{ old('brand_color', $tenant->brand_color ?? '#0056A2') }}"
                                   class="w-14 h-11 p-1 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 cursor-pointer">
                            <input type="text" value="{{ old('brand_color', $tenant->brand_color ?? '#0056A2') }}" readonly
                                   class="grow px-4 py-2.5 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 text-xs font-mono font-bold">
                        </div>
                        @error('brand_color')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Favicon Upload -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Favicon File Upload (Optional)</label>
                        <input type="file" id="favicon_file" name="favicon_file" accept="image/*,.ico"
                               class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-white hover:file:bg-slate-900 cursor-pointer">
                        @if($tenant->favicon_url)
                            <div class="flex items-center gap-2 pt-1">
                                <span class="text-[11px] font-semibold text-slate-500">Current Favicon:</span>
                                <img src="{{ $tenant->favicon_url }}" alt="Favicon" class="w-5 h-5 object-contain bg-slate-100 p-0.5 rounded border border-slate-200">
                            </div>
                        @endif
                        @error('favicon_file')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Logo File Upload -->
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Venue Logo Upload (Optional)</label>
                    
                    <input type="file" id="logo_file" name="logo_file" accept="image/*"
                           class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sltds-endeavour file:text-white hover:file:bg-sltds-sky cursor-pointer">
                    <p class="text-[11px] text-slate-500">Upload PNG, JPG, WebP or SVG logo file directly from your device. If omitted, stylized initial badge is rendered.</p>
                    @error('logo_file')
                        <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                    @enderror

                    @if($tenant->logo_url)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-slate-600">Current Venue Logo Preview:</span>
                                <img src="{{ $tenant->logo_url }}" alt="Venue Logo" class="h-10 w-auto object-contain bg-white p-1 rounded-lg border border-slate-200 shadow-xs">
                            </div>
                            <label class="inline-flex items-center gap-2 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-xl cursor-pointer hover:bg-rose-100 transition-colors">
                                <input type="checkbox" name="remove_logo" value="1" class="accent-rose-600 rounded">
                                Remove Logo
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SECTION 2: BUSINESS INFORMATION & CONTACT -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sltds-green"></span>
                        2. Business Info & Contact Details
                    </h2>
                    <p class="text-xs text-slate-500">Displayed in your site header, footer, contact page, and tax receipts.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Facility Name -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility Name *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $tenant->name) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                        @error('name')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tagline -->
                    <div>
                        <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Tagline</label>
                        <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $tenant->tagline) }}"
                               placeholder="e.g. Premier Badminton & Tennis Arena"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Contact Phone</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $tenant->phone ?? '+94 11 234 5678') }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                        @error('phone')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Support Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $tenant->email) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                        @error('email')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opening Hours -->
                    <div>
                        <label for="opening_hours" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Opening Hours</label>
                        <input type="text" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $tenant->opening_hours ?? 'Mon - Sun: 6:00 AM - 10:00 PM') }}"
                               placeholder="e.g. Mon-Sun 6am-10pm"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Physical Address</label>
                    <input type="text" id="address" name="address" value="{{ old('address', $tenant->address) }}"
                           placeholder="e.g. 45 Maitland Crescent, Colombo 00700"
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Facility About & Overview</label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">{{ old('description', $tenant->description) }}</textarea>
                </div>
            </div>

            <!-- SECTION 3: HOMEPAGE HERO CONTENT -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-sltds-sky"></span>
                        3. Homepage Hero Banner Content
                    </h2>
                    <p class="text-xs text-slate-500">Headlines and background image displayed at the top of your venue booking page.</p>
                </div>

                <div class="space-y-4">
                    <!-- Hero Headline -->
                    <div>
                        <label for="hero_headline" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Hero Headline</label>
                        <input type="text" id="hero_headline" name="hero_headline" value="{{ old('hero_headline', $tenant->hero_headline ?? ('Reserve Premium ' . $tenant->name . ' Courts Online')) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <!-- Hero Subheading -->
                    <div>
                        <label for="hero_subheading" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Hero Subheading</label>
                        <textarea id="hero_subheading" name="hero_subheading" rows="2"
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">{{ old('hero_subheading', $tenant->hero_subheading ?? ('Instant real-time booking for ' . $tenant->name . ' with automated lighting and court scheduling.')) }}</textarea>
                    </div>

                    <!-- Hero Image File Upload -->
                    <div class="space-y-3 pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">Hero Banner Photo Upload (Optional)</label>
                        
                        <input type="file" id="hero_image_file" name="hero_image_file" accept="image/*"
                               class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sltds-endeavour file:text-white hover:file:bg-sltds-sky cursor-pointer">
                        <p class="text-[11px] text-slate-500">Upload high-resolution hero photo for your homepage banner.</p>
                        @error('hero_image_file')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror

                        @if($tenant->hero_image_url)
                            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200">
                                <span class="text-xs font-bold text-slate-600">Current Hero Image Preview:</span>
                                <img src="{{ $tenant->hero_image_url }}" alt="Hero Banner" class="h-16 w-32 object-cover rounded-xl border border-slate-200 shadow-xs">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 4: NOTICES & PROMO LINKS (REPEATABLE STRIP) -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                            4. Notices & Promo Link Banner
                        </h2>
                        <p class="text-xs text-slate-500">Repeatable list of announcements or quick links (e.g. "Court Booking Rules", "Redeem Voucher").</p>
                    </div>
                </div>

                @php
                    $existingNotices = $tenant->notices ?? [
                        ['title' => 'Court Booking Rules', 'link' => '#', 'icon' => 'document-text'],
                        ['title' => 'Redeem Member Voucher', 'link' => '#', 'icon' => 'ticket'],
                    ];
                @endphp

                <div class="space-y-4">
                    @for($i = 0; $i < 4; $i++)
                        @php
                            $n = $existingNotices[$i] ?? ['title' => '', 'link' => '', 'icon' => 'info'];
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                            <div class="sm:col-span-5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Notice Title #{{ $i + 1 }}</label>
                                <input type="text" name="notices[{{ $i }}][title]" value="{{ old("notices.{$i}.title", $n['title']) }}"
                                       placeholder="e.g. Non-marking Shoes Required"
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                            </div>
                            <div class="sm:col-span-5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Target Link URL or #</label>
                                <input type="text" name="notices[{{ $i }}][link]" value="{{ old("notices.{$i}.link", $n['link']) }}"
                                       placeholder="https://... or #"
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">Icon</label>
                                <select name="notices[{{ $i }}][icon]" class="w-full px-3 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                                    <option value="info" {{ ($n['icon'] ?? '') === 'info' ? 'selected' : '' }}>Info</option>
                                    <option value="document-text" {{ ($n['icon'] ?? '') === 'document-text' ? 'selected' : '' }}>Rules</option>
                                    <option value="ticket" {{ ($n['icon'] ?? '') === 'ticket' ? 'selected' : '' }}>Voucher</option>
                                </select>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- SECTION 5: NAVIGATION TOGGLES -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        5. Navigation Visibility Controls
                    </h2>
                    <p class="text-xs text-slate-500">Toggle which sections appear in your top website navigation bar.</p>
                </div>

                @php
                    $nav = $tenant->nav_settings ?? [
                        'show_courts' => true,
                        'show_pricing' => true,
                        'show_passes' => true,
                        'show_rules' => true,
                        'show_contact' => true,
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Courts Grid</span>
                        <input type="checkbox" name="nav_settings[show_courts]" value="1" {{ !empty($nav['show_courts']) ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded cursor-pointer">
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Pricing & Rates</span>
                        <input type="checkbox" name="nav_settings[show_pricing]" value="1" {{ !empty($nav['show_pricing']) ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded cursor-pointer">
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Member Passes</span>
                        <input type="checkbox" name="nav_settings[show_passes]" value="1" {{ !empty($nav['show_passes']) ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded cursor-pointer">
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Facility Rules</span>
                        <input type="checkbox" name="nav_settings[show_rules]" value="1" {{ !empty($nav['show_rules']) ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded cursor-pointer">
                    </div>
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Contact & Map</span>
                        <input type="checkbox" name="nav_settings[show_contact]" value="1" {{ !empty($nav['show_contact']) ? 'checked' : '' }} class="w-5 h-5 accent-sltds-endeavour rounded cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- SECTION 6 & 7: CATEGORY TAG & CUSTOM DOMAIN -->
            <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
                <div class="border-b border-slate-200 pb-3">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        6 & 7. Directory Category & Custom Domain
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Directory Category Tag -->
                    <div>
                        <label for="category" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Directory Category Tag *</label>
                        <select id="category" name="category" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium focus:outline-none focus:border-sltds-endeavour">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $tenant->category ?? '') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Drives your facility's filter tag in the SLTDS customer directory.</p>
                        @error('category')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Custom Domain -->
                    <div>
                        <label for="custom_domain" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Custom Domain Name</label>
                        <input type="text" id="custom_domain" name="custom_domain" value="{{ old('custom_domain', $tenant->custom_domain) }}"
                               placeholder="e.g. booking.colombocourts.lk"
                               class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono focus:outline-none focus:border-sltds-endeavour">
                        <p class="text-[11px] text-slate-500 mt-1">Point your domain CNAME record to <code>cname.sltdigital.com</code>.</p>
                        @error('custom_domain')
                            <p class="text-rose-600 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-4">
                <button type="submit" :disabled="submitting" class="w-full sm:w-auto px-8 py-4 rounded-xl text-xs font-black text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-lg shadow-sltds-endeavour/20 transition-all hover:scale-[1.01] disabled:opacity-50 cursor-pointer">
                    Save & Publish Site Settings &rarr;
                </button>
            </div>

        </form>

    </div>

</x-layouts.admin>
