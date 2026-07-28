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
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Facility Asset Inventory</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Courts & Resource Management</h1>
            </div>
            
            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
            <button type="button" @click="showAddModal = true" class="px-4 py-2.5 rounded-xl font-extrabold text-xs bg-sltds-endeavour hover:bg-sltds-sky text-white shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01] flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add New Court Resource
            </button>
            @endif
        </div>

        <!-- Flash Status Message -->
        @if(session('status'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        <!-- Courts Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-200">
                <thead>
                    <tr class="text-[11px] font-black text-slate-500 uppercase tracking-wider border-b border-slate-200 bg-slate-50">
                        <th class="p-4">Court / Facility</th>
                        <th class="p-4">Sport Category</th>
                        <th class="p-4">Type & Surface</th>
                        <th class="p-4">Off-Peak Rate</th>
                        <th class="p-4">Peak Rate</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium">
                    @foreach($courts as $c)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-900">
                            <div class="flex items-center gap-3">
                                <img src="{{ $c->image_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-xs" alt="" />
                                <div>
                                    <span class="block text-sm font-bold text-slate-900 leading-tight">{{ $c->name }}</span>
                                    <span class="text-[10px] text-slate-500 font-normal">Resource ID: CRT-00{{ $c->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 font-semibold text-slate-800">
                            {{ $c->sportCategory ? $c->sportCategory->name : 'Sport' }}
                        </td>
                        <td class="p-4 text-slate-700 capitalize">
                            <span class="font-bold text-slate-900">{{ $c->type }}</span>
                            <span class="block text-[10px] text-slate-500 font-normal">{{ $c->surface_type }}</span>
                        </td>
                        <td class="p-4 font-bold text-slate-900">
                            LKR {{ number_format($c->hourly_rate) }}<span class="text-[10px] text-slate-500 font-normal">/hr</span>
                        </td>
                        <td class="p-4 font-black text-amber-600">
                            LKR {{ number_format($c->peak_hourly_rate) }}<span class="text-[10px] text-amber-700/80 font-normal">/hr</span>
                        </td>
                        <td class="p-4">
                            @if($c->is_active)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">Active</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-300">Inactive</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" @click="openEditModal({{ $c->id }}, '{{ $c->name }}', {{ $c->hourly_rate }}, {{ $c->peak_hourly_rate }}, {{ $c->is_active ? 1 : 0 }})" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-sltds-endeavour bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-colors cursor-pointer">
                                    Edit
                                </button>
                                <form action="{{ route('admin.courts.delete', ['id' => $c->id]) }}" method="POST" onsubmit="return confirm('Delete court resource {{ $c->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-slate-400 text-[11px]">View Only</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- MODAL A: ADD NEW COURT RESOURCE -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showAddModal = false" class="bg-white rounded-3xl p-8 max-w-lg w-full space-y-6 border-2 border-slate-200 shadow-2xl relative text-left">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <h3 class="text-lg font-black text-slate-900">Add New Court Resource</h3>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-sm">✕</button>
                </div>

                <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Court Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Center Court Tennis 4" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Sport Category *</label>
                            <select name="sport_category_id" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Court Environment *</label>
                            <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                                <option value="indoor">Indoor (AC)</option>
                                <option value="outdoor">Outdoor</option>
                                <option value="covered">Covered</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Surface Type Specs</label>
                        <input type="text" name="surface_type" value="Plexicushion Tournament Hard" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Court Photo Upload (Optional)</label>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sltds-endeavour file:text-white hover:file:bg-sltds-sky cursor-pointer">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Off-Peak Rate (LKR)</label>
                            <input type="number" name="hourly_rate" value="3500" min="0" max="1000000" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Peak Rate (LKR)</label>
                            <input type="number" name="peak_hourly_rate" value="4500" min="0" max="1000000" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </button>
                        <button type="submit" :disabled="submitting" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 disabled:opacity-50">
                            Save Court Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL B: EDIT COURT RESOURCE -->
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showEditModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full space-y-6 border-2 border-slate-200 shadow-2xl relative text-left">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <div>
                        <span class="text-[10px] font-black text-sltds-endeavour uppercase tracking-wider block">Edit Court Specs</span>
                        <h3 class="text-lg font-black text-slate-900 mt-0.5" x-text="editCourtName"></h3>
                    </div>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-sm">✕</button>
                </div>

                <form :action="'/admin/courts/' + editCourtId" method="POST" enctype="multipart/form-data" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Court Name *</label>
                        <input type="text" name="name" x-model="editCourtName" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Off-Peak Hourly Rate (LKR) *</label>
                        <input type="number" name="hourly_rate" x-model="editOffPeakRate" min="0" max="1000000" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Peak Hourly Rate (LKR) *</label>
                        <input type="number" name="peak_hourly_rate" x-model="editPeakRate" min="0" max="1000000" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Court Photo Upload</label>
                        <input type="file" name="image_file" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sltds-endeavour file:text-white hover:file:bg-sltds-sky cursor-pointer">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Status *</label>
                        <select name="is_active" x-model="editIsActive" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 font-semibold focus:outline-none focus:border-sltds-endeavour">
                            <option value="1">Active / Operational</option>
                            <option value="0">Inactive / Maintenance</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800">
                            Cancel
                        </button>
                        <button type="submit" :disabled="submitting" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 disabled:opacity-50">
                            Update Court
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>
