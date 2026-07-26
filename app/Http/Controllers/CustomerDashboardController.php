<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Booking;
use Carbon\Carbon;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Tenant::first(['*']);
        if (!$tenant) {
            $tenant = new Tenant(['id' => 1, 'name' => 'Colombo Courts Club', 'slug' => 'colombo-courts-club']);
            $tenant->id = 1;
        }

        // Fetch all bookings for demo customer
        $allBookings = Booking::where('tenant_id', '=', $tenant->id, 'and')
            ->with('court.sportCategory')
            ->orderBy('booking_date', 'desc')
            ->get();

        $today = Carbon::today()->toDateString();

        $upcomingBookings = $allBookings->filter(function ($b) use ($today) {
            return $b->booking_date >= $today && $b->status !== 'cancelled';
        });

        $pastBookings = $allBookings->filter(function ($b) use ($today) {
            return $b->booking_date < $today && $b->status !== 'cancelled';
        });

        $cancelledBookings = $allBookings->filter(function ($b) {
            return $b->status === 'cancelled';
        });

        // Mock Customer Profile & Credits/Passes
        $customer = [
            'name' => session('user_name', 'Kavinda Perera'),
            'email' => session('user_email', 'kavinda@example.com'),
            'phone' => '+94 77 123 4567',
            'member_id' => 'CCC-MEMBER-984',
            'tier' => 'Club Pass Member',
            'favorite_sport' => 'Padel & Tennis',
        ];

        $credits = [
            'balance' => 15000.00,
            'currency' => 'LKR',
            'last_topup' => '2026-07-15',
        ];

        $activePasses = [
            [
                'id' => 1,
                'name' => 'Monthly Club Pass',
                'sport' => 'All Sports (Tennis, Padel, Badminton, Squash)',
                'discount' => '20% Off All Booking Fees',
                'valid_until' => Carbon::today()->addDays(18)->format('M j, Y'),
                'status' => 'Active',
                'color' => 'brand',
            ],
            [
                'id' => 2,
                'name' => 'Badminton 10-Session Pack',
                'sport' => 'Badminton',
                'discount' => '4 Free Peak Hours Remaining',
                'valid_until' => Carbon::today()->addDays(45)->format('M j, Y'),
                'status' => 'Active',
                'color' => 'accent',
            ],
        ];

        return view('customer.dashboard', compact(
            'tenant',
            'customer',
            'upcomingBookings',
            'pastBookings',
            'cancelledBookings',
            'credits',
            'activePasses'
        ));
    }

    public function cancelBooking(Request $request, int $id)
    {
        $booking = Booking::find($id, ['*']);
        if ($booking) {
            $booking->status = 'cancelled';
            $booking->save();
        }

        return redirect()->route('customer.my-bookings')->with('status', 'Booking reference ' . ($booking ? $booking->booking_reference : '') . ' has been cancelled successfully.');
    }

    public function updateProfile(Request $request)
    {
        session(['user_name' => $request->input('name', 'Kavinda Perera')]);
        return redirect()->route('customer.my-bookings')->with('status', 'Profile information updated successfully.');
    }
}
