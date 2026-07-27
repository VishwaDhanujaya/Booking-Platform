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

        $query = Booking::with('court.sportCategory')->orderBy('id', 'desc');

        if ($statusFilter !== 'all') {
            $query->where('status', '=', $statusFilter, 'and');
        }

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('customer_name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('booking_reference', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('customer_email', 'LIKE', "%{$searchQuery}%");
            });
        }

        $bookings = $query->get();

        return view('admin.bookings', compact('tenant', 'bookings', 'statusFilter', 'searchQuery'));
    }

    public function courts(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $categories = SportCategory::all(['*']);
        $courts = Court::with('sportCategory')->get();

        return view('admin.courts', compact('tenant', 'categories', 'courts'));
    }

    public function storeCourt(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);

        $validated = $request->validate([
            'sport_category_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'surface_type' => ['nullable', 'string'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'peak_hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $validated['sport_category_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'surface_type' => $validated['surface_type'] ?? 'Standard Surface',
            'hourly_rate' => $validated['hourly_rate'],
            'peak_hourly_rate' => $validated['peak_hourly_rate'] ?? ($validated['hourly_rate'] * 1.25),
            'image_url' => '/images/courts/tennis.png',
            'description' => $validated['description'] ?? 'Newly created court resource.',
            'is_active' => true,
        ]);

        return redirect()->route('admin.courts')->with('status', 'New court resource created successfully!');
    }

    public function updateCourt(Request $request, int $id)
    {
        $court = Court::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'hourly_rate' => ['required', 'numeric', 'min:0'],
            'peak_hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $court->update([
            'name' => $validated['name'],
            'hourly_rate' => $validated['hourly_rate'],
            'peak_hourly_rate' => $validated['peak_hourly_rate'] ?? ($validated['hourly_rate'] * 1.25),
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('admin.courts')->with('status', "Court {$court->name} updated successfully.");
    }

    public function deleteCourt(int $id)
    {
        $court = Court::findOrFail($id);
        $court->delete();

        return redirect()->route('admin.courts')->with('status', 'Court deleted successfully.');
    }

    public function markNoShow(Request $request, int $id, BookingEngineService $bookingEngine)
    {
        $booking = Booking::findOrFail($id);
        $staffUser = Auth::user();

        $bookingEngine->markNoShow($booking, $staffUser, $request->input('notes'));

        return back()->with('status', "Booking {$booking->booking_reference} marked as No-Show. Customer attendance record updated.");
    }

    public function markPaid(Request $request, int $id, \App\Services\InvoiceService $invoiceService)
    {
        $booking = Booking::findOrFail($id);

        $booking->payment_status = 'paid';
        $booking->paid_at = Carbon::now();
        $booking->save();

        $invoice = $invoiceService->generateInvoice($booking);

        return back()->with('status', "Booking {$booking->booking_reference} marked as Paid. Invoice {$invoice->invoice_number} generated successfully.");
    }

    public function pricing(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $courts = Court::all(['*']);
        $categories = SportCategory::all(['*']);
        $pricingRules = PricingRule::orderBy('priority', 'asc')->get();

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
        $rule = PricingRule::findOrFail($id);
        $rule->is_active = !$rule->is_active;
        $rule->save();

        return redirect()->route('admin.pricing')->with('status', 'Pricing rule status updated.');
    }

    public function deletePricingRule(int $id)
    {
        $rule = PricingRule::findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.pricing')->with('status', 'Pricing rule deleted.');
    }

    public function customers(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);
        $customers = User::where('role', '=', 'customer', 'and')
            ->with(['creditLedgers', 'passes'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.customers', compact('tenant', 'customers'));
    }

    public function issueCredits(Request $request, int $id, CreditAndPassService $creditAndPassService)
    {
        $customer = User::findOrFail($id);
        $staffUser = Auth::user();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $creditAndPassService->issueCredits($customer, (float)$validated['amount'], $validated['reason'], $staffUser);

        return back()->with('status', "Issued LKR " . number_format($validated['amount'], 2) . " credits to {$customer->name} successfully.");
    }

    public function issuePass(Request $request, int $id, CreditAndPassService $creditAndPassService)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'pass_name' => ['required', 'string', 'max:255'],
            'total_units' => ['required', 'integer', 'min:1'],
            'price_paid' => ['required', 'numeric', 'min:0'],
            'valid_days' => ['required', 'integer', 'min:1'],
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
        $staffMembers = User::whereIn('role', ['owner', 'manager', 'trainer_staff', 'front_desk'], 'and')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.staff', compact('tenant', 'staffMembers'));
    }

    public function storeStaff(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first(['*']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'string', 'in:owner,manager,trainer_staff,front_desk'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'tenant_id' => $tenant->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);

        Log::info("[STAFF INVITATION LOG] Staff account created for {$validated['name']} ({$validated['email']}) with role {$validated['role']}.");

        return redirect()->route('admin.staff')->with('status', "Staff account for {$validated['name']} created successfully.");
    }

    public function deleteStaff(int $id)
    {
        $staffUser = User::findOrFail($id);
        if ($staffUser->id === Auth::id()) {
            return back()->with('error', "You cannot remove your own active staff account.");
        }
        $staffUser->delete();

        return redirect()->route('admin.staff')->with('status', "Staff account removed.");
    }
}
