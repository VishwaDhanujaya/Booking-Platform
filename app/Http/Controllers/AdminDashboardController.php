<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\Booking;
use App\Models\TimeSlot;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tenant = Tenant::first(['*']);
        if (!$tenant) {
            $tenant = new Tenant(['id' => 1, 'name' => 'Colombo Courts Club', 'slug' => 'colombo-courts-club']);
            $tenant->id = 1;
        }

        // Summary KPI Metrics
        $stats = [
            'bookings_today' => 28,
            'weekly_revenue' => 384500.00,
            'occupancy_rate' => 84,
            'active_members' => 142,
        ];

        $recentBookings = Booking::where('tenant_id', '=', $tenant->id, 'and')
            ->with('court.sportCategory')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $courts = Court::where('tenant_id', '=', $tenant->id, 'and')->with('sportCategory')->get();

        return view('admin.index', compact('tenant', 'stats', 'recentBookings', 'courts'));
    }

    public function bookings(Request $request)
    {
        $tenant = Tenant::first(['*']);
        
        $statusFilter = $request->query('status', 'all');
        $searchQuery = $request->query('search');

        $query = Booking::where('tenant_id', '=', $tenant->id, 'and')->with('court.sportCategory')->orderBy('id', 'desc');

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
        $tenant = Tenant::first(['*']);
        $categories = SportCategory::where('tenant_id', '=', $tenant->id, 'and')->get();
        $courts = Court::where('tenant_id', '=', $tenant->id, 'and')->with('sportCategory')->get();

        return view('admin.courts', compact('tenant', 'categories', 'courts'));
    }

    public function storeCourt(Request $request)
    {
        $tenant = Tenant::first(['*']);

        Court::create([
            'tenant_id' => $tenant->id,
            'sport_category_id' => $request->input('sport_category_id', 1),
            'name' => $request->input('name', 'New Court Arena'),
            'type' => $request->input('type', 'indoor'),
            'surface_type' => $request->input('surface_type', 'Professional Surface'),
            'hourly_rate' => $request->input('hourly_rate', 3500.00),
            'peak_hourly_rate' => $request->input('peak_hourly_rate', 4500.00),
            'image_url' => '/images/courts/tennis.png',
            'description' => $request->input('description', 'Newly added facility court.'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.courts')->with('status', 'New court resource created successfully!');
    }
}
