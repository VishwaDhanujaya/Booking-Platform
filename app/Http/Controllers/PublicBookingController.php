<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\TimeSlot;
use App\Models\Booking;
use App\Models\CustomerPass;
use App\Services\TenantResolver;
use App\Services\BookingEngineService;
use Carbon\Carbon;

class PublicBookingController extends Controller
{
    public function index(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();

        if ($tenant && !$tenant->is_active) {
            return response()->view('errors.tenant_suspended', compact('tenant'), 403);
        }

        $categories = SportCategory::withCount('courts')->get();

        $selectedSportSlug = (string) $request->query('sport', 'all');
        $courtTypeFilter = (string) $request->query('type', 'all');
        
        $selectedDateStr = (string) $request->query('date', Carbon::today()->toDateString());
        try {
            $selectedDate = Carbon::parse($selectedDateStr);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        $courtsQuery = Court::where('is_active', '=', true)->with('sportCategory');

        if ($selectedSportSlug !== 'all') {
            $courtsQuery->whereHas('sportCategory', function ($q) use ($selectedSportSlug) {
                $q->where('slug', '=', $selectedSportSlug);
            });
        }

        if ($courtTypeFilter !== 'all') {
            $courtsQuery->where('type', '=', $courtTypeFilter);
        }

        $courts = $courtsQuery->get();

        $selectedCourtId = $request->query('court_id');
        if ($selectedCourtId) {
            $selectedCourt = $courts->firstWhere('id', (int) $selectedCourtId) ?? $courts->first();
        } else {
            $selectedCourt = $courts->first();
        }

        $today = Carbon::today();
        $weekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $loopDate = $today->copy()->addDays($i);
            $loopDateStr = $loopDate->toDateString();

            if ($selectedCourt) {
                $availCount = TimeSlot::where('court_id', '=', $selectedCourt->id)
                    ->where('date', '=', $loopDateStr)
                    ->where('status', '=', 'available')
                    ->count();
            } else {
                $availCount = 0;
            }

            $weekDates[] = [
                'date_str' => $loopDateStr,
                'day_name' => $loopDate->format('D'),
                'day_number' => $loopDate->format('d'),
                'month_name' => $loopDate->format('M'),
                'is_today' => $loopDate->isToday(),
                'is_selected' => $loopDateStr === $selectedDate->toDateString(),
                'available_count' => $availCount,
            ];
        }

        $timeSlots = collect();
        if ($selectedCourt) {
            $timeSlots = TimeSlot::where('court_id', '=', $selectedCourt->id)
                ->where('date', '=', $selectedDate->toDateString())
                ->orderBy('start_time')
                ->get();

            // Auto-provision 16 hourly slots (6:00 AM - 10:00 PM) if none exist for court/date
            if ($timeSlots->isEmpty()) {
                for ($hour = 6; $hour < 22; $hour++) {
                    $start = sprintf('%02d:00', $hour);
                    $end = sprintf('%02d:00', $hour + 1);
                    $isPeak = ($hour >= 17);
                    $price = $isPeak ? ($selectedCourt->peak_hourly_rate ?? $selectedCourt->hourly_rate * 1.2) : $selectedCourt->hourly_rate;

                    TimeSlot::create([
                        'tenant_id' => $tenant->id,
                        'court_id' => $selectedCourt->id,
                        'date' => $selectedDate->toDateString(),
                        'start_time' => $start,
                        'end_time' => $end,
                        'status' => 'available',
                        'is_peak' => $isPeak,
                        'price' => $price,
                    ]);
                }

                $timeSlots = TimeSlot::where('court_id', '=', $selectedCourt->id)
                    ->where('date', '=', $selectedDate->toDateString())
                    ->orderBy('start_time')
                    ->get();
            }
        }

        $allCourtsTimeSlots = [];
        if ($courts->isNotEmpty()) {
            foreach ($courts as $court) {
                $slots = TimeSlot::where('court_id', '=', $court->id)
                    ->where('date', '=', $selectedDate->toDateString())
                    ->orderBy('start_time')
                    ->get();

                if ($slots->isEmpty()) {
                    for ($hour = 6; $hour < 22; $hour++) {
                        $start = sprintf('%02d:00', $hour);
                        $end = sprintf('%02d:00', $hour + 1);
                        $isPeak = ($hour >= 17);
                        $price = $isPeak ? ($court->peak_hourly_rate ?? $court->hourly_rate * 1.2) : $court->hourly_rate;

                        TimeSlot::create([
                            'tenant_id' => $tenant->id,
                            'court_id' => $court->id,
                            'date' => $selectedDate->toDateString(),
                            'start_time' => $start,
                            'end_time' => $end,
                            'status' => 'available',
                            'is_peak' => $isPeak,
                            'price' => $price,
                        ]);
                    }

                    $slots = TimeSlot::where('court_id', '=', $court->id)
                        ->where('date', '=', $selectedDate->toDateString())
                        ->orderBy('start_time')
                        ->get();
                }

                $allCourtsTimeSlots[$court->id] = $slots;
            }
        }

        return view('booking.index', compact(
            'tenant',
            'categories',
            'courts',
            'selectedCourt',
            'selectedSportSlug',
            'courtTypeFilter',
            'selectedDate',
            'weekDates',
            'timeSlots',
            'allCourtsTimeSlots'
        ));
    }

    public function checkout(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        
        $courtId = (int) $request->query('court_id', 1);
        $court = Court::with('sportCategory')->find($courtId) ?? Court::first();

        $slotIds = array_filter(explode(',', (string) $request->query('slots', '1,2')));
        $slots = TimeSlot::whereIn('id', $slotIds)->orderBy('start_time')->get();

        if ($slots->isEmpty()) {
            $date = Carbon::today()->addDay()->toDateString();
            $slots = TimeSlot::where('court_id', '=', $court->id)
                ->where('date', '=', $date)
                ->limit(2)
                ->get();
        }

        $bookingDate = $slots->first() ? $slots->first()->date : Carbon::today()->toDateString();
        $startTime = $slots->first() ? $slots->first()->start_time : '17:00';
        $endTime = $slots->last() ? $slots->last()->end_time : '19:00';

        $baseCourtPrice = $slots->sum('price');
        if ($baseCourtPrice <= 0) {
            $baseCourtPrice = $court ? $court->hourly_rate * 2 : 7000;
        }

        $activePass = Auth::check() 
            ? CustomerPass::where('user_id', Auth::id())->where('status', 'active')->first() 
            : null;

        return view('booking.checkout', compact(
            'tenant',
            'court',
            'slots',
            'bookingDate',
            'startTime',
            'endTime',
            'baseCourtPrice',
            'activePass'
        ));
    }

    public function process(Request $request, BookingEngineService $bookingEngine)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $courtId = (int) $request->input('court_id', 1);
        $court = Court::findOrFail($courtId);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please sign in to complete your court reservation.');
        }

        $date = $request->input('booking_date', Carbon::today()->toDateString());
        $startTime = $request->input('start_time', '17:00');
        $endTime = $request->input('end_time', '19:00');
        $paymentMethod = $request->input('payment_method', 'pay_at_venue');

        $addons = [];
        if ($request->has('addon_rackets')) {
            $addons[] = ['name' => 'Pro Racket Rental x2', 'price' => 1200];
        }
        if ($request->has('addon_balls')) {
            $addons[] = ['name' => 'Match Balls Tube (Babolat)', 'price' => 1500];
        }
        if ($request->has('addon_locker')) {
            $addons[] = ['name' => 'Locker & Towel Access', 'price' => 500];
        }
        if ($request->has('addon_lights')) {
            $addons[] = ['name' => 'Night LED Floodlights', 'price' => 1000];
        }

        try {
            $pass = null;
            if ($paymentMethod === 'pass') {
                $pass = CustomerPass::where('user_id', $user->id)->where('status', 'active')->first();
            }

            $booking = $bookingEngine->createBooking(
                $user,
                $court,
                $date,
                $startTime,
                $endTime,
                $addons,
                $paymentMethod,
                $pass
            );

            return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference]);
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['booking' => $e->getMessage()]);
        }
    }

    public function joinWaitlist(Request $request, BookingEngineService $bookingEngine)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please sign in to join court waitlists.');
        }

        $request->validate([
            'court_id' => ['required', 'integer'],
            'booking_date' => ['required', 'date'],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string'],
        ]);

        $court = Court::findOrFail($request->court_id);

        try {
            $waitlist = $bookingEngine->joinWaitlist(
                $user,
                $court,
                $request->booking_date,
                $request->start_time,
                $request->end_time
            );

            return back()->with('status', "Joined waitlist successfully! Position #{$waitlist->position} in line.");
        } catch (\Exception $e) {
            return back()->withErrors(['waitlist' => $e->getMessage()]);
        }
    }

    public function confirmation(Request $request, string $reference)
    {
        $tenant = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $booking = Booking::where('booking_reference', '=', $reference)->with('court.sportCategory')->first();

        if (!$booking) {
            return redirect()->route('booking.index');
        }

        return view('booking.confirmation', compact('tenant', 'booking'));
    }

    public function pricing(Request $request)
    {
        $tenantModel = TenantResolver::getActiveTenantModel() ?? Tenant::first();
        $tenant = TenantResolver::getActiveTenant();

        $courts = Court::where('is_active', true)->with('sportCategory')->get();
        $categories = SportCategory::withCount('courts')->get();

        $pricingRules = \App\Models\PricingRule::where('tenant_id', $tenantModel->id)
                            ->where('is_active', true)
                            ->get();

        return view('booking.pricing', compact('tenantModel', 'tenant', 'courts', 'categories', 'pricingRules'));
    }
}
