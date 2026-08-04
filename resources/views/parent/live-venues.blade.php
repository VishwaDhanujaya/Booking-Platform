<x-layouts.parent title="Live Venues Directory | BookFlow by SLTDS">

    <!-- PAGE HERO CANVAS (Features Inspired Minimalist Dark VADEL Aesthetic) -->
    <div class="p-3 sm:p-4 bg-slate-50">
        <section class="relative rounded-4xl sm:rounded-5xl overflow-hidden py-16 sm:py-24 flex flex-col justify-center items-center p-6 sm:p-10 shadow-2xl bg-slate-950 text-white">
            
            <!-- High-Res Background Image -->
            <img src="/images/vadel_hero_court.png" alt="Indoor Sports Court Facility" class="absolute inset-0 w-full h-full object-cover z-0 opacity-75 scale-105">
            <div class="absolute inset-0 bg-linear-to-b from-slate-950/50 via-slate-950/30 to-slate-950/75 z-0"></div>

            <!-- Header Content -->
            <div class="relative z-10 text-center space-y-4 sm:space-y-6 max-w-4xl mx-auto px-4">
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black italic tracking-tighter text-white uppercase drop-shadow-2xl leading-tight">
                    Live Sports Venues & Operating Facilities
                </h1>

                <p class="text-xs sm:text-base text-slate-200 max-w-2xl mx-auto font-normal leading-relaxed tracking-wide drop-shadow-md">
                    Explore real, active client facilities running on BookFlow court booking software.
                </p>
            </div>

        </section>
    </div>

    <!-- DIRECTORY & FILTER TABS SECTION (DB Driven Grid) -->
    <section class="py-20 bg-slate-50 text-slate-900 border-b border-slate-200 min-h-150">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- Category Filter Bar (Liquid Glass Style Filter Pills) -->
            <div class="flex flex-wrap items-center justify-center gap-2.5">
                <a href="{{ route('parent.live-venues', ['category' => 'all']) }}"
                   class="px-5 py-2.5 rounded-full text-xs font-bold transition-all shadow-xs {{ $selectedCategory === 'all' ? 'bg-slate-950 text-white font-black shadow-md border border-slate-900' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    All Venues ({{ \App\Models\Tenant::where('is_active', '=', true, 'and')->where('is_public', '=', true, 'and')->count(['*']) }})
                </a>
                
                @foreach($categories as $catKey => $catLabel)
                    @if($catKey !== 'all')
                        @php
                            $catCount = \App\Models\Tenant::where('is_active', '=', true, 'and')->where('is_public', '=', true, 'and')->where('category', '=', $catKey, 'and')->count(['*']);
                        @endphp
                        <a href="{{ route('parent.live-venues', ['category' => $catKey]) }}"
                           class="px-5 py-2.5 rounded-full text-xs font-bold transition-all shadow-xs {{ $selectedCategory === $catKey ? 'bg-slate-950 text-white font-black shadow-md border border-slate-900' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                            {{ $catLabel }} ({{ $catCount }})
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Tenants Grid (DB Driven Showcase Cards) -->
            @if($tenants->isEmpty())
                <div class="text-center py-20 bg-white rounded-4xl border border-slate-200 max-w-md mx-auto space-y-3 shadow-xl">
                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <div class="text-lg font-black text-slate-900">No facilities found</div>
                    <p class="text-xs text-slate-500">There are currently no active public venues in this category.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tenants as $tenant)
                        @php
                            $catLower = strtolower($tenant->category ?? '');
                            if (str_contains($catLower, 'badminton') || str_contains($catLower, 'squash')) {
                                $coverImg = $tenant->hero_image_url ?: '/images/courts/badminton.png';
                            } elseif (str_contains($catLower, 'padel') || str_contains($catLower, 'tennis') || str_contains($catLower, 'racquet')) {
                                $coverImg = $tenant->hero_image_url ?: '/images/padel_tennis_arena.png';
                            } elseif (str_contains($catLower, 'gym') || str_contains($catLower, 'fitness') || str_contains($catLower, 'health')) {
                                $coverImg = $tenant->hero_image_url ?: '/images/gym_fitness_studio.png';
                            } else {
                                $coverImg = $tenant->hero_image_url ?: '/images/vadel_hero_court.png';
                            }
                        @endphp
                        
                        <div class="bg-white border border-slate-200/80 rounded-4xl overflow-hidden shadow-xl hover:border-slate-400 transition-all duration-300 flex flex-col justify-between group">
                            
                            <div>
                                <!-- Card Image Header -->
                                <div class="relative overflow-hidden h-52">
                                    <img src="{{ $coverImg }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-linear-to-t from-slate-950/80 via-transparent to-transparent"></div>
                                    <span class="absolute bottom-4 left-4 px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/20 backdrop-blur-xl text-white border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]">
                                        {{ $tenant->category }}
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-white text-lg shadow-md group-hover:scale-105 transition-transform shrink-0" style="background-color: {{ $tenant->brand_color ?? '#0056A2' }}">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-black text-slate-900 tracking-tight group-hover:text-slate-950 transition-colors">{{ $tenant->name }}</h3>
                                            <span class="text-xs text-slate-500 font-medium block">{{ $tenant->location ?? 'Sri Lanka' }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-2 pt-1">
                                        {{ $tenant->tagline ?? $tenant->description ?? 'Live customer facility powered by BookFlow court management platform.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Card Action -->
                            <div class="p-6 pt-0 border-t border-slate-100 mt-4 space-y-2">
                                <a href="{{ \App\Services\TenantResolver::tenantUrl('booking.index', [], $tenant) }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-3 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs">
                                    Visit Venue Site &rarr;
                                </a>
                                <div class="text-[10px] text-center text-slate-400 font-mono font-medium truncate pt-1">
                                    Domain: {{ $tenant->domain ?? ($tenant->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST)) }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    <!-- BOTTOM DEMO CTA SECTION -->
    <section class="py-24 bg-slate-950 text-white relative overflow-hidden">
        <img src="/images/contact_stadium_lights.png" alt="Sports Complex Lounge" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-105">
        <div class="absolute inset-0 bg-linear-to-t from-slate-950/60 via-slate-950/30 to-slate-950/40"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 text-center space-y-6">
            <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight uppercase italic">Ready to list your sports venue?</h2>
            <p class="text-xs sm:text-sm text-slate-200 max-w-xl mx-auto font-normal leading-relaxed">
                Register your facility today and join the network of growing sports clubs powered by BookFlow.
            </p>
            <div class="pt-4">
                <a href="{{ route('tenant.register') }}" class="inline-flex items-center px-8 py-4 rounded-full text-xs sm:text-sm font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-105">
                    Register Venue &rarr;
                </a>
            </div>
        </div>
    </section>

</x-layouts.parent>
