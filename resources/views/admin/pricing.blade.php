<x-layouts.admin title="Pricing Rules & Rate Configuration | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-purple-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Dynamic Revenue Engine
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    Pricing & Rate Configuration
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">Configure peak/off-peak windows, seasonal date ranges, and member discount rules for {{ $tenant->name }}.</p>
            </div>
            <div>
                <button onclick="document.getElementById('addRuleModal').style.display = 'flex'" class="px-5 py-2.5 rounded-full font-black text-xs text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Pricing Rule
                </button>
            </div>
        </div>

        <!-- Rule Type Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-black text-[10px] uppercase tracking-widest border border-amber-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Peak Windows</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $pricingRules->where('rule_type', 'peak')->count() }} Active</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Configured peak rate surcharges (e.g. 17:00–22:00 weekdays & weekends).</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 font-black text-[10px] uppercase tracking-widest border border-indigo-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Seasonal Adjustments</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $pricingRules->where('rule_type', 'seasonal')->count() }} Active</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Date-range specific seasonal adjustments (e.g. Summer & Holiday peak rates).</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 font-black text-[10px] uppercase tracking-widest border border-emerald-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Discount Rules</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $pricingRules->where('rule_type', 'discount')->count() }} Active</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Multi-booking, last-minute, and member tier discount rules.</p>
            </div>
        </div>

        <!-- Pricing Rules Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h2 class="text-base font-black italic tracking-tighter uppercase text-white drop-shadow-sm">Facility Pricing Rules</h2>
                <span class="text-xs text-slate-400 font-medium">Applied in order of calculation engine priority</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium border-collapse min-w-200">
                    <thead class="bg-white/5 text-slate-400 uppercase tracking-widest font-black text-[10px] border-b border-white/10">
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
                    <tbody class="divide-y divide-white/10 text-slate-200">
                        @forelse ($pricingRules as $rule)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-bold text-white">
                                    {{ $rule->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase
                                        {{ $rule->rule_type === 'peak' ? 'bg-amber-500/20 text-amber-300 border border-amber-400/30' : '' }}
                                        {{ $rule->rule_type === 'seasonal' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-400/30' : '' }}
                                        {{ $rule->rule_type === 'discount' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/30' : '' }}
                                        {{ $rule->rule_type === 'off_peak' ? 'bg-white/10 text-slate-300 border border-white/20' : '' }}">
                                        {{ ucfirst($rule->rule_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black font-mono text-emerald-400">
                                    {{ $rule->adjustment_value > 0 ? '+' : '' }}{{ $rule->adjustment_value }}{{ $rule->adjustment_type === 'percentage' ? '%' : ' LKR' }}
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    @if ($rule->start_time && $rule->end_time)
                                        <code class="font-bold font-mono text-sky-400">{{ $rule->start_time }} – {{ $rule->end_time }}</code>
                                    @elseif ($rule->start_date && $rule->end_date)
                                        <span>{{ \Carbon\Carbon::parse($rule->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($rule->end_date)->format('M d, Y') }}</span>
                                    @elseif ($rule->discount_type === 'multi_booking')
                                        <span>Min {{ $rule->min_slots }} Slots</span>
                                    @else
                                        <span class="text-slate-500">All Times</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    {{ $rule->court ? $rule->court->name : ($rule->sportCategory ? $rule->sportCategory->name : 'All Facilities') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($rule->is_active)
                                        <span class="inline-flex items-center gap-1.5 text-emerald-300 bg-emerald-500/20 border border-emerald-400/30 px-3 py-1 rounded-full font-black text-[10px] uppercase shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-slate-400 bg-white/10 border border-white/20 px-3 py-1 rounded-full font-black text-[10px] uppercase shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <form action="{{ route('admin.pricing.toggle', $rule->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all cursor-pointer">
                                            {{ $rule->is_active ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pricing.delete', $rule->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this pricing rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded-full text-xs font-bold text-rose-300 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-400/30 transition-all cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400 font-medium">
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
    <div id="addRuleModal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4" style="display: none;">
        <div class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-white/20 text-left text-white">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Create Pricing Rule</h3>
                <button type="button" onclick="document.getElementById('addRuleModal').style.display = 'none'" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
            </div>

            <form action="{{ route('admin.pricing.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Rule Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Evening Peak Surcharge" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 focus:outline-none focus:border-white/50 focus:bg-white/20 font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Rule Type *</label>
                        <select name="rule_type" required class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-slate-900 text-white font-medium focus:outline-none focus:border-white/50">
                            <option value="peak">Peak Surcharge</option>
                            <option value="off_peak">Off-Peak Discount</option>
                            <option value="seasonal">Seasonal Adjustment</option>
                            <option value="discount">Special Discount</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Discount Type</label>
                        <select name="discount_type" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-slate-900 text-white font-medium focus:outline-none focus:border-white/50">
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
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Adjustment Type *</label>
                        <select name="adjustment_type" required class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-slate-900 text-white font-medium focus:outline-none focus:border-white/50">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (LKR)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Value (+ / -) *</label>
                        <input type="number" step="0.01" name="adjustment_value" required placeholder="e.g. 20 or -15" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Start Time (HH:MM)</label>
                        <input type="text" name="start_time" placeholder="17:00" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">End Time (HH:MM)</label>
                        <input type="text" name="end_time" placeholder="22:00" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                    <button type="button" onclick="document.getElementById('addRuleModal').style.display = 'none'" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all cursor-pointer">Save Pricing Rule</button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.admin>

