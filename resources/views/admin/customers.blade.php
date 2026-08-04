<x-layouts.admin title="Customers & Credits | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8" x-data="{ selectedCustomerId: null, selectedCustomerName: '', creditModalOpen: false, passModalOpen: false }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-cyan-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Member Accounts & Tender Ledger
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    Customers & Credits Management
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">Manage tenant members, issue goodwill wallet credits, assign multi-visit passes, and audit credit ledgers for {{ $tenant->name }}.</p>
            </div>
        </div>

        <!-- Customer Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 font-black text-[10px] uppercase tracking-widest border border-sky-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Total Members</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $customers->count() }} Registered</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Tenant-scoped customer player accounts.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-black text-[10px] uppercase tracking-widest border border-emerald-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Total Credits Issued</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">LKR {{ number_format($customers->sum('credit_balance'), 2) }}</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Combined derived balance across all customer ledgers.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 font-black text-[10px] uppercase tracking-widest border border-indigo-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Active Passes</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $customers->flatMap->passes->where('status', 'active')->count() }} Active</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Active multi-session passes with remaining units.</p>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h2 class="text-base font-black italic tracking-tighter uppercase text-white drop-shadow-sm">Customer Directory</h2>
                <span class="text-xs text-slate-400 font-medium">Click Issue Credits or Issue Pass to grant internal tender</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium border-collapse min-w-200">
                    <thead class="bg-white/5 text-slate-400 uppercase tracking-widest font-black text-[10px] border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4">Customer Details</th>
                            <th class="px-6 py-4">Derived Credit Balance</th>
                            <th class="px-6 py-4">Active Passes</th>
                            <th class="px-6 py-4">Account Status</th>
                            <th class="px-6 py-4 text-right">Staff Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-200">
                        @forelse ($customers as $c)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-2xl bg-white/10 text-white font-black flex items-center justify-center text-sm shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] border border-white/20">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-white block font-bold text-xs">{{ $c->name }}</strong>
                                            <span class="text-slate-400 text-[11px] font-normal">{{ $c->email }} &bull; {{ $c->phone ?? 'No phone' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-black font-mono text-sm text-emerald-400">
                                        LKR {{ number_format($c->credit_balance, 2) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $c->creditLedgers->count() }} ledger entries</span>
                                </td>

                                <td class="px-6 py-4">
                                    @php $activePasses = $c->passes->where('status', 'active'); @endphp
                                    @if($activePasses->isNotEmpty())
                                        @foreach($activePasses as $p)
                                            <div class="text-[11px] font-bold text-white">
                                                {{ $p->pass_name }} ({{ $p->remaining_units }}/{{ $p->total_units }} units left)
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-slate-500 text-[11px]">No active passes</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($c->is_banned)
                                        <span class="inline-flex items-center gap-1.5 text-rose-300 bg-rose-500/20 border border-rose-400/30 px-3 py-1 rounded-full font-black text-[10px] uppercase shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            Suspended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-emerald-300 bg-emerald-500/20 border border-emerald-400/30 px-3 py-1 rounded-full font-black text-[10px] uppercase shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            Active Player
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(in_array(auth()->user()->role ?? '', ['owner', 'manager']) || auth()->user()?->isSuperAdmin())
                                        <button @click="selectedCustomerId = {{ $c->id }}; selectedCustomerName = '{{ $c->name }}'; creditModalOpen = true" class="px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 hover:bg-emerald-500/30 font-bold text-[11px] transition-all cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            + Issue Credits
                                        </button>

                                        <button @click="selectedCustomerId = {{ $c->id }}; selectedCustomerName = '{{ $c->name }}'; passModalOpen = true" class="px-3.5 py-1.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 hover:bg-indigo-500/30 font-bold text-[11px] transition-all cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            + Assign Pass
                                        </button>
                                    @else
                                        <span class="text-slate-500 text-[11px] font-semibold">View Only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-medium">
                                    No customer accounts found for this tenant facility.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Issue Credits Modal -->
        <div x-show="creditModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-white/20 text-left text-white" @click.away="creditModalOpen = false">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Issue Wallet Credits</h3>
                        <p class="text-xs text-slate-400 font-mono" x-text="'Customer: ' + selectedCustomerName"></p>
                    </div>
                    <button @click="creditModalOpen = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <form :action="'/admin/customers/' + selectedCustomerId + '/issue-credits'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Credit Amount (LKR) *</label>
                        <input type="number" step="0.01" name="amount" required placeholder="e.g. 5000.00" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Reason / Support Note *</label>
                        <input type="text" name="reason" required placeholder="e.g. Goodwill topup for court lighting delay" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                        <button type="button" @click="creditModalOpen = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-black uppercase tracking-wider shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)] disabled:opacity-50 transition-all cursor-pointer">Issue Credits &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Issue Pass Modal -->
        <div x-show="passModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-white/20 text-left text-white" @click.away="passModalOpen = false">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Assign Multi-Session Pass</h3>
                        <p class="text-xs text-slate-400 font-mono" x-text="'Customer: ' + selectedCustomerName"></p>
                    </div>
                    <button @click="passModalOpen = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <form :action="'/admin/customers/' + selectedCustomerId + '/issue-pass'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Pass Name *</label>
                        <input type="text" name="pass_name" required value="Badminton 10-Session Pass" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Total Session Units *</label>
                            <input type="number" name="total_units" required value="10" min="1" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        </div>

                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Price Paid (LKR) *</label>
                            <input type="number" step="0.01" name="price_paid" required value="25000.00" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        </div>
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Valid Duration (Days) *</label>
                        <input type="number" name="valid_days" required value="60" min="1" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                        <button type="button" @click="passModalOpen = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-wider shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)] disabled:opacity-50 transition-all cursor-pointer">Assign Pass &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>

