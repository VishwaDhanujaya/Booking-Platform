<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Booking;
use App\Services\TenantResolver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Platform-wide Dashboard with Aggregate Metrics
     */
    public function dashboard()
    {
        $totalTenants = Tenant::count(['*']);
        $activeTenants = Tenant::where('is_active', '=', true, 'and')->count(['*']);
        $suspendedTenants = Tenant::where('is_active', '=', false, 'and')->count(['*']);

        $startOfWeek = Carbon::now()->startOfWeek();
        $totalBookingsThisWeek = Booking::where('created_at', '>=', $startOfWeek, 'and')->count(['*']);

        $startOfMonth = Carbon::now()->startOfMonth();
        $signupsThisMonth = Tenant::where('created_at', '>=', $startOfMonth, 'and')->count(['*']);

        $recentTenants = Tenant::orderBy('created_at', 'desc')->limit(5)->get(['*']);

        $recentUsers = User::withoutGlobalScopes()
            ->with('tenant')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['*']);

        return view('super_admin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'suspendedTenants',
            'totalBookingsThisWeek',
            'signupsThisMonth',
            'recentTenants',
            'recentUsers'
        ));
    }

    /**
     * Tenant Directory & Management Screen
     */
    public function tenants(Request $request)
    {
        $query = Tenant::query();

        if ($request->has('search')) {
            $search = (string) $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }

        if ($request->has('plan') && $request->query('plan') !== 'all') {
            $query->where('subscription_plan', '=', $request->query('plan'), 'and');
        }

        if ($request->has('status') && $request->query('status') !== 'all') {
            if ($request->query('status') === 'active') {
                $query->where('is_active', '=', true, 'and');
            } elseif ($request->query('status') === 'suspended') {
                $query->where('is_active', '=', false, 'and');
            }
        }

        $tenants = $query->withCount(['courts', 'users', 'bookings'])->orderBy('created_at', 'desc')->get(['*']);

        return view('super_admin.tenants.index', compact('tenants'));
    }

    /**
     * Show Create Tenant Form (Staff Assisted Path)
     */
    public function createTenant()
    {
        return view('super_admin.tenants.create');
    }

    /**
     * Store New Tenant & Initial Owner Credentials
     */
    public function storeTenant(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:tenants,slug'],
            'domain' => ['nullable', 'string', 'max:255', 'unique:tenants,domain'],
            'category' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'brand_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'subscription_plan' => ['required', 'string', 'in:starter,pro,enterprise'],
            'is_public' => ['nullable', 'boolean'],
            
            // Initial Owner Account
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'owner_password' => ['required', 'string', 'min:8'],
        ]);

        $tenant = DB::transaction(function () use ($validated) {
            $slug = strtolower($validated['slug']);
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'domain' => $validated['domain'] ?? ($slug . '.localhost'),
                'category' => $validated['category'],
                'tagline' => $validated['tagline'] ?? null,
                'description' => $validated['description'] ?? null,
                'location' => $validated['location'] ?? 'Colombo, Sri Lanka',
                'brand_color' => $validated['brand_color'] ?? '#0284c7',
                'subscription_plan' => $validated['subscription_plan'],
                'subscription_status' => 'active',
                'is_active' => true,
                'is_public' => (bool) ($validated['is_public'] ?? true),
            ]);

            // Create Owner Role for Tenant
            $ownerRole = Role::create([
                'tenant_id' => $tenant->id,
                'name' => 'Owner',
                'slug' => 'owner',
                'description' => 'Full administrative control over facility settings.',
                'permissions' => ['all' => true],
            ]);

            // Create Owner User Account
            $ownerUser = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'password' => Hash::make($validated['owner_password']),
                'role' => 'owner',
                'is_super_admin' => false,
            ]);

            $ownerUser->roles()->attach($ownerRole);

            return $tenant;
        });

        return redirect()->route('superadmin.tenants')
            ->with('status', "Tenant '{$tenant->name}' provisioned successfully! Owner account created for {$validated['owner_email']}.");
    }

    /**
     * Toggle Tenant Active / Suspended Status
     */
    public function toggleStatus(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_active = !$tenant->is_active;
        $tenant->subscription_status = $tenant->is_active ? 'active' : 'suspended';
        $tenant->save();

        $statusText = $tenant->is_active ? 'reactivated' : 'suspended';

        return back()->with('status', "Tenant '{$tenant->name}' has been {$statusText}.");
    }

    /**
     * Toggle Tenant Public Customer Directory Visibility
     */
    public function togglePublic(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->is_public = !$tenant->is_public;
        $tenant->save();

        $visibilityText = $tenant->is_public ? 'public (visible in customer directory)' : 'private (hidden from customer directory)';

        return back()->with('status', "Tenant '{$tenant->name}' is now {$visibilityText}.");
    }

    /**
     * Edit Tenant Subscription & Branding Details
     */
    public function editTenant(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('super_admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update Tenant Settings
     */
    public function updateTenant(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'brand_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'subscription_plan' => ['required', 'string', 'in:starter,pro,enterprise'],
            'subscription_status' => ['required', 'string', 'in:active,trialing,suspended,cancelled'],
            'is_active' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'brand_color' => $validated['brand_color'] ?? '#0284c7',
            'subscription_plan' => $validated['subscription_plan'],
            'subscription_status' => $validated['subscription_status'],
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'is_public' => (bool) ($validated['is_public'] ?? false),
        ]);

        return redirect()->route('superadmin.tenants')->with('status', "Tenant '{$tenant->name}' updated successfully.");
    }

    /**
     * Manage Users for a Specific Tenant Facility (Super-Admin Direct View)
     */
    public function tenantUsers(int $id)
    {
        $tenant = Tenant::findOrFail($id);
        $users = User::withoutGlobalScopes()
            ->where('tenant_id', '=', $tenant->id, 'and')
            ->orderBy('id', 'desc')
            ->get(['*']);

        return view('super_admin.tenants.users', compact('tenant', 'users'));
    }

    /**
     * Store/Create User for a Specific Tenant
     */
    public function storeTenantUser(Request $request, int $id)
    {
        $tenant = Tenant::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:owner,manager,trainer_staff,front_desk,customer'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);

        if (in_array($validated['role'], ['owner', 'manager', 'trainer_staff', 'front_desk'])) {
            $roleModel = Role::where('tenant_id', '=', $tenant->id, 'and')
                ->where('slug', '=', $validated['role'], 'and')
                ->first();
            if (!$roleModel && $validated['role'] === 'owner') {
                $roleModel = Role::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Owner',
                    'slug' => 'owner',
                    'description' => 'Full administrative control over facility settings.',
                    'permissions' => ['all' => true],
                ]);
            }
            if ($roleModel) {
                $user->roles()->attach($roleModel);
            }
        }

        return redirect()->route('superadmin.tenants.users', $tenant->id)
            ->with('status', "User account for {$validated['name']} ({$validated['role']}) created successfully for {$tenant->name}.");
    }

    /**
     * Delete User Account for a Specific Tenant
     */
    public function deleteTenantUser(int $tenantId, int $userId)
    {
        $tenant = Tenant::findOrFail($tenantId);
        $user = User::withoutGlobalScopes()
            ->where('tenant_id', '=', $tenant->id, 'and')
            ->findOrFail($userId);

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Cannot delete Platform Super-Admin accounts from tenant management.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('superadmin.tenants.users', $tenant->id)
            ->with('status', "User account for {$userName} deleted successfully.");
    }
}
