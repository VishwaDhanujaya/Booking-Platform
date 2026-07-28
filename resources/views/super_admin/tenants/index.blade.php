<x-layouts.super_admin title="Tenant Directory | SLTDS Super Admin">

    <div class="space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 shadow-xs">
                        Tenant Control
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tenant Directory & Management</h1>
                <p class="text-xs text-slate-500">Manage every tenant subscription, toggle active status, set public directory visibility, or access tenant admin.</p>
            </div>
            
            <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-4.5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all">
                + Provision New Tenant
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border-2 border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <form action="{{ route('superadmin.tenants') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                
                <!-- Search Input -->
                <div class="sm:col-span-6">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search tenant name, slug, or domain..."
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                </div>

                <!-- Plan Filter -->
                <div class="sm:col-span-3">
                    <select name="plan" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                        <option value="all">All Plans</option>
                        <option value="starter" {{ request('plan') === 'starter' ? 'selected' : '' }}>Starter ($49/mo)</option>
                        <option value="pro" {{ request('plan') === 'pro' ? 'selected' : '' }}>Pro ($99/mo)</option>
                        <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-3">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs focus:outline-none focus:bg-white focus:border-sltds-endeavour">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

            </form>
        </div>

        <!-- Tenant List Table -->
        <div class="bg-white border-2 border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-4 px-6">Tenant / Subdomain</th>
                            <th class="py-4 px-4">Category Tag</th>
                            <th class="py-4 px-4">Plan & Status</th>
                            <th class="py-4 px-4">Directory Visibility</th>
                            <th class="py-4 px-4">Courts / Bookings</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Tenant Info -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-extrabold text-white text-base shadow-md" style="background-color: {{ $tenant->brand_color ?? '#0056A2' }}">
                                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-sm">{{ $tenant->name }}</div>
                                            <div class="text-[11px] text-sltds-endeavour font-mono font-medium">
                                                {{ $tenant->domain ?? ($tenant->slug . '.localhost') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $tenant->category }}
                                    </span>
                                </td>

                                <!-- Plan & Status -->
                                <td class="py-4 px-4 space-y-1">
                                    <div>
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-blue-50 text-sltds-endeavour border border-blue-200">
                                            {{ $tenant->subscription_plan }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($tenant->is_active)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-sltds-green border border-emerald-200">Active</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Directory Visibility -->
                                <td class="py-4 px-4">
                                    <form action="{{ route('superadmin.tenants.toggle-public', $tenant->id) }}" method="POST">
                                        @csrf
                                        @if($tenant->is_public)
                                            <button type="submit" title="Click to hide from public directory" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-sltds-green border border-emerald-200 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition-colors">
                                                ✓ Public Directory
                                            </button>
                                        @else
                                            <button type="submit" title="Click to publish to customer directory" class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 hover:bg-emerald-50 hover:text-sltds-green hover:border-emerald-200 transition-colors">
                                                🔒 Private (Hidden)
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                <!-- Counts -->
                                <td class="py-4 px-4 text-xs font-medium">
                                    <div class="font-bold text-slate-900">{{ $tenant->courts_count ?? 0 }} Courts</div>
                                    <div class="text-slate-500 text-[10px]">{{ $tenant->bookings_count ?? 0 }} Total Bookings</div>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right space-x-2">
                                    
                                    <!-- Impersonate Button -->
                                    <form action="{{ route('superadmin.tenants.impersonate', $tenant->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Log in as Tenant Owner for support" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-900 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-all shadow-xs">
                                            Log in as &rarr;
                                        </button>
                                    </form>

                                    <!-- Edit Link -->
                                    <a href="{{ route('superadmin.tenants.edit', $tenant->id) }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors">
                                        Edit
                                    </a>

                                    <!-- Suspend / Reactivate Button -->
                                    <form action="{{ route('superadmin.tenants.toggle-status', $tenant->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($tenant->is_active)
                                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors">
                                                Suspend
                                            </button>
                                        @else
                                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-sltds-green bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                                Reactivate
                                            </button>
                                        @endif
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
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
