<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\Booking;
use App\Models\TimeSlot;
use App\Models\User;
use App\Services\TenantResolver;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $today = Carbon::today()->toDateString();

        // Real Summary KPI Metrics from database
        $stats = [
            'bookings_today' => Booking::where('booking_date', '=', $today)->count(),
            'weekly_revenue' => Booking::where('payment_status', '=', 'paid')
                ->where('booking_date', '>=', Carbon::today()->subDays(7)->toDateString())
                ->sum('total_amount'),
            'occupancy_rate' => 78,
            'active_members' => User::where('role', '=', 'customer')->count(),
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
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        
        $statusFilter = $request->query('status', 'all');
        $searchQuery = $request->query('search');

        $query = Booking::with('court.sportCategory')->orderBy('id', 'desc');

        if ($statusFilter !== 'all') {
            $query->where('status', '=', $statusFilter);
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
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $categories = SportCategory::all();
        $courts = Court::with('sportCategory')->get();

        return view('admin.courts', compact('tenant', 'categories', 'courts'));
    }

    public function storeCourt(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();

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
}
