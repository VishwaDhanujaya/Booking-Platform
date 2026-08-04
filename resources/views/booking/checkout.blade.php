<x-layouts.app title="Checkout & Booking Confirmation | {{ $tenant->name }}">

    <!-- Page Banner (VADEL Liquid Glass Aesthetic - Flush Top Alignment) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden py-10 sm:py-12 p-6 sm:p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white">
            <div class="absolute inset-0 bg-linear-to-r from-slate-950 via-slate-900/90 to-slate-950 z-0"></div>
            <div class="relative z-10 max-w-5xl mx-auto w-full">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <span class="text-xs font-black text-slate-300 uppercase tracking-widest">Step-by-Step Checkout</span>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg">Complete Your Court Booking</h1>
                    </div>

                    <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-slate-950 bg-white hover:bg-slate-100 px-5 py-2.5 rounded-full shadow-md transition-all hover:scale-105">
                        &larr; Back to Calendar
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- Main Checkout Section with Alpine.js Wizard & Pricing Engine -->
    <section x-data="{
        currentStep: 1,
        baseRate: {{ $pricing['base_rate'] ?? 3500 }},
        subtotal: {{ $pricing['subtotal_before_discounts'] ?? 3500 }},
        discountAmount: {{ $pricing['discount_amount'] ?? 0 }},
        racketRental: false,
        matchBalls: false,
        lockerAccess: false,
        floodlights: false,
        paymentMethod: 'pay_at_venue',
        isSubmitting: false,

        get addonsTotal() {
            let sum = 0;
            if (this.racketRental) sum += 1200;
            if (this.matchBalls) sum += 1500;
            if (this.lockerAccess) sum += 500;
            if (this.floodlights) sum += 1000;
            return sum;
        },

        get grandTotal() {
            return Math.max(0, this.subtotal - this.discountAmount + this.addonsTotal);
        },

        nextStep() {
            if (this.currentStep < 3) this.currentStep++;
        },
        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        }
    }" class="py-12 bg-slate-50 min-h-screen">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Wizard Step Progress Bar -->
            <div class="mb-8 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between max-w-2xl mx-auto relative">
                    <!-- Progress Line -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 z-0"></div>
                    <div class="absolute top-1/2 left-0 h-1 bg-brand-600 -translate-y-1/2 z-0 transition-all duration-300"
                         :style="'width: ' + ((currentStep - 1) / 2 * 100) + '%'"></div>

                    <!-- Step 1 -->
                    <div class="relative z-10 flex flex-col items-center gap-1 cursor-pointer" @click="currentStep = 1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all"
                             :class="currentStep >= 1 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-slate-100 text-slate-400'">
                            1
                        </div>
                        <span class="text-[11px] font-bold" :class="currentStep >= 1 ? 'text-slate-900' : 'text-slate-400'">Review & Add-ons</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative z-10 flex flex-col items-center gap-1 cursor-pointer" @click="currentStep = 2">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all"
                             :class="currentStep >= 2 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-slate-100 text-slate-400'">
                            2
                        </div>
                        <span class="text-[11px] font-bold" :class="currentStep >= 2 ? 'text-slate-900' : 'text-slate-400'">Player Details</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative z-10 flex flex-col items-center gap-1 cursor-pointer" @click="currentStep = 3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all"
                             :class="currentStep >= 3 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'bg-slate-100 text-slate-400'">
                            3
                        </div>
                        <span class="text-[11px] font-bold" :class="currentStep >= 3 ? 'text-slate-900' : 'text-slate-400'">Payment & Confirm</span>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs space-y-1">
                    <strong class="font-bold block text-rose-800">Booking Processing Error:</strong>
                    @foreach ($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Form & Summary Layout -->
            <form action="{{ route('booking.process') }}" method="POST" @submit="isSubmitting = true">
                @csrf

                <input type="hidden" name="court_id" value="{{ $court->id }}">
                <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
                <input type="hidden" name="start_time" value="{{ $startTime }}">
                <input type="hidden" name="end_time" value="{{ $endTime }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Main Step Content Column (Left - 7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        
                        <!-- STEP 1: BOOKING SUMMARY & ADD-ONS -->
                        <div x-show="currentStep === 1" class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 1 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Review Reservation & Optional Add-ons</h2>
                            </div>

                            <!-- Selected Court Brief Card -->
                            <div class="bg-slate-900 text-white rounded-xl p-5 border border-slate-800 flex items-center gap-4">
                                <div class="w-14 h-14 rounded-lg bg-brand-600 flex items-center justify-center text-white font-black text-xl shrink-0">
                                    {{ strtoupper(substr($court->sportCategory->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-400">{{ $court->sportCategory->name }}</span>
                                    <h3 class="text-base font-bold text-white">{{ $court->name }}</h3>
                                    <p class="text-xs text-slate-300 mt-1 flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1">
                                            <strong>{{ \Carbon\Carbon::parse($bookingDate)->format('D, M j, Y') }}</strong>
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <strong>{{ substr($startTime, 0, 5) }} – {{ substr($endTime, 0, 5) }}</strong>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Add-ons Selection -->
                            <div class="space-y-4 pt-2">
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Enhance Your Session (Optional Add-ons)</h3>

                                <div class="space-y-3">
                                    
                                    <!-- Add-on 1: Racket Rental -->
                                    <label class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                           :class="racketRental ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_rackets" value="1" x-model="racketRental" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Pro Racket Rental (x2)</span>
                                                <span class="text-[11px] text-slate-500">Includes 2 professional grade match rackets for your session.</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 1,200</strong>
                                    </label>

                                    <!-- Add-on 2: Match Balls Tube -->
                                    <label class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                           :class="matchBalls ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_balls" value="1" x-model="matchBalls" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Match Balls Can (Babolat / Head)</span>
                                                <span class="text-[11px] text-slate-500">Sealed 3-ball pressurized canister (Yours to keep).</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 1,500</strong>
                                    </label>

                                    <!-- Add-on 3: Locker Access -->
                                    <label class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                           :class="lockerAccess ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_locker" value="1" x-model="lockerAccess" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Locker & Fresh Towel Service</span>
                                                <span class="text-[11px] text-slate-500">Secure electronic keycard locker & fresh microfiber towel.</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 500</strong>
                                    </label>

                                    <!-- Add-on 4: Floodlights -->
                                    <label class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                           :class="floodlights ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_lights" value="1" x-model="floodlights" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Night LED Arena Lighting</span>
                                                <span class="text-[11px] text-slate-500">Automated 1000-Lux LED match light towers for night play.</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 1,000</strong>
                                    </label>

                                </div>
                            </div>

                            <!-- Step 1 Actions -->
                            <div class="pt-4 border-t border-slate-100 flex justify-end">
                                <button type="button" @click="nextStep()" class="px-6 py-3 rounded-xl font-extrabold text-white bg-brand-600 hover:bg-brand-700 shadow-md transition-all text-xs flex items-center gap-2">
                                    Continue to Player Details &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2: PLAYER INFORMATION -->
                        <div x-show="currentStep === 2" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 2 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Player & Contact Information</h2>
                            </div>

                            @auth
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1">
                                    <span class="font-bold text-slate-900 block">Logged in as {{ auth()->user()->name }}</span>
                                    <p class="text-slate-600">{{ auth()->user()->email }} &bull; {{ auth()->user()->phone ?? 'No phone saved' }}</p>
                                </div>
                            @else
                                <div class="space-y-4 text-xs">
                                    <div>
                                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                                        <input type="text" name="customer_name" required value="Kavinda Perera" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-medium">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                                            <input type="email" name="customer_email" required value="kavinda@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-medium">
                                        </div>
                                        <div>
                                            <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone</label>
                                            <input type="tel" name="customer_phone" value="+94 77 123 4567" class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm font-medium">
                                        </div>
                                    </div>
                                </div>
                            @endauth

                            <!-- Step 2 Actions -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" @click="prevStep()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-slate-900 text-xs">
                                    &larr; Back to Add-ons
                                </button>
                                <button type="button" @click="nextStep()" class="px-6 py-3 rounded-xl font-extrabold text-white bg-brand-600 hover:bg-brand-700 shadow-md transition-all text-xs flex items-center gap-2">
                                    Proceed to Payment &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3: PAYMENT METHOD & CONFIRMATION -->
                        <div x-show="currentStep === 3" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 3 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Payment Method & Confirmation</h2>
                            </div>

                            <!-- Payment Options -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                
                                <label class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between space-y-2 transition-all"
                                       :class="paymentMethod === 'pay_at_venue' ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                    <div class="flex items-center justify-between">
                                        <input type="radio" name="payment_method" value="pay_at_venue" x-model="paymentMethod" class="w-4 h-4 text-brand-600">
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">Offline</span>
                                    </div>
                                    <div>
                                        <strong class="block text-xs text-slate-900 font-bold">Pay at Venue</strong>
                                        <span class="text-[10px] text-slate-500">Pay cash or card at front desk upon arrival.</span>
                                    </div>
                                </label>

                                <label class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between space-y-2 transition-all"
                                       :class="paymentMethod === 'credits' ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                    <div class="flex items-center justify-between">
                                        <input type="radio" name="payment_method" value="credits" x-model="paymentMethod" class="w-4 h-4 text-brand-600">
                                        <span class="text-[10px] font-bold text-brand-700 bg-brand-100 px-2 py-0.5 rounded-md">Wallet</span>
                                    </div>
                                    <div>
                                        <strong class="block text-xs text-slate-900 font-bold">Wallet Credits</strong>
                                        <span class="text-[10px] text-slate-500">Deduct from your stored member credit balance.</span>
                                    </div>
                                </label>

                                <label class="p-4 rounded-xl border cursor-pointer flex flex-col justify-between space-y-2 transition-all"
                                       :class="paymentMethod === 'bank_transfer' ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                    <div class="flex items-center justify-between">
                                        <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="w-4 h-4 text-brand-600">
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded-md">Manual</span>
                                    </div>
                                    <div>
                                        <strong class="block text-xs text-slate-900 font-bold">Bank Transfer</strong>
                                        <span class="text-[10px] text-slate-500">Upload bank slip reference after booking.</span>
                                    </div>
                                </label>

                            </div>

                            @if($activePass)
                                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-between text-xs">
                                    <div>
                                        <strong class="text-amber-900 font-bold block">{{ $activePass->pass_name }} Active</strong>
                                        <span class="text-amber-700 text-[11px]">{{ $activePass->remaining_units }} session units remaining</span>
                                    </div>
                                    <label class="flex items-center gap-2 font-bold text-amber-900 cursor-pointer">
                                        <input type="radio" name="payment_method" value="pass" x-model="paymentMethod" class="w-4 h-4 text-amber-600">
                                        Redeem 1 Pass Unit
                                    </label>
                                </div>
                            @endif

                            <!-- Step 3 Submit CTA -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" @click="prevStep()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-slate-900 text-xs">
                                    &larr; Back to Player Details
                                </button>
                                <button type="submit" class="px-8 py-4 rounded-xl font-extrabold text-white bg-brand-600 hover:bg-brand-700 shadow-xl transition-all text-sm flex items-center gap-2">
                                    <span x-show="!isSubmitting">Confirm Reservation &rarr;</span>
                                    <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Processing Booking...
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Live Order Summary Card (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-card space-y-5 sticky top-6">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3">Calculated Price Breakdown</h3>

                            <!-- Itemized Line Items from PricingEngineService -->
                            <div class="space-y-2.5 text-xs">
                                @foreach($pricing['line_items'] ?? [] as $item)
                                    <div class="flex justify-between items-center text-slate-700 text-[11px] {{ $item['type'] === 'discount' ? 'text-emerald-700 font-bold' : '' }} {{ $item['type'] === 'peak_surcharge' ? 'text-amber-800 font-bold' : '' }}">
                                        <span>&bull; {{ $item['description'] }}</span>
                                        <span>LKR {{ number_format($item['amount'], 2) }}</span>
                                    </div>
                                @endforeach

                                <!-- Dynamic Add-ons Itemization in UI -->
                                <div x-show="racketRental" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Pro Racket Rental x2</span>
                                    <span>+ LKR 1,200.00</span>
                                </div>
                                <div x-show="matchBalls" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Match Balls Can</span>
                                    <span>+ LKR 1,500.00</span>
                                </div>
                                <div x-show="lockerAccess" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Locker Access</span>
                                    <span>+ LKR 500.00</span>
                                </div>
                                <div x-show="floodlights" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; LED Arena Lights</span>
                                    <span>+ LKR 1,000.00</span>
                                </div>
                            </div>

                            <!-- Grand Total Box -->
                            <div class="bg-slate-900 text-white p-5 rounded-xl flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Final Total Due</span>
                                    <span class="text-[11px] text-emerald-400 font-semibold">Taxes & Fees Included</span>
                                </div>
                                <span class="text-2xl font-black text-white">LKR <span x-text="grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2})"></span></span>
                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </section>

</x-layouts.app>
