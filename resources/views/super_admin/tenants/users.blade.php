<x-layouts.super_admin title="Manage Tenant Users | SLTDS Super Admin">

    <div x-data="{ showAddUserModal: false }" class="space-y-8">

        <!-- CLEAN OPERATIONAL PAGE HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200">
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-950 tracking-tight">User Accounts: {{ $tenant->name }}</h1>
                <p class="text-xs text-slate-500 font-medium">Super-Admin direct control over user accounts and staff role permissions for this facility workspace.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants') }}" class="px-5 py-2.5 rounded-full text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                    &larr; Back to Directory
                </a>
                <button type="button" @click="showAddUserModal = true" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-black text-white bg-slate-950 hover:bg-slate-800 transition-all shadow-md cursor-pointer">
                    + Add User
                </button>
            </div>
        </div>

        <!-- HIGH-DENSITY USER TABLE -->
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-950 text-white uppercase tracking-wider font-black text-[10px]">
                        <tr>
                            <th class="py-4 px-6">User Name</th>
                            <th class="py-4 px-4">Email Address</th>
                            <th class="py-4 px-4">Role</th>
                            <th class="py-4 px-4">Account Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 font-black text-slate-950 text-sm">
                                    {{ $u->name }}
                                </td>
                                <td class="py-4 px-4 font-mono font-medium text-slate-600 text-[11px]">
                                    {{ $u->email }}
                                </td>
                                <td class="py-4 px-4">
                                    @if(in_array($u->role, ['owner', 'manager']))
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-950 text-white">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @elseif(in_array($u->role, ['trainer_staff', 'front_desk']))
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-100 text-slate-700 border border-slate-300">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($u->is_banned)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-50 text-rose-800 border border-rose-200">Suspended</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">Active</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if(!$u->isSuperAdmin())
                                        <form action="{{ route('superadmin.tenants.users.delete', ['tenantId' => $tenant->id, 'userId' => $u->id]) }}" method="POST" onsubmit="return confirm('Delete user account {{ $u->name }} ({{ $u->email }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-full text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 transition-colors cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Platform Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-14 text-center text-slate-400 font-medium">
                                    No user accounts exist for this tenant facility.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ADD USER MODAL (Lightweight White Card) -->
        <div x-show="showAddUserModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="showAddUserModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full space-y-6 border border-slate-200 shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-950 tracking-tight">Add User to {{ $tenant->name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Create a staff or owner account for this facility.</p>
                    </div>
                    <button type="button" @click="showAddUserModal = false" class="text-slate-400 hover:text-slate-950 font-black text-sm cursor-pointer leading-none">&times;</button>
                </div>

                <form action="{{ route('superadmin.tenants.users.store', $tenant->id) }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Kasun Jayawardena"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Email Address *</label>
                        <input type="email" name="email" required placeholder="e.g. kasun@facility.lk"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-medium focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Role Permission *</label>
                        <select name="role" required
                                class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-bold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                            <option value="owner">Facility Owner</option>
                            <option value="manager">Facility Manager</option>
                            <option value="front_desk">Front Desk / Staff</option>
                            <option value="trainer_staff">Trainer / Coach</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Password *</label>
                        <input type="text" name="password" required value="password123"
                               class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-950 font-mono font-bold focus:outline-none focus:bg-white focus:border-slate-950 transition-all">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddUserModal = false" class="px-4 py-2.5 rounded-full text-xs font-bold text-slate-500 hover:text-slate-950 cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-black text-white bg-slate-950 hover:bg-slate-800 shadow-md transition-all">
                            Create Account &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.super_admin>
