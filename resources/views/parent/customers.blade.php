<x-layouts.parent title="Live Customer Directory | BookFlow by SLTDS">

    <!-- Page Header Hero (SLTDS Corporate Branding) -->
    <section class="py-16 bg-linear-to-br from-slate-900 via-sltds-endeavour to-slate-950 text-white border-b border-sltds-sky/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
            <div>
                <span class="inline-block px-4.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-sltds-sky/20 text-sltds-sky border border-sltds-sky/40 mb-4 shadow-xs">
                    Live Customer Sites
                </span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-black leading-tight text-white">Facilities Powered by SLTDS</h1>
            <p class="text-slate-200 text-sm sm:text-base max-w-2xl mx-auto pt-1">
                Explore real, active client sites running on our court booking software.
            </p>
        </div>
    </section>

    <!-- Directory & Filter Tabs Section -->
    <section class="py-16 bg-slate-50 text-slate-900 min-h-150">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- Category Filter Bar -->
            <div class="flex flex-wrap items-center justify-center gap-2 border-b border-slate-200 pb-6">
                <a href="{{ route('parent.customers', ['category' => 'all']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $selectedCategory === 'all' ? 'bg-sltds-endeavour text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                    All Categories ({{ \App\Models\Tenant::where('is_active', '=', true, 'and')->where('is_public', '=', true, 'and')->count(['*']) }})
                </a>
                
                @foreach($categories as $catKey => $catLabel)
                    @if($catKey !== 'all')
                        @php
                            $catCount = \App\Models\Tenant::where('is_active', '=', true, 'and')->where('is_public', '=', true, 'and')->where('category', '=', $catKey, 'and')->count(['*']);
                        @endphp
                        <a href="{{ route('parent.customers', ['category' => $catKey]) }}"
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $selectedCategory === $catKey ? 'bg-sltds-endeavour text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}">
                            {{ $catLabel }} ({{ $catCount }})
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Tenants Grid (DB Driven) -->
            @if($tenants->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 max-w-md mx-auto space-y-3 shadow-xs">
                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <div class="text-base font-bold text-slate-900">No facilities found</div>
                    <p class="text-xs text-slate-500">There are currently no active public venues in this category.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($tenants as $tenant)
                        <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-6 flex flex-col justify-between hover:border-sltds-endeavour hover:shadow-xl transition-all group">
                            
                            <div class="space-y-4">
                                <!-- Top Header -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-white text-xl shadow-md group-hover:scale-105 transition-transform" style="background-color: {{ $tenant->brand_color ?? '#0056A2' }}">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-slate-900 group-hover:text-sltds-endeavour transition-colors">{{ $tenant->name }}</h3>
                                            <span class="text-xs text-slate-500 font-medium block">{{ $tenant->location ?? 'Sri Lanka' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Badge Tags -->
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $tenant->category }}
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase bg-emerald-50 text-sltds-green border border-emerald-200">
                                        Active Site
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-xs text-slate-600 leading-relaxed">
                                    {{ $tenant->tagline ?? $tenant->description ?? 'Live customer site running on SLT Digital Services court booking software.' }}
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="pt-5 border-t border-slate-100 mt-5 space-y-2">
                                <a href="{{ url('/' . $tenant->slug . '/book') }}"
                                   class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                                    Launch Customer Site &rarr;
                                </a>
                                <div class="text-[10px] text-center text-slate-500 font-mono font-medium">
                                    Domain: {{ $tenant->domain ?? ($tenant->slug . '.localhost') }}
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

</x-layouts.parent>
