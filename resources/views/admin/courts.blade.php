<x-layouts.admin title="Courts Management & Pricing | Colombo Courts Admin">

    <div x-data="{ 
        showAddModal: false,
        showEditModal: false,
        editCourtName: '',
        editOffPeakRate: 0,
        editPeakRate: 0,
        openEditModal(name, offPeak, peak) {
            this.editCourtName = name;
            this.editOffPeakRate = offPeak;
            this.editPeakRate = peak;
            this.showEditModal = true;
        }
    }" class="max-w-7xl mx-auto space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-brand-400 uppercase tracking-widest">Facility Asset Inventory</span>
                <h1 class="text-2xl sm:text-3xl font-black text-white mt-0.5">Courts & Pricing Management</h1>
            </div>
            
            <button type="button" @click="showAddModal = true" class="px-4 py-2.5 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 text-white shadow-md transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add New Court Resource
            </button>
        </div>

        <!-- Flash Status Message -->
        @if(session('status'))
        <div class="bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 px-5 py-3 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        <!-- Courts Table -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-xl overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800 bg-slate-950/80">
                        <th class="p-4">Court / Facility</th>
                        <th class="p-4">Sport Category</th>
                        <th class="p-4">Type & Surface</th>
                        <th class="p-4">Off-Peak Rate</th>
                        <th class="p-4">Peak Rate (16:00+)</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-xs">
                    @foreach($courts as $c)
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="p-4 font-bold text-white">
                            <div class="flex items-center gap-3">
                                <img src="{{ $c->image_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-700" alt="" />
                                <div>
                                    <span class="block text-sm font-bold text-white leading-tight">{{ $c->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal">Resource ID: CRT-00{{ $c->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-slate-200">
                            {{ $c->sportCategory ? $c->sportCategory->name : 'Sport' }}
                        </td>
                        <td class="p-4 text-slate-300 capitalize">
                            <span class="font-bold text-white">{{ $c->type }}</span>
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $c->surface_type }}</span>
                        </td>
                        <td class="p-4 font-bold text-white">
                            LKR {{ number_format($c->hourly_rate) }}<span class="text-[10px] text-slate-400 font-normal">/hr</span>
                        </td>
                        <td class="p-4 font-bold text-amber-400">
                            LKR {{ number_format($c->peak_hourly_rate) }}<span class="text-[10px] text-amber-300/80 font-normal">/hr</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Active</span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" @click="openEditModal('{{ $c->name }}', {{ $c->hourly_rate }}, {{ $c->peak_hourly_rate }})" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-brand-300 bg-brand-500/10 hover:bg-brand-500/20 border border-brand-500/30 transition-colors">
                                    Edit Rates
                                </button>
                                <button type="button" onclick="alert('Demo mode: Court {{ $c->name }} status updated.')" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 transition-colors">
                                    Maintenance
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MODAL A: ADD NEW COURT RESOURCE -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showAddModal = false" class="bg-slate-900 rounded-3xl p-8 max-w-lg w-full space-y-6 border border-slate-800 shadow-2xl relative text-left">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <h3 class="text-lg font-extrabold text-white">Add New Court Resource</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-white font-bold text-sm">✕</button>
                </div>

                <form action="{{ route('admin.courts.store') }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Court Name</label>
                        <input type="text" name="name" value="Center Court - Tennis 4" required class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Sport Category</label>
                            <select name="sport_category_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Court Environment</label>
                            <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                                <option value="indoor">Indoor (AC)</option>
                                <option value="outdoor">Outdoor</option>
                                <option value="covered">Covered</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Surface Type Specs</label>
                        <input type="text" name="surface_type" value="Plexicushion Tournament Hard" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Off-Peak Rate (LKR)</label>
                            <input type="number" name="hourly_rate" value="3500" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Peak Rate (LKR)</label>
                            <input type="number" name="peak_hourly_rate" value="4500" class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-semibold focus:ring-2 focus:ring-brand-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-500 shadow-md">
                            Save Court Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL B: EDIT COURT RATES -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showEditModal = false" class="bg-slate-900 rounded-3xl p-8 max-w-md w-full space-y-6 border border-slate-800 shadow-2xl relative text-left">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div>
                        <span class="text-[10px] font-bold text-brand-400 uppercase tracking-wider block">Pricing & Rate Rules</span>
                        <h3 class="text-lg font-extrabold text-white mt-0.5" x-text="editCourtName"></h3>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-white font-bold text-sm">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Off-Peak Rate (06:00 - 16:00)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-500 font-bold">LKR</span>
                            <input type="number" x-model="editOffPeakRate" class="w-full pl-12 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Peak Rate (16:00 - 22:00)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-2.5 text-slate-500 font-bold">LKR</span>
                            <input type="number" x-model="editPeakRate" class="w-full pl-12 pr-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white font-bold">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-400 hover:text-white">
                            Cancel
                        </button>
                        <button type="button" @click="alert('Rates updated successfully for ' + editCourtName); showEditModal = false;" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-500 shadow-md">
                            Update Rates
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.admin>
