<x-layouts.admin title="Staff & Roles | Facility Management">

    <div class="max-w-7xl mx-auto space-y-8" x-data="{ addStaffModalOpen: false }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="px-3.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 text-amber-400 border border-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    Access Control & Role Delegation
                </span>
                <h1 class="text-2xl sm:text-4xl font-black italic tracking-tighter uppercase text-white leading-tight mt-2 drop-shadow-md">
                    Staff Management
                </h1>
                <p class="text-xs text-slate-400 mt-1 font-medium">Manage venue staff accounts, assign operational roles, and enforce access permissions for {{ $tenant->name }}.</p>
            </div>

            <button @click="addStaffModalOpen = true" class="px-5 py-2.5 rounded-full font-black text-xs text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-95 flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                + Invite / Add Staff Member
            </button>
        </div>

        <!-- Staff Role Breakdown Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 font-black text-[10px] uppercase tracking-widest border border-indigo-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Total Staff</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $staffMembers->count() }} Accounts</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Active facility personnel with system access.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-purple-500/20 text-purple-300 font-black text-[10px] uppercase tracking-widest border border-purple-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Owners & Managers</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $staffMembers->whereIn('role', ['owner', 'manager'])->count() }} Admin Staff</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">Full administrative authority over pricing & staff.</p>
            </div>

            <div class="bg-slate-900/80 backdrop-blur-2xl p-6 rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] space-y-3">
                <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 font-black text-[10px] uppercase tracking-widest border border-amber-400/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">Front Desk & Trainers</span>
                <h3 class="text-3xl font-black text-white tracking-tight drop-shadow-sm">{{ $staffMembers->whereIn('role', ['trainer_staff', 'front_desk'])->count() }} Operational Staff</h3>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">View bookings, mark paid, and manage no-shows.</p>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="bg-slate-900/80 backdrop-blur-2xl rounded-3xl border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2),0_20px_50px_rgba(0,0,0,0.6)] overflow-hidden">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h2 class="text-base font-black italic tracking-tighter uppercase text-white drop-shadow-sm">Staff Account Directory</h2>
                <span class="text-xs text-slate-400 font-medium">Tenant-scoped operational role access</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-medium border-collapse min-w-200">
                    <thead class="bg-white/5 text-slate-400 uppercase tracking-widest font-black text-[10px] border-b border-white/10">
                        <tr>
                            <th class="px-6 py-4">Staff Member</th>
                            <th class="px-6 py-4">Assigned Role</th>
                            <th class="px-6 py-4">Access Scope</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-200">
                        @forelse ($staffMembers as $member)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-2xl bg-white/10 text-white font-black flex items-center justify-center text-sm shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] border border-white/20">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong class="text-white block font-bold text-xs">{{ $member->name }}</strong>
                                            <span class="text-slate-400 text-[11px] font-normal">{{ $member->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @php
                                        $roleStyles = [
                                            'owner' => 'bg-purple-500/20 text-purple-300 border-purple-400/30',
                                            'manager' => 'bg-indigo-500/20 text-indigo-300 border-indigo-400/30',
                                            'trainer_staff' => 'bg-emerald-500/20 text-emerald-300 border-emerald-400/30',
                                            'front_desk' => 'bg-amber-500/20 text-amber-300 border-amber-400/30',
                                        ];
                                        $style = $roleStyles[$member->role] ?? 'bg-white/10 text-slate-300 border-white/20';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $style }} shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                        {{ str_replace('_', ' ', $member->role) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-slate-300 text-[11px]">
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
                                            <button type="submit" :disabled="submitting" class="px-3.5 py-1.5 rounded-full bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-400/30 font-bold text-[11px] transition-all disabled:opacity-50 cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Remove Staff
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-500 text-[11px] font-bold">
                                            {{ $member->id === auth()->id() ? 'Active Account' : 'Protected Owner' }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 font-medium">
                                    No staff accounts created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Staff Modal -->
        <div x-show="addStaffModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="bg-slate-900/95 backdrop-blur-3xl rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-white/20 text-left text-white" @click.away="addStaffModalOpen = false">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h3 class="text-lg font-black italic tracking-tighter uppercase text-white">Invite / Create Staff Member</h3>
                    <button @click="addStaffModalOpen = false" class="text-slate-400 hover:text-white font-bold p-1 cursor-pointer">✕</button>
                </div>

                <form action="{{ route('admin.staff.store') }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Kasun Kalhara" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Email Address *</label>
                        <input type="email" name="email" required placeholder="kasun@colombocourts.lk" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Assigned Operational Role *</label>
                        <select name="role" required class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-slate-900 text-white font-medium focus:outline-none focus:border-white/50">
                            <option value="front_desk">Front Desk (Bookings & Check-in)</option>
                            <option value="trainer_staff">Trainer / Staff (Court & No-Show Management)</option>
                            <option value="manager">Manager (Courts, Pricing, & Staff)</option>
                            @if(in_array(auth()->user()->role ?? '', ['owner', 'super_admin']) || auth()->user()?->isSuperAdmin())
                                <option value="owner">Owner (Full Facility Authority)</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block font-black text-slate-300 uppercase tracking-widest text-[10px] mb-1.5">Account Password *</label>
                        <input type="password" name="password" required minlength="6" placeholder="••••••••" class="w-full px-4 py-3 rounded-2xl border border-white/20 bg-white/10 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/50 focus:bg-white/20 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-white/10">
                        <button type="button" @click="addStaffModalOpen = false" class="px-5 py-2.5 rounded-full border border-white/20 font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all">Cancel</button>
                        <button type="submit" :disabled="submitting" class="px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] disabled:opacity-50 transition-all cursor-pointer">Create Account &rarr;</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.admin>

