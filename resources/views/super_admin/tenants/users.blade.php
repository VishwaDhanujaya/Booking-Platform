<x-layouts.super_admin title="Manage Tenant Users | SLTDS Super Admin">

    <div x-data="{ showAddUserModal: false }" class="space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-sltds-sky/30 shadow-xs">
                        User Access Control
                    </span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">User Accounts: {{ $tenant->name }}</h1>
                <p class="text-xs text-slate-500">Super-Admin direct control over user accounts and staff roles for this facility.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('superadmin.tenants') }}" class="text-xs font-bold text-slate-600 hover:text-sltds-endeavour transition-colors">
                    &larr; Back to Directory
                </a>
                <button type="button" @click="showAddUserModal = true" class="px-4.5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all flex items-center gap-1.5 cursor-pointer">
                    + Add Tenant User
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white border-2 border-slate-200/80 rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-4 px-6">User Name</th>
                            <th class="py-4 px-4">Email Address</th>
                            <th class="py-4 px-4">Role Tag</th>
                            <th class="py-4 px-4">Account Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($users as $u)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 font-extrabold text-slate-900">
                                    {{ $u->name }}
                                </td>
                                <td class="py-4 px-4 font-mono font-medium text-slate-600">
                                    {{ $u->email }}
                                </td>
                                <td class="py-4 px-4 capitalize">
                                    @if(in_array($u->role, ['owner', 'manager']))
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase bg-blue-50 text-sltds-endeavour border border-blue-200">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @elseif(in_array($u->role, ['trainer_staff', 'front_desk']))
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ str_replace('_', ' ', $u->role) }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($u->is_banned)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Suspended</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-sltds-green border border-emerald-200">Active</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @if(!$u->isSuperAdmin())
                                        <form action="{{ route('superadmin.tenants.users.delete', ['tenantId' => $tenant->id, 'userId' => $u->id]) }}" method="POST" onsubmit="return confirm('Delete user account {{ $u->name }} ({{ $u->email }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-slate-400 font-bold">Platform Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-500">
                                    No user accounts exist for this tenant facility.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add User Modal -->
        <div x-show="showAddUserModal" x-cloak class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showAddUserModal = false" class="bg-white rounded-3xl p-8 max-w-md w-full space-y-6 border border-slate-200 shadow-2xl relative text-left">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="text-[10px] font-black text-sltds-endeavour uppercase tracking-wider block">Super-Admin User Creation</span>
                        <h3 class="text-lg font-black text-slate-900 mt-0.5">Add User to {{ $tenant->name }}</h3>
                    </div>
                    <button type="button" @click="showAddUserModal = false" class="text-slate-400 hover:text-slate-900 font-bold text-sm">✕</button>
                </div>

                <form action="{{ route('superadmin.tenants.users.store', $tenant->id) }}" method="POST" class="space-y-4 text-xs">
                    @csrf

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                        <input type="text" name="name" required placeholder="e.g. Kasun Jayawardena" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                        <input type="email" name="email" required placeholder="e.g. kasun@facility.lk" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Role Permission *</label>
                        <select name="role" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold">
                            <option value="owner">Facility Owner</option>
                            <option value="manager">Facility Manager</option>
                            <option value="front_desk">Front Desk / Staff</option>
                            <option value="trainer_staff">Trainer / Coach</option>
                            <option value="customer">Customer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Password *</label>
                        <input type="text" name="password" required value="password123" class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono font-bold">
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showAddUserModal = false" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-900">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md">
                            Create User Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-layouts.super_admin>
