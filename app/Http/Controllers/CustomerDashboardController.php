<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Booking;
use App\Models\CustomerPass;
use App\Models\CreditLedger;
use App\Services\TenantResolver;
use App\Services\BookingEngineService;
use Carbon\Carbon;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $user = Auth::user();

        // Fetch authenticated user's database bookings (scoped to tenant)
        $allBookings = Booking::where('user_id', '=', $user->id)
            ->with('court.sportCategory')
            ->orderBy('booking_date', 'desc')
            ->get();

        if ($allBookings->isEmpty()) {
            $allBookings = Booking::where('customer_email', '=', $user->email)
                ->with('court.sportCategory')
                ->orderBy('booking_date', 'desc')
                ->get();
        }

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

        $customer = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '+94 77 123 4567',
            'member_id' => 'MEMBER-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'tier' => ucfirst($user->role) . ' Account',
            'favorite_sport' => 'Tennis & Padel',
        ];

        $credits = [
            'balance' => $user->credit_balance,
            'currency' => 'LKR',
            'last_topup' => CreditLedger::where('user_id', $user->id)->latest()->value('created_at')?->format('Y-m-d') ?? 'N/A',
        ];

        $activePasses = CustomerPass::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        return view('customer.dashboard', compact(
            'tenant',
            'user',
            'customer',
            'upcomingBookings',
            'pastBookings',
            'cancelledBookings',
            'credits',
            'activePasses'
        ));
    }

    public function cancelBooking(Request $request, int $id, BookingEngineService $bookingEngine)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('customer_email', $user->email);
        })->first();

        if ($booking) {
            try {
                $bookingEngine->cancelBooking($booking, 'Cancelled by customer via account portal');
                return redirect()->route('customer.my-bookings')->with('status', "Booking {$booking->booking_reference} has been cancelled successfully.");
            } catch (\Exception $e) {
                return redirect()->route('customer.my-bookings')->withErrors(['cancel' => $e->getMessage()]);
            }
        }

        return redirect()->route('customer.my-bookings');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($validated);

        return redirect()->route('customer.my-bookings')->with('status', 'Profile information updated successfully.');
    }
}
