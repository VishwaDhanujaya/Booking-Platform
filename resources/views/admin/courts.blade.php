<x-layouts.admin title="Courts Management & Facilities | Facility Management">

    <div x-data="{ 
        showAddModal: false,
        showEditModal: false,
        editCourtId: null,
        editCourtName: '',
        editOffPeakRate: 0,
        editPeakRate: 0,
        editIsActive: 1,
        openEditModal(id, name, offPeak, peak, isActive) {
            this.editCourtId = id;
            this.editCourtName = name;
            this.editOffPeakRate = offPeak;
            this.editPeakRate = peak;
            this.editIsActive = isActive ? 1 : 0;
            this.showEditModal = true;
        }
    }" class="max-w-7xl mx-auto space-y-6">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-emerald-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Facility Asset Inventory
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    Courts & Resource Management
                </h1>
            </div>
            
            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
            <button type="button" @click="showAddModal = true" class="px-5 py-2.5 rounded-full font-black text-xs text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                Add New Court Resource
            </button>
            @endif
        </div>

        <!-- Courts Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/10 bg-white/5">
                        <th class="p-4">Court / Facility</th>
                        <th class="p-4">Sport Category</th>
                        <th class="p-4">Type & Surface</th>
                        <th class="p-4">Off-Peak Rate</th>
                        <th class="p-4">Peak Rate</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-xs font-medium">
                    @foreach($courts as $c)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-4 font-bold text-white">
                            <div class="flex items-center gap-3">
                                <img src="{{ $c->image_url }}" class="w-10 h-10 rounded-2xl object-cover border border-white/20 shadow-md" alt="" />
                                <div>
                                    <span class="block text-sm font-bold text-white leading-tight">{{ $c->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">CRT-00{{ $c->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-slate-300">
                            {{ $c->sportCategory ? $c->sportCategory->name : 'Sport' }}
                        </td>
                        <td class="p-4 text-slate-300 capitalize">
                            <span class="font-bold text-white">{{ $c->type }}</span>
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $c->surface_type }}</span>
                        </td>
                        <td class="p-4 font-bold text-white">
                            LKR {{ number_format($c->hourly_rate) }}<span class="text-[10px] text-slate-400 font-normal">/hr</span>
                        </td>
                        <td class="p-4 font-black text-amber-400">
                            LKR {{ number_format($c->peak_hourly_rate) }}<span class="text-[10px] text-amber-300/80 font-normal">/hr</span>
                        </td>
                        <td class="p-4">
                            @if($c->is_active)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Active</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" @click="openEditModal({{ $c->id }}, '{{ $c->name }}', {{ $c->hourly_rate }}, {{ $c->peak_hourly_rate }}, {{ $c->is_active ? 1 : 0 }})" class="px-3.5 py-1.5 rounded-full text-xs font-extrabold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] cursor-pointer">
                                    Edit
                                </button>
                                <form action="{{ route('admin.courts.delete', ['id' => $c->id]) }}" method="POST" onsubmit="return confirm('Delete court resource {{ $c->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-extrabold text-rose-300 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-400/30 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-slate-500 text-[11px]">View Only</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MODAL A: ADD NEW COURT RESOURCE -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div @click.away="showAddModal = false" class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl p-6 sm:p-8 max-w-lg w-full space-y-6 border border-white/20 shadow-2xl relative text-left text-white">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Add New Court Resource</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Court Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Center Court Tennis 4" class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Sport Category *</label>
                            <select name="sport_category_id" class="w-full px-4 py-2.5 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Court Environment *</label>
                            <select name="type" class="w-full px-4 py-2.5 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50">
                                <option value="indoor">Indoor (AC)</option>
                                <option value="outdoor">Outdoor</option>
                                <option value="covered">Covered</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Surface Type Specs</label>
                        <input type="text" name="surface_type" value="Plexicushion Tournament Hard" class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Court Photo Upload (Optional)</label>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Off-Peak Rate (LKR)</label>
                            <input type="number" name="hourly_rate" value="3500" min="0" max="1000000" class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        </div>
                        <div>
                            <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Peak Rate (LKR)</label>
                            <input type="number" name="peak_hourly_rate" value="4500" min="0" max="1000000" class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] disabled:opacity-50 transition-all cursor-pointer">
                            Save Court Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL B: EDIT COURT RESOURCE -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div @click.away="showEditModal = false" class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl p-6 sm:p-8 max-w-md w-full space-y-6 border border-white/20 shadow-2xl relative text-left text-white">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block">Edit Court Specs</span>
                        <h3 class="text-lg font-black italic tracking-tighter uppercase text-white mt-0.5" x-text="editCourtName"></h3>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <form :action="'/admin/courts/' + editCourtId" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Court Name *</label>
                        <input type="text" name="name" x-model="editCourtName" required class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Off-Peak Hourly Rate (LKR) *</label>
                        <input type="number" name="hourly_rate" x-model="editOffPeakRate" min="0" max="1000000" required class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Peak Hourly Rate (LKR) *</label>
                        <input type="number" name="peak_hourly_rate" x-model="editPeakRate" min="0" max="1000000" required class="w-full px-4 py-2.5 rounded-2xl bg-white/10 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Court Photo Upload</label>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-white file:text-slate-950 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Status *</label>
                        <select name="is_active" x-model="editIsActive" class="w-full px-4 py-2.5 rounded-2xl bg-slate-900 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/50">
                            <option value="1">Active / Operational</option>
                            <option value="0">Inactive / Maintenance</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">
                            Cancel
                        </button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] disabled:opacity-50 transition-all cursor-pointer">
                            Update Court
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>

