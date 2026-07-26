<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SportCategory;
use App\Models\Court;
use App\Models\TimeSlot;
use App\Models\Booking;
use Carbon\Carbon;

class PublicBookingController extends Controller
{
    public function index(Request $request)
    {
        $tenant = Tenant::first(['*']);
        if (!$tenant) {
            $tenant = new Tenant(['id' => 1, 'name' => 'Colombo Courts Club', 'slug' => 'colombo-courts-club']);
            $tenant->id = 1;
        }

        $tenantId = (int) $tenant->id;

        $categories = SportCategory::where('tenant_id', '=', $tenantId, 'and')->withCount('courts')->get();

        $selectedSportSlug = (string) $request->query('sport', 'all');
        $courtTypeFilter = (string) $request->query('type', 'all');
        
        $selectedDateStr = (string) $request->query('date', Carbon::today()->toDateString());
        try {
            $selectedDate = Carbon::parse($selectedDateStr);
        } catch (\Exception $e) {
            $selectedDate = Carbon::today();
        }

        $courtsQuery = Court::where('tenant_id', '=', $tenantId, 'and')
            ->where('is_active', '=', true, 'and')
            ->with('sportCategory');

        if ($selectedSportSlug !== 'all') {
            $courtsQuery->whereHas('sportCategory', function ($q) use ($selectedSportSlug) {
                $q->where('slug', '=', $selectedSportSlug, 'and');
            });
        }

        if ($courtTypeFilter !== 'all') {
            $courtsQuery->where('type', '=', $courtTypeFilter, 'and');
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
                $availCount = TimeSlot::where('court_id', '=', $selectedCourt->id, 'and')
                    ->where('date', '=', $loopDateStr, 'and')
                    ->where('status', '=', 'available', 'and')
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
            $timeSlots = TimeSlot::where('court_id', '=', $selectedCourt->id, 'and')
                ->where('date', '=', $selectedDate->toDateString(), 'and')
                ->orderBy('start_time')
                ->get();
        }

        $allCourtsTimeSlots = [];
        if ($courts->isNotEmpty()) {
            foreach ($courts as $court) {
                $allCourtsTimeSlots[$court->id] = TimeSlot::where('court_id', '=', $court->id, 'and')
                    ->where('date', '=', $selectedDate->toDateString(), 'and')
                    ->orderBy('start_time')
                    ->get();
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
        $tenant = Tenant::first(['*']);
        
        $courtId = (int) $request->query('court_id', 1);
        $court = Court::with('sportCategory')->find($courtId, ['*']) ?? Court::first(['*']);

        $slotIds = array_filter(explode(',', (string) $request->query('slots', '1,2')));
        $slots = TimeSlot::whereIn('id', $slotIds, 'and', false)->orderBy('start_time')->get();

        if ($slots->isEmpty()) {
            $date = Carbon::today()->addDay()->toDateString();
            $slots = TimeSlot::where('court_id', '=', $court->id, 'and')
                ->where('date', '=', $date, 'and')
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

        return view('booking.checkout', compact(
            'tenant',
            'court',
            'slots',
            'bookingDate',
            'startTime',
            'endTime',
            'baseCourtPrice'
        ));
    }

    public function process(Request $request)
    {
        $tenant = Tenant::first(['*']);
        $courtId = (int) $request->input('court_id', 1);
        $court = Court::find($courtId, ['*']) ?? Court::first(['*']);

        $refNumber = 'CCC-2026-' . rand(1000, 9999);

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

        $addonsTotal = array_sum(array_column($addons, 'price'));
        $basePrice = floatval($request->input('base_price', 7000));
        $totalAmount = $basePrice + $addonsTotal;

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_reference' => $refNumber,
            'booking_date' => $request->input('booking_date', Carbon::today()->toDateString()),
            'start_time' => $request->input('start_time', '17:00'),
            'end_time' => $request->input('end_time', '19:00'),
            'customer_name' => $request->input('customer_name', 'Kavinda Perera'),
            'customer_email' => $request->input('customer_email', 'kavinda@example.com'),
            'customer_phone' => $request->input('customer_phone', '+94 77 123 4567'),
            'total_amount' => $totalAmount,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'addons' => $addons,
        ]);

        return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference]);
    }

    public function confirmation(Request $request, string $reference)
    {
        $tenant = Tenant::first(['*']);
        $booking = Booking::where('booking_reference', '=', $reference, 'and')->with('court.sportCategory')->first();

        if (!$booking) {
            $booking = Booking::with('court.sportCategory')->first() ?? new Booking([
                'booking_reference' => $reference,
                'booking_date' => Carbon::today()->toDateString(),
                'start_time' => '17:00',
                'end_time' => '19:00',
                'customer_name' => 'Kavinda Perera',
                'customer_email' => 'kavinda@example.com',
                'customer_phone' => '+94 77 123 4567',
                'total_amount' => 9700.00,
                'status' => 'confirmed',
                'payment_status' => 'paid',
            ]);
        }

        return view('booking.confirmation', compact('tenant', 'booking'));
    }
}
