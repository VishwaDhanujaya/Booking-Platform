<x-layouts.admin title="Customers & Credits | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8" x-data="{ selectedCustomerId: null, selectedCustomerName: '', creditModalOpen: false, passModalOpen: false }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Member Accounts & Tender Ledger</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Customers & Credits Management</h1>
                <p class="text-xs text-slate-500 mt-1">Manage tenant members, issue goodwill wallet credits, assign multi-visit passes, and audit credit ledgers for {{ $tenant->name }}.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-xs font-bold">
                ✓ {{ session('status') }}
            </div>
        @endif

        <!-- Customer Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-sltds-endeavour font-black text-[10px] uppercase tracking-wider border border-blue-200">Total Members</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $customers->count() }} Registered</h3>
                <p class="text-xs text-slate-500">Tenant-scoped customer player accounts.</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-black text-[10px] uppercase tracking-wider border border-emerald-200">Total Credits Issued</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">LKR {{ number_format($customers->sum('credit_balance'), 2) }}</h3>
                <p class="text-xs text-slate-500">Combined derived balance across all customer ledgers.</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-800 font-black text-[10px] uppercase tracking-wider border border-indigo-200">Active Passes</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $customers->flatMap->passes->where('status', 'active')->count() }} Active</h3>
                <p class="text-xs text-slate-500">Active multi-session passes with remaining units.</p>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 tracking-tight">Customer Directory</h2>
                <span class="text-xs text-slate-500 font-semibold">Click Issue Credits or Issue Pass to grant internal tender</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-black text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Customer Details</th>
                            <th class="px-6 py-4">Derived Credit Balance</th>
                            <th class="px-6 py-4">Active Passes</th>
                            <th class="px-6 py-4">Account Status</th>
                            <th class="px-6 py-4 text-right">Staff Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse ($customers as $c)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-sltds-endeavour text-white font-black flex items-center justify-center text-sm shadow-xs">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 block font-bold text-xs">{{ $c->name }}</strong>
                                            <span class="text-slate-500 text-[11px] font-normal">{{ $c->email }} &bull; {{ $c->phone ?? 'No phone' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-black font-mono text-sm text-emerald-600">
                                        LKR {{ number_format($c->credit_balance, 2) }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 block font-normal">{{ $c->creditLedgers->count() }} ledger entries</span>
                                </td>

                                <td class="px-6 py-4">
                                    @php $activePasses = $c->passes->where('status', 'active'); @endphp
                                    @if($activePasses->isNotEmpty())
                                        @foreach($activePasses as $p)
                                            <div class="text-[11px] font-bold text-slate-900">
                                                {{ $p->pass_name }} ({{ $p->remaining_units }}/{{ $p->total_units }} units left)
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-slate-400 text-[11px]">No active passes</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($c->is_banned)
                                        <span class="inline-flex items-center gap-1.5 text-rose-800 bg-rose-100 border border-rose-300 px-2.5 py-1 rounded-full font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Suspended
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-emerald-800 bg-emerald-100 border border-emerald-300 px-2.5 py-1 rounded-full font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Active Player
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">
                                    @if(in_array(auth()->user()->role ?? '', ['owner', 'manager']) || auth()->user()?->isSuperAdmin())
                                        <button @click="selectedCustomerId = {{ $c->id }}; selectedCustomerName = '{{ $c->name }}'; creditModalOpen = true" class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 font-extrabold text-[11px] cursor-pointer">
                                            + Issue Credits
                                        </button>

                                        <button @click="selectedCustomerId = {{ $c->id }}; selectedCustomerName = '{{ $c->name }}'; passModalOpen = true" class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-800 border border-indigo-200 hover:bg-indigo-100 font-extrabold text-[11px] cursor-pointer">
                                            + Assign Pass
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-[11px] font-semibold">View Only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">
                                    No customer accounts found for this tenant facility.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Issue Credits Modal -->
        <div x-show="creditModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-6 shadow-2xl border-2 border-slate-200 text-left" @click.away="creditModalOpen = false">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Issue Wallet Credits</h3>
                        <p class="text-xs text-slate-500" x-text="'Customer: ' + selectedCustomerName"></p>
                    </div>
                    <button @click="creditModalOpen = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
                </div>

                <form :action="'/admin/customers/' + selectedCustomerId + '/issue-credits'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Credit Amount (LKR) *</label>
                        <input type="number" step="0.01" name="amount" required placeholder="e.g. 5000.00" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Reason / Support Note *</label>
                        <input type="text" name="reason" required placeholder="e.g. Goodwill topup for court lighting delay" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="creditModalOpen = false" class="px-5 py-3 rounded-xl border border-slate-300 font-extrabold text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black shadow-md disabled:opacity-50">Issue Credits &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Issue Pass Modal -->
        <div x-show="passModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-6 shadow-2xl border-2 border-slate-200 text-left" @click.away="passModalOpen = false">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Assign Multi-Session Pass</h3>
                        <p class="text-xs text-slate-500" x-text="'Customer: ' + selectedCustomerName"></p>
                    </div>
                    <button @click="passModalOpen = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
                </div>

                <form :action="'/admin/customers/' + selectedCustomerId + '/issue-pass'" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Pass Name *</label>
                        <input type="text" name="pass_name" required value="Badminton 10-Session Pass" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Total Session Units *</label>
                            <input type="number" name="total_units" required value="10" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Price Paid (LKR) *</label>
                            <input type="number" step="0.01" name="price_paid" required value="25000.00" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Valid Duration (Days) *</label>
                        <input type="number" name="valid_days" required value="60" min="1" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="passModalOpen = false" class="px-5 py-3 rounded-xl border border-slate-300 font-extrabold text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-black shadow-md disabled:opacity-50">Assign Pass &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>
