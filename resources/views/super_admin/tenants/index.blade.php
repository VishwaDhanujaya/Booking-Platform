<x-layouts.super_admin title="Tenant Directory | SLTDS Super Admin">

    <div class="space-y-8">

        <!-- CLEAN OPERATIONAL PAGE HEADER (VADEL Minimalist Display Typography) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/15">
            <div class="space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-md">
                    TENANT DIRECTORY & MANAGEMENT
                </h1>
                <p class="text-xs text-slate-300 font-normal leading-relaxed">
                    Manage every tenant subscription, toggle active status, set public directory visibility, or access tenant user accounts.
                </p>
            </div>
            
            <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-6 py-3 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] hover:scale-105 active:scale-[0.98]">
                + Provision New Tenant &rarr;
            </a>
        </div>

        <!-- FILTER & SEARCH BAR (Specular Liquid Glass Card) -->
        <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-3xl p-5 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_20px_40px_rgba(0,0,0,0.7)]">
            <form action="{{ route('superadmin.tenants') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                
                <!-- Search Input -->
                <div class="sm:col-span-6">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search tenant name, slug, or domain..."
                           class="w-full px-5 py-3 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 text-xs focus:outline-none focus:bg-white/20 focus:border-white/60 transition-all font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                </div>

                <!-- Plan Filter -->
                <div class="sm:col-span-3">
                    <select name="plan" onchange="this.form.submit()"
                            class="w-full px-5 py-3 rounded-full bg-slate-900/90 border border-white/20 text-white text-xs focus:outline-none focus:border-white/60 transition-all font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        <option value="all" class="bg-slate-950 text-white">All SaaS Plans</option>
                        <option value="starter" class="bg-slate-950 text-white" {{ request('plan') === 'starter' ? 'selected' : '' }}>Starter Tier ($49/mo)</option>
                        <option value="pro" class="bg-slate-950 text-white" {{ request('plan') === 'pro' ? 'selected' : '' }}>Pro Tier ($99/mo)</option>
                        <option value="enterprise" class="bg-slate-950 text-white" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise Plan</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-3">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-5 py-3 rounded-full bg-slate-900/90 border border-white/20 text-white text-xs focus:outline-none focus:border-white/60 transition-all font-bold shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        <option value="all" class="bg-slate-950 text-white">All Statuses</option>
                        <option value="active" class="bg-slate-950 text-white" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" class="bg-slate-950 text-white" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

            </form>
        </div>

        <!-- HIGH-DENSITY OPERATIONAL TENANT TABLE (Specular Liquid Glass) -->
        <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.8)]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-950/90 text-slate-300 uppercase tracking-wider font-black text-[10px] border-b border-white/15">
                        <tr>
                            <th class="py-4 px-6">Tenant / Subdomain</th>
                            <th class="py-4 px-4">Category</th>
                            <th class="py-4 px-4">Plan & Status</th>
                            <th class="py-4 px-4">Directory Visibility</th>
                            <th class="py-4 px-4">Courts / Bookings</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300 font-medium">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-white/5 transition-colors">
                                
                                <!-- Tenant Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-white text-sm shadow-md shrink-0 border border-white/20" style="background-color: {{ $tenant->brand_color ?? '#0056A2' }}">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-white text-sm tracking-tight">{{ $tenant->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono font-medium">
                                                {{ $tenant->domain ?? ($tenant->slug . '.' . parse_url(config('app.url'), PHP_URL_HOST)) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/10 backdrop-blur-xl text-slate-200 border border-white/20">
                                        {{ $tenant->category }}
                                    </span>
                                </td>

                                <!-- Plan & Status -->
                                <td class="py-4 px-4 space-y-1">
                                    <div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/15 text-white border border-white/25">
                                            {{ $tenant->subscription_plan }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($tenant->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30">Suspended</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Directory Visibility -->
                                <td class="py-4 px-4">
                                    <form action="{{ route('superadmin.tenants.toggle-public', $tenant->id) }}" method="POST">
                                        @csrf
                                        @if($tenant->is_public)
                                            <button type="submit" title="Click to hide from public directory" class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 hover:bg-rose-500/30 hover:text-rose-200 transition-colors cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Public Directory
                                            </button>
                                        @else
                                            <button type="submit" title="Click to publish to customer directory" class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/10 text-slate-300 border border-white/20 hover:bg-emerald-500/30 hover:text-emerald-200 transition-colors cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Private (Hidden)
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                <!-- Counts -->
                                <td class="py-4 px-4 text-xs font-medium">
                                    <div class="font-black text-white">{{ $tenant->courts_count ?? 0 }} Courts</div>
                                    <div class="text-slate-400 text-[10px]">{{ $tenant->bookings_count ?? 0 }} Total Bookings</div>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right space-x-1.5">
                                    
                                    <!-- Manage Users Link -->
                                    <a href="{{ route('superadmin.tenants.users', $tenant->id) }}" class="px-3.5 py-1.5 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                                        Users &rarr;
                                    </a>

                                    <!-- Edit Link -->
                                    <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="px-3.5 py-1.5 rounded-full text-xs font-black text-slate-300 bg-white/10 hover:bg-white/20 border border-white/15 transition-all">
                                        Edit
                                    </a>

                                    <!-- Suspend / Reactivate Button -->
                                    <form action="{{ route('superadmin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($tenant->is_active)
                                            <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-black text-rose-300 bg-rose-500/20 border border-rose-400/30 hover:bg-rose-500/30 transition-colors cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Suspend
                                            </button>
                                        @else
                                            <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-black text-emerald-300 bg-emerald-500/20 border border-emerald-400/30 hover:bg-emerald-500/30 transition-colors cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Reactivate
                                            </button>
                                        @endif
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    No tenants match your search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.super_admin>
