<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\PricingRule;
use App\Models\Role;
use App\Models\CreditLedger;
use App\Models\CustomerPass;
use App\Services\TenantResolver;
use App\Services\BookingEngineService;
use App\Services\CreditAndPassService;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $today = Carbon::today()->toDateString();

        $totalSlotsToday = TimeSlot::where('date', '=', $today, 'and')->count();
        $bookedSlotsToday = TimeSlot::where('date', '=', $today, 'and')->where('status', '=', 'booked', 'and')->count();
        $occupancyRate = ($totalSlotsToday > 0) ? round(($bookedSlotsToday / $totalSlotsToday) * 100) : 0;

        $stats = [
            'bookings_today' => Booking::where('booking_date', '=', $today, 'and')->count(),
            'weekly_revenue' => Booking::where('payment_status', '=', 'paid', 'and')
                ->where('booking_date', '>=', Carbon::today()->subDays(7)->toDateString(), 'and')
                ->sum('total_amount'),
            'occupancy_rate' => $occupancyRate,
            'active_members' => User::where('role', '=', 'customer', 'and')->count(),
        ];

        $recentBookings = Booking::with('court.sportCategory')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $courts = Court::with('sportCategory')->get();

        return view('admin.index', compact('tenant', 'stats', 'recentBookings', 'courts'));
    }

    public function bookings(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        
        $statusFilter = $request->query('status', 'all');
        $searchQuery = $request->query('search');

        $query = Booking::where('tenant_id', '=', $tenant->id, 'and')
            ->with('court.sportCategory')
            ->orderBy('booking_date', 'desc')
            ->orderBy('id', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', '=', $statusFilter, 'and');
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('customer_name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('booking_reference', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('customer_email', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$searchQuery}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings', compact('tenant', 'bookings', 'statusFilter', 'searchQuery'));
    }

    public function courts(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $categories = SportCategory::all(['*']);
        $courts = Court::where('tenant_id', '=', $tenant->id, 'and')
            ->with('sportCategory')
            ->get();

        return view('admin.courts', compact('tenant', 'categories', 'courts'));
    }

    public function storeCourt(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);

        $validated = $request->validate([
            'sport_category_id' => ['required', 'integer', 'exists:sport_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:indoor,outdoor,covered'],
            'surface_type' => ['nullable', 'string', 'max:255'],
            'hourly_rate' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'peak_hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'image_file' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $imageUrl = $validated['image_url'] ?? '/images/courts/tennis.png';
        if ($request->hasFile('image_file')) {
            $uploadDir = public_path('uploads/tenants/' . $tenant->slug . '/courts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $file = $request->file('image_file');
            $filename = 'court_' . time() . '_' . \Illuminate\Support\Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imageUrl = '/uploads/tenants/' . $tenant->slug . '/courts/' . $filename;
        }

        Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $validated['sport_category_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'surface_type' => $validated['surface_type'] ?? 'Standard Surface',
            'hourly_rate' => $validated['hourly_rate'],
            'peak_hourly_rate' => $validated['peak_hourly_rate'] ?? ($validated['hourly_rate'] * 1.25),
            'image_url' => $imageUrl,
            'description' => $validated['description'] ?? 'Newly created court resource.',
            'is_active' => true,
        ]);

        return redirect()->route('admin.courts')->with('status', 'New court resource created successfully!');
    }

    public function updateCourt(Request $request, int $id)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $court = Court::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'hourly_rate' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'peak_hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'image_file' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['required', 'boolean'],
        ]);

        // Safety check for deactivating a court with active/future bookings
        if (!$validated['is_active'] && $court->is_active) {
            $today = Carbon::today()->toDateString();
            $activeBookingsCount = Booking::where('court_id', '=', $court->id, 'and')
                ->where('booking_date', '>=', $today, 'and')
                ->whereIn('status', ['confirmed', 'pending', 'payment_pending'], 'and')
                ->count();

            if ($activeBookingsCount > 0) {
                return back()->withInput()->with('error', "Cannot deactivate court '{$court->name}' because it has {$activeBookingsCount} active or upcoming reservation(s). Please cancel or reassign those bookings first.");
            }
        }

        $imageUrl = $validated['image_url'] ?? $court->image_url;
        if ($request->hasFile('image_file')) {
            $uploadDir = public_path('uploads/tenants/' . $tenant->slug . '/courts');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $file = $request->file('image_file');
            $filename = 'court_' . time() . '_' . \Illuminate\Support\Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $imageUrl = '/uploads/tenants/' . $tenant->slug . '/courts/' . $filename;
        }

        $court->update([
            'name' => $validated['name'],
            'hourly_rate' => $validated['hourly_rate'],
            'peak_hourly_rate' => $validated['peak_hourly_rate'] ?? ($validated['hourly_rate'] * 1.25),
            'image_url' => $imageUrl,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.courts')->with('status', "Court {$court->name} updated successfully.");
    }

    public function deleteCourt(int $id)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $court = Court::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        $totalBookingsCount = Booking::where('court_id', '=', $court->id, 'and')->count();

        if ($totalBookingsCount > 0) {
            return back()->with('error', "Cannot delete court resource '{$court->name}' because it has {$totalBookingsCount} associated reservation record(s). To remove this court from availability, please edit the court and set its status to Inactive instead.");
        }

        $court->delete();

        return redirect()->route('admin.courts')->with('status', "Court resource '{$court->name}' deleted successfully.");
    }

    public function markNoShow(Request $request, int $id, BookingEngineService $bookingEngine)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $booking = Booking::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        if ($booking->status === 'cancelled') {
            return back()->with('error', "Cannot mark a cancelled booking ({$booking->booking_reference}) as No-Show.");
        }

        if ($booking->status === 'no_show') {
            return back()->with('status', "Booking {$booking->booking_reference} is already recorded as No-Show.");
        }

        $today = Carbon::today()->toDateString();
        if ($booking->booking_date > $today) {
            return back()->with('error', "Cannot mark a future booking ({$booking->booking_reference} on {$booking->booking_date}) as No-Show before the scheduled date.");
        }

        $staffUser = Auth::user();
        $bookingEngine->markNoShow($booking, $staffUser, $request->input('notes'));

        return back()->with('status', "Booking {$booking->booking_reference} marked as No-Show. Customer attendance record updated.");
    }

    public function markPaid(Request $request, int $id, \App\Services\InvoiceService $invoiceService)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $booking = Booking::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        if ($booking->status === 'cancelled') {
            return back()->with('error', "Cannot mark a cancelled booking ({$booking->booking_reference}) as Paid.");
        }

        if ($booking->payment_status === 'paid') {
            return back()->with('status', "Booking {$booking->booking_reference} is already marked as Paid.");
        }

        $booking->payment_status = 'paid';
        $booking->paid_at = Carbon::now();
        $booking->save();

        $invoice = $invoiceService->generateInvoice($booking);

        return back()->with('status', "Booking {$booking->booking_reference} marked as Paid. Invoice {$invoice->invoice_number} generated successfully.");
    }

    public function cancelBooking(Request $request, int $id, BookingEngineService $bookingEngine)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $booking = Booking::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        if ($booking->status === 'cancelled') {
            return back()->with('error', "Booking {$booking->booking_reference} is already cancelled.");
        }

        $reason = $request->input('notes') ? ('Admin Cancel: ' . $request->input('notes')) : 'Cancelled by facility staff via admin portal';

        try {
            $bookingEngine->cancelBooking($booking, $reason);
            return back()->with('status', "Booking {$booking->booking_reference} cancelled successfully. Reserved court slots freed up.");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to cancel booking {$booking->booking_reference}: " . $e->getMessage());
        }
    }

    public function pricing(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $courts = Court::where('tenant_id', '=', $tenant->id, 'and')->get();
        $categories = SportCategory::all(['*']);
        $pricingRules = PricingRule::where('tenant_id', '=', $tenant->id, 'and')
            ->orderBy('priority', 'asc')
            ->get();

        return view('admin.pricing', compact('tenant', 'courts', 'categories', 'pricingRules'));
    }

    public function storePricingRule(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rule_type' => ['required', 'string'],
            'discount_type' => ['nullable', 'string'],
            'adjustment_type' => ['required', 'string'],
            'adjustment_value' => ['required', 'numeric'],
            'court_id' => ['nullable', 'integer'],
            'sport_category_id' => ['nullable', 'integer'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'min_slots' => ['nullable', 'integer'],
        ]);

        PricingRule::create([
            'tenant_id' => $tenant->id,
            'court_id' => $validated['court_id'] ?: null,
            'sport_category_id' => $validated['sport_category_id'] ?: null,
            'name' => $validated['name'],
            'rule_type' => $validated['rule_type'],
            'discount_type' => $validated['discount_type'] ?? 'none',
            'adjustment_type' => $validated['adjustment_type'],
            'adjustment_value' => $validated['adjustment_value'],
            'start_time' => $validated['start_time'] ?: null,
            'end_time' => $validated['end_time'] ?: null,
            'start_date' => $validated['start_date'] ?: null,
            'end_date' => $validated['end_date'] ?: null,
            'min_slots' => $validated['min_slots'] ?? 1,
            'priority' => 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.pricing')->with('status', 'Pricing rule added successfully!');
    }

    public function togglePricingRule(int $id)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $rule = PricingRule::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();

        return redirect()->route('admin.pricing')->with('status', 'Pricing rule status updated.');
    }

    public function deletePricingRule(int $id)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $rule = PricingRule::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.pricing')->with('status', 'Pricing rule deleted.');
    }

    public function customers(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $customers = User::where('tenant_id', '=', $tenant->id, 'and')
            ->where('role', '=', 'customer', 'and')
            ->with(['creditLedgers', 'passes'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.customers', compact('tenant', 'customers'));
    }

    public function issueCredits(Request $request, int $id, CreditAndPassService $creditAndPassService)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $customer = User::where('tenant_id', '=', $tenant->id, 'and')
            ->where('role', '=', 'customer', 'and')
            ->findOrFail($id);

        /** @var \App\Models\User $staffUser */
        $staffUser = Auth::user();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:500000'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $creditAndPassService->issueCredits($customer, (float)$validated['amount'], $validated['reason'], $staffUser);

        return back()->with('status', "Issued LKR " . number_format($validated['amount'], 2) . " wallet credits to {$customer->name} successfully.");
    }

    public function issuePass(Request $request, int $id, CreditAndPassService $creditAndPassService)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        $customer = User::where('tenant_id', '=', $tenant->id, 'and')
            ->where('role', '=', 'customer', 'and')
            ->findOrFail($id);

        $validated = $request->validate([
            'pass_name' => ['required', 'string', 'max:255'],
            'total_units' => ['required', 'integer', 'min:1', 'max:1000'],
            'price_paid' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'valid_days' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $expiresAt = Carbon::today()->addDays((int)$validated['valid_days']);

        $creditAndPassService->issuePass(
            $customer,
            $validated['pass_name'],
            (int)$validated['total_units'],
            (float)$validated['price_paid'],
            $expiresAt
        );

        return back()->with('status', "Issued {$validated['pass_name']} ({$validated['total_units']} units) to {$customer->name} successfully.");
    }

    public function staff(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $staffMembers = User::where('tenant_id', '=', $tenant->id, 'and')
            ->whereIn('role', ['owner', 'manager', 'trainer_staff', 'front_desk'], 'and')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.staff', compact('tenant', 'staffMembers'));
    }

    public function storeStaff(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Role assignment authorization rule: Only Owner or Super Admin can assign the 'owner' role.
        $allowedRoles = ['manager', 'trainer_staff', 'front_desk'];
        if ($currentUser->role === 'owner' || $currentUser->isSuperAdmin()) {
            $allowedRoles[] = 'owner';
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'in:' . implode(',', $allowedRoles)],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);

        $roleModel = Role::where('tenant_id', '=', $tenant->id, 'and')
            ->where('slug', '=', $validated['role'], 'and')
            ->first();
        if ($roleModel) {
            $user->roles()->attach($roleModel);
        }

        Log::info("[STAFF ACCOUNT CREATED] Staff account created for {$validated['name']} ({$validated['email']}) with role {$validated['role']} under tenant {$tenant->name}.");

        return redirect()->route('admin.staff')->with('status', "Staff account for {$validated['name']} ({$validated['role']}) created successfully.");
    }

    public function deleteStaff(int $id)
    {
        $tenant = TenantResolver::getActiveTenantModel();
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();

        // Tenant scope check: Staff user must belong to active tenant
        $staffUser = User::where('tenant_id', '=', $tenant->id, 'and')->findOrFail($id);

        // Rule 1: Cannot remove own logged in account
        if ($staffUser->id === $currentUser->id) {
            return back()->with('error', "You cannot remove your own active staff account.");
        }

        // Rule 2: A Manager cannot remove an Owner account
        if ($staffUser->role === 'owner' && $currentUser->role === 'manager' && !$currentUser->isSuperAdmin()) {
            return back()->with('error', "Unauthorized: Manager role cannot remove an Owner account.");
        }

        // Rule 3: Cannot remove the LAST remaining Owner account of the tenant
        if ($staffUser->role === 'owner') {
            $ownerCount = User::where('tenant_id', '=', $tenant->id, 'and')
                ->where('role', '=', 'owner', 'and')
                ->count();

            if ($ownerCount <= 1) {
                return back()->with('error', "Action Blocked: Cannot remove the last remaining Owner account for {$tenant->name}. Every facility must retain at least one active Owner.");
            }
        }

        $staffUser->delete();

        return redirect()->route('admin.staff')->with('status', "Staff account for {$staffUser->name} removed successfully.");
    }
}
