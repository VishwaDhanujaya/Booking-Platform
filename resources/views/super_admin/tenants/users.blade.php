<x-layouts.super_admin title="Manage Tenant Users | SLTDS Super Admin">

    <div x-data="{ showAddUserModal: false }" class="space-y-8">

        <!-- CLEAN OPERATIONAL PAGE HEADER (VADEL Typography) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/15">
            <div class="space-y-1.5">
                <h1 class="text-3xl sm:text-4xl font-black italic tracking-tighter text-white uppercase drop-shadow-md">
                    USER ACCOUNTS: {{ $tenant->name }}
                </h1>
                <p class="text-xs text-slate-300 font-normal leading-relaxed">
                    Super-Admin direct control over user accounts and staff role permissions for this facility workspace.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants') }}" class="px-5 py-2.5 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    &larr; Back to Directory
                </a>
                <button type="button" @click="showAddUserModal = true" class="inline-flex items-center px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] hover:scale-105 active:scale-[0.98] cursor-pointer">
                    + Add User
                </button>
            </div>
        </div>

        <!-- HIGH-DENSITY USER TABLE (Specular Liquid Glass) -->
        <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/15 rounded-4xl overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.8)]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-950/90 text-slate-300 uppercase tracking-wider font-black text-[10px] border-b border-white/15">
                        <tr>
                            <th class="py-4 px-6">User Name</th>
                            <th class="py-4 px-4">Email Address</th>
                            <th class="py-4 px-4">Role</th>
                            <th class="py-4 px-4">Account Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300 font-medium">
                        @forelse($users as $u)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 px-6 font-black text-white text-sm">
                                    {{ $u->name }}
                                </td>
                                <td class="py-4 px-4 font-mono font-medium text-slate-300 text-[11px]">
                                    {{ $u->email }}
                                </td>
                                <td class="py-4 px-4">
                                    @if(in_array($u->role, ['owner', 'manager']))
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/20 text-white border border-white/30">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @elseif(in_array($u->role, ['trainer_staff', 'front_desk']))
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/10 text-slate-200 border border-white/20">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-white/5 text-slate-400 border border-white/10">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($u->is_banned)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-500/20 text-rose-300 border border-rose-400/30">Suspended</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Active</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if(!$u->isSuperAdmin())
                                        <form action="{{ route('superadmin.tenants.users.delete', [$tenant->id, $u->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete user {{ $u->email }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3.5 py-1.5 rounded-full text-xs font-black text-rose-300 bg-rose-500/20 border border-rose-400/30 hover:bg-rose-500/30 transition-colors cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                                                Delete User
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-500 italic">Platform Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    No users found for {{ $tenant->name }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ADD USER MODAL (Specular Liquid Glass Modal) -->
        <div x-show="showAddUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xl">
            <div @click.away="showAddUserModal = false" class="bg-slate-900 border border-white/20 rounded-4xl p-8 max-w-md w-full space-y-6 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.9)]">
                <div class="flex items-center justify-between pb-4 border-b border-white/10">
                    <h3 class="text-lg font-black text-white">Add User Account for {{ $tenant->name }}</h3>
                    <button @click="showAddUserModal = false" class="text-slate-400 hover:text-white font-black text-sm cursor-pointer">✕</button>
                </div>

                <form action="{{ route('superadmin.tenants.users.store', $tenant->id) }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Ruwan Perera"
                               class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Email Address *</label>
                        <input type="email" name="email" required placeholder="e.g. ruwan@facility.lk"
                               class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white placeholder-slate-400 font-medium focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Role Permissions *</label>
                        <select name="role" required class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-white/20 text-white font-semibold focus:outline-none focus:border-white/60 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                            <option value="owner" class="bg-slate-950 text-white">Owner (Full Admin Access)</option>
                            <option value="manager" class="bg-slate-950 text-white">Manager (Bookings & Pricing Control)</option>
                            <option value="front_desk" class="bg-slate-950 text-white">Front Desk (Check-in & Reservations)</option>
                            <option value="trainer_staff" class="bg-slate-950 text-white">Trainer Staff</option>
                            <option value="customer" class="bg-slate-950 text-white">Customer / Player</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-300">Password *</label>
                        <input type="password" name="password" required value="password123"
                               class="w-full px-4 py-3 rounded-2xl bg-white/10 border border-white/20 text-white font-mono font-bold focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddUserModal = false" class="px-4 py-2.5 rounded-full text-xs font-bold text-slate-400 hover:text-white transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all hover:scale-105 active:scale-[0.98] cursor-pointer">
                            Create User &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.super_admin>
