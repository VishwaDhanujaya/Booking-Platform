<x-layouts.admin title="Pricing Rules & Rate Configuration | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Dynamic Revenue Engine</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Pricing & Rate Configuration</h1>
                <p class="text-xs text-slate-500 mt-1">Configure peak/off-peak windows, seasonal date ranges, and member discount rules for {{ $tenant->name }}.</p>
            </div>
            <div>
                <button onclick="document.getElementById('addRuleModal').style.display = 'flex'" class="px-4 py-2.5 rounded-xl bg-sltds-endeavour hover:bg-sltds-sky text-white font-extrabold text-xs shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01] flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Pricing Rule
                </button>
            </div>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-xs font-bold">
                ✓ {{ session('status') }}
            </div>
        @endif

        <!-- Rule Type Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-black text-[10px] uppercase tracking-wider border border-amber-200">Peak Windows</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $pricingRules->where('rule_type', 'peak')->count() }} Active</h3>
                <p class="text-xs text-slate-500">Configured peak rate surcharges (e.g. 17:00–22:00 weekdays & weekends).</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-black text-[10px] uppercase tracking-wider border border-indigo-200">Seasonal Adjustments</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $pricingRules->where('rule_type', 'seasonal')->count() }} Active</h3>
                <p class="text-xs text-slate-500">Date-range specific seasonal adjustments (e.g. Summer & Holiday peak rates).</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-black text-[10px] uppercase tracking-wider border border-emerald-200">Discount Rules</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $pricingRules->where('rule_type', 'discount')->count() }} Active</h3>
                <p class="text-xs text-slate-500">Multi-booking, last-minute, and member tier discount rules.</p>
            </div>
        </div>

        <!-- Pricing Rules Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 tracking-tight">Facility Pricing Rules</h2>
                <span class="text-xs text-slate-500 font-semibold">Applied in order of calculation engine priority</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-black text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Rule Name</th>
                            <th class="px-6 py-4">Type</th>
                            <th class="px-6 py-4">Adjustment</th>
                            <th class="px-6 py-4">Time Window / Range</th>
                            <th class="px-6 py-4">Scope</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse ($pricingRules as $rule)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-900">
                                    {{ $rule->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase
                                        {{ $rule->rule_type === 'peak' ? 'bg-amber-100 text-amber-900 border border-amber-300' : '' }}
                                        {{ $rule->rule_type === 'seasonal' ? 'bg-indigo-100 text-indigo-900 border border-indigo-300' : '' }}
                                        {{ $rule->rule_type === 'discount' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : '' }}
                                        {{ $rule->rule_type === 'off_peak' ? 'bg-slate-100 text-slate-800 border border-slate-300' : '' }}">
                                        {{ ucfirst($rule->rule_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black font-mono text-slate-900">
                                    {{ $rule->adjustment_value > 0 ? '+' : '' }}{{ $rule->adjustment_value }}{{ $rule->adjustment_type === 'percentage' ? '%' : ' LKR' }}
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    @if ($rule->start_time && $rule->end_time)
                                        <code class="font-bold text-slate-800">{{ $rule->start_time }} – {{ $rule->end_time }}</code>
                                    @elseif ($rule->start_date && $rule->end_date)
                                        <span>{{ \Carbon\Carbon::parse($rule->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($rule->end_date)->format('M d, Y') }}</span>
                                    @elseif ($rule->discount_type === 'multi_booking')
                                        <span>Min {{ $rule->min_slots }} Slots</span>
                                    @else
                                        <span class="text-slate-400">All Times</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-700">
                                    {{ $rule->court ? $rule->court->name : ($rule->sportCategory ? $rule->sportCategory->name : 'All Facilities') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($rule->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-emerald-800 bg-emerald-100 border border-emerald-300 px-2.5 py-1 rounded-full font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-600 bg-slate-100 border border-slate-300 px-2.5 py-1 rounded-full font-extrabold text-[10px] uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <form action="{{ route('admin.pricing.toggle', $rule->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sltds-endeavour hover:underline font-extrabold cursor-pointer">
                                            {{ $rule->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pricing.delete', $rule->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this pricing rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline font-extrabold cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">
                                    No custom pricing rules configured yet. The engine currently applies default court base and peak rates.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Create Pricing Rule Modal -->
    <div id="addRuleModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 space-y-6 shadow-2xl border-2 border-slate-200 text-left">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <h3 class="text-lg font-black text-slate-900">Create Pricing Rule</h3>
                <button type="button" onclick="document.getElementById('addRuleModal').style.display = 'none'" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
            </div>

            <form action="{{ route('admin.pricing.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Rule Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Evening Peak Surcharge" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-sltds-endeavour bg-slate-50 focus:bg-white font-medium">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Rule Type *</label>
                        <select name="rule_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                            <option value="peak">Peak Surcharge</option>
                            <option value="off_peak">Off-Peak Discount</option>
                            <option value="seasonal">Seasonal Adjustment</option>
                            <option value="discount">Special Discount</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Discount Type</label>
                        <select name="discount_type" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                            <option value="none">None</option>
                            <option value="multi_booking">Multi-Slot Booking</option>
                            <option value="last_minute">Last-Minute Special</option>
                            <option value="club">Club Member</option>
                            <option value="student">Student</option>
                            <option value="senior">Senior</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Adjustment Type *</label>
                        <select name="adjustment_type" required class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (LKR)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Value (+ / -) *</label>
                        <input type="number" step="0.01" name="adjustment_value" required placeholder="e.g. 20 or -15" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Start Time (HH:MM)</label>
                        <input type="text" name="start_time" placeholder="17:00" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">End Time (HH:MM)</label>
                        <input type="text" name="end_time" placeholder="22:00" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                    <button type="button" onclick="document.getElementById('addRuleModal').style.display = 'none'" class="px-5 py-3 rounded-xl border border-slate-300 font-extrabold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-sltds-endeavour hover:bg-sltds-sky text-white font-black shadow-md shadow-sltds-endeavour/20">Save Pricing Rule</button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>
