<x-layouts.admin title="Staff & Roles | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8" x-data="{ addStaffModalOpen: false }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-black text-sltds-endeavour uppercase tracking-wider">Access Control & Role Delegation</span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-0.5">Staff Management</h1>
                <p class="text-xs text-slate-500 mt-1">Manage venue staff accounts, assign operational roles, and enforce access permissions for {{ $tenant->name }}.</p>
            </div>

            <button @click="addStaffModalOpen = true" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-sltds-endeavour hover:bg-sltds-sky text-white text-xs font-extrabold shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01] cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                + Invite / Add Staff Member
            </button>
        </div>

        @if (session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-xs font-bold">
                ✓ {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3 rounded-2xl text-xs font-bold">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- Staff Role Breakdown Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-800 font-black text-[10px] uppercase tracking-wider border border-indigo-200">Total Staff</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $staffMembers->count() }} Accounts</h3>
                <p class="text-xs text-slate-500">Active facility personnel with system access.</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-sltds-endeavour font-black text-[10px] uppercase tracking-wider border border-blue-200">Owners & Managers</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $staffMembers->whereIn('role', ['owner', 'manager'])->count() }} Admin Staff</h3>
                <p class="text-xs text-slate-500">Full administrative authority over pricing & staff.</p>
            </div>

            <div class="bg-white p-6 rounded-3xl border-2 border-slate-200/80 shadow-xl space-y-2">
                <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 font-black text-[10px] uppercase tracking-wider border border-amber-200">Front Desk & Trainers</span>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ $staffMembers->whereIn('role', ['trainer_staff', 'front_desk'])->count() }} Operational Staff</h3>
                <p class="text-xs text-slate-500">View bookings, mark paid, and manage no-shows.</p>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/80 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 tracking-tight">Staff Account Directory</h2>
                <span class="text-xs text-slate-500 font-semibold">Tenant-scoped operational role access</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-black text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Staff Member</th>
                            <th class="px-6 py-4">Assigned Role</th>
                            <th class="px-6 py-4">Access Scope</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-800">
                        @forelse ($staffMembers as $member)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-black flex items-center justify-center text-sm shadow-xs">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 block font-bold text-xs">{{ $member->name }}</strong>
                                            <span class="text-slate-500 text-[11px] font-normal">{{ $member->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $roleStyles = [
                                            'owner' => 'bg-purple-100 text-purple-900 border-purple-300',
                                            'manager' => 'bg-indigo-100 text-indigo-900 border-indigo-300',
                                            'trainer_staff' => 'bg-emerald-100 text-emerald-900 border-emerald-300',
                                            'front_desk' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        ];
                                        $style = $roleStyles[$member->role] ?? 'bg-slate-100 text-slate-800 border-slate-300';
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $style }}">
                                        {{ str_replace('_', ' ', $member->role) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-600 text-[11px]">
                                    @if(in_array($member->role, ['owner', 'manager']))
                                        Full Admin (Bookings, Courts, Pricing, Staff Management)
                                    @else
                                        Operations (View Bookings, Mark Paid, Mark No-Show)
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    @if($member->id !== auth()->id() && !($member->role === 'owner' && auth()->user()->role === 'manager' && !auth()->user()->isSuperAdmin()))
                                        <form action="{{ route('admin.staff.delete', ['id' => $member->id]) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" onsubmit="return confirm('Remove staff account for {{ $member->name }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" :disabled="submitting" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 font-extrabold text-[11px] transition-colors disabled:opacity-50 cursor-pointer">
                                                Remove Staff
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px] font-bold">
                                            {{ $member->id === auth()->id() ? 'Active Account' : 'Protected Owner' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500 font-medium">
                                    No staff accounts created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Staff Modal -->
        <div x-show="addStaffModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 space-y-6 shadow-2xl border-2 border-slate-200 text-left" @click.away="addStaffModalOpen = false">
                <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                    <h3 class="text-base font-black text-slate-900">Invite / Create Staff Member</h3>
                    <button @click="addStaffModalOpen = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
                </div>

                <form action="{{ route('admin.staff.store') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Kasun Kalhara" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                        <input type="email" name="email" required placeholder="kasun@colombocourts.lk" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Assigned Operational Role *</label>
                        <select name="role" required class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                            <option value="front_desk">Front Desk (Bookings & Check-in)</option>
                            <option value="trainer_staff">Trainer / Staff (Court & No-Show Management)</option>
                            <option value="manager">Manager (Courts, Pricing, & Staff)</option>
                            @if(in_array(auth()->user()->role ?? '', ['owner', 'super_admin']) || auth()->user()?->isSuperAdmin())
                                <option value="owner">Owner (Full Facility Authority)</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Account Password *</label>
                        <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-slate-50 focus:bg-white font-medium focus:outline-none focus:border-sltds-endeavour">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="addStaffModalOpen = false" class="px-5 py-3 rounded-xl border border-slate-300 font-extrabold text-slate-600 hover:bg-slate-100">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-3 rounded-xl bg-sltds-endeavour hover:bg-sltds-sky text-white font-black shadow-md disabled:opacity-50">Create Account &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>
