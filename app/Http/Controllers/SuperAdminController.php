<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Role;
use App\Models\Booking;
use App\Models\ImpersonationLog;
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

        $recentImpersonations = ImpersonationLog::with(['superAdmin', 'tenant', 'impersonatedUser'])
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
            'recentImpersonations'
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
     * Log In As (Impersonate Tenant Owner) with Audit Log Entry
     */
    public function impersonate(Request $request, int $id)
    {
        /** @var \App\Models\User $superAdminUser */
        $superAdminUser = Auth::user();

        if (!$superAdminUser || !$superAdminUser->isSuperAdmin()) {
            abort(403, 'Unauthorized Access: Only genuine Platform Super-Admins can initiate tenant impersonation.');
        }

        $tenant = Tenant::findOrFail($id);

        // Find Owner or Manager of target tenant without global tenant scope
        $targetUser = User::withoutGlobalScopes()
            ->where('tenant_id', '=', $tenant->id, 'and')
            ->whereIn('role', ['owner', 'manager'], 'and', false)
            ->first();

        if (!$targetUser) {
            $targetUser = User::withoutGlobalScopes()
                ->where('tenant_id', '=', $tenant->id, 'and')
                ->first();
        }

        if (!$targetUser) {
            // Auto-provision Owner Account for this tenant on demand so impersonation never fails
            $ownerRole = Role::where('tenant_id', '=', $tenant->id, 'and')->where('slug', '=', 'owner', 'and')->first();
            if (!$ownerRole) {
                $ownerRole = Role::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Owner',
                    'slug' => 'owner',
                    'description' => 'Full administrative control over facility settings.',
                    'permissions' => ['all' => true],
                ]);
            }

            $targetUser = User::create([
                'tenant_id' => $tenant->id,
                'name' => $tenant->name . ' Owner',
                'email' => 'owner@' . $tenant->slug . '.lk',
                'password' => bcrypt('password123'),
                'role' => 'owner',
                'is_super_admin' => false,
            ]);

            $targetUser->roles()->attach($ownerRole);
        }

        // Write Audit Log Entry
        ImpersonationLog::create([
            'super_admin_id' => $superAdminUser->id,
            'tenant_id' => $tenant->id,
            'impersonated_user_id' => $targetUser->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Save impersonation state in session
        session([
            'is_impersonating' => true,
            'original_super_admin_id' => $superAdminUser->id,
            'original_super_admin_name' => $superAdminUser->name,
            'impersonated_tenant_name' => $tenant->name,
            'impersonated_tenant_id' => $tenant->id,
        ]);

        // Set active tenant context
        TenantResolver::setActiveTenantContext($tenant);

        // Authenticate as target tenant user
        Auth::login($targetUser);

        return redirect('/' . $tenant->slug . '/admin')
            ->with('status', "Impersonation Active: Logged in as {$targetUser->name} ({$targetUser->role}) for {$tenant->name}. Audit log recorded.");
    }

    /**
     * Stop Impersonating & Return to Super Admin Panel
     */
    public function stopImpersonating(Request $request)
    {
        $originalSuperAdminId = session('original_super_admin_id');

        if ($originalSuperAdminId) {
            $superAdminUser = User::withoutGlobalScopes()->where('id', '=', $originalSuperAdminId, 'and')->first();
            if ($superAdminUser && $superAdminUser->isSuperAdmin()) {
                Auth::login($superAdminUser);
            } else {
                Auth::logout();
                return redirect()->route('superadmin.login')->with('error', 'Super Admin session expired. Please sign in again.');
            }
        } elseif (Auth::check() && Auth::user()->isSuperAdmin()) {
            // Already logged in as super admin
            $superAdminUser = Auth::user();
        } else {
            return redirect()->route('superadmin.login');
        }

        $request->session()->forget([
            'is_impersonating',
            'original_super_admin_id',
            'original_super_admin_name',
            'impersonated_tenant_name',
            'impersonated_tenant_id',
        ]);

        TenantResolver::clearTenantContext();

        return redirect()->route('superadmin.dashboard')->with('status', 'Returned to Platform Super-Admin Panel.');
    }
}
