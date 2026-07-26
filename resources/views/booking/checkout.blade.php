<x-layouts.app title="Checkout & Booking Confirmation | Colombo Courts Club">

    <!-- Page Banner -->
    <section class="bg-slate-900 text-white py-8 border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-brand-400 uppercase tracking-wider">Step-by-Step Checkout</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Complete Your Court Booking</h1>
                </div>

                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors">
                    &larr; Back to Availability Calendar
                </a>
            </div>
        </div>
    </section>

    <!-- Main Checkout Section with 4-Step Alpine.js Wizard -->
    <section x-data="{
        currentStep: 1,
        basePrice: {{ $baseCourtPrice }},
        racketRental: false,
        matchBalls: false,
        lockerAccess: false,
        floodlights: true,
        paymentMethod: 'card',
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
            return this.basePrice + this.addonsTotal;
        },

        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep++;
                window.scrollTo({ top: 150, behavior: 'smooth' });
            }
        },

        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 150, behavior: 'smooth' });
            }
        }
    }" class="py-10 bg-slate-50 min-h-screen">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- 5. Visible 4-Step Progress Indicator -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-card">
                <div class="relative">
                    <!-- Progress Track Line -->
                    <div class="absolute top-4 left-8 right-8 h-1 bg-slate-200 z-0 rounded-full"></div>
                    <div class="absolute top-4 left-8 h-1 bg-brand-600 z-0 rounded-full transition-all duration-300"
                         :style="'width: ' + ((currentStep - 1) * 33.33) + '%'"></div>

                    <div class="grid grid-cols-4 gap-2 sm:gap-4 relative z-10">
                        
                        <!-- Step 1 Indicator -->
                        <div class="flex flex-col items-center text-center space-y-2 cursor-pointer" @click="currentStep = 1">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all ring-4 ring-white"
                                 :class="currentStep >= 1 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                1
                            </div>
                            <span class="text-[11px] font-bold transition-colors hidden sm:block"
                                  :class="currentStep === 1 ? 'text-brand-600 font-extrabold' : 'text-slate-500'">
                                Booking & Add-ons
                            </span>
                        </div>

                        <!-- Step 2 Indicator -->
                        <div class="flex flex-col items-center text-center space-y-2 cursor-pointer" @click="currentStep = 2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all ring-4 ring-white"
                                 :class="currentStep >= 2 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                2
                            </div>
                            <span class="text-[11px] font-bold transition-colors hidden sm:block"
                                  :class="currentStep === 2 ? 'text-brand-600 font-extrabold' : 'text-slate-500'">
                                Player Information
                            </span>
                        </div>

                        <!-- Step 3 Indicator -->
                        <div class="flex flex-col items-center text-center space-y-2 cursor-pointer" @click="currentStep = 3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-all ring-4 ring-white"
                                 :class="currentStep >= 3 ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                                3
                            </div>
                            <span class="text-[11px] font-bold transition-colors hidden sm:block"
                                  :class="currentStep === 3 ? 'text-brand-600 font-extrabold' : 'text-slate-500'">
                                Payment & Checkout
                            </span>
                        </div>

                        <!-- Step 4 Indicator (Confirmation) -->
                        <div class="flex flex-col items-center text-center space-y-2 opacity-60">
                            <div class="w-9 h-9 rounded-full bg-slate-100 text-slate-400 border border-slate-200 flex items-center justify-center font-bold text-xs ring-4 ring-white">
                                4
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 hidden sm:block">
                                Confirmation
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Form & Summary Layout -->
            <form action="{{ route('booking.process') }}" method="POST" @submit="isSubmitting = true">
                @csrf

                <input type="hidden" name="court_id" value="{{ $court->id }}">
                <input type="hidden" name="booking_date" value="{{ $bookingDate }}">
                <input type="hidden" name="start_time" value="{{ $startTime }}">
                <input type="hidden" name="end_time" value="{{ $endTime }}">
                <input type="hidden" name="base_price" :value="basePrice">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Main Step Content Column (Left - 7 cols) -->
                    <div class="lg:col-span-7 space-y-6">
                        
                        <!-- STEP 1: BOOKING SUMMARY & ADD-ONS -->
                        <div x-show="currentStep === 1" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 1 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Review Reservation & Optional Add-ons</h2>
                            </div>

                            <!-- Selected Court Brief Card -->
                            <div class="bg-slate-900 text-white rounded-xl p-5 border border-slate-800 flex items-center gap-4">
                                <img src="{{ $court->image_url }}" class="w-16 h-16 rounded-lg object-cover border border-slate-700" alt="" />
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-400">{{ $court->sportCategory->name }}</span>
                                    <h3 class="text-base font-bold text-white">{{ $court->name }}</h3>
                                    <p class="text-xs text-slate-300 mt-1 flex items-center gap-3">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <strong>{{ \Carbon\Carbon::parse($bookingDate)->format('D, M j, Y') }}</strong>
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 7v5l3 3"/></svg>
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
                                                <span class="block text-xs font-bold text-slate-900">High-Performance Racket Rental (x2)</span>
                                                <span class="text-[11px] text-slate-500">Includes 2 professional grade Wilson/Babolat rackets for your match.</span>
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
                                                <span class="block text-xs font-bold text-slate-900">New Match Ball Can (3 Balls)</span>
                                                <span class="text-[11px] text-slate-500">Unopened pressurized tournament balls (yours to keep).</span>
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
                                                <span class="block text-xs font-bold text-slate-900">Locker Room Key & Fresh Towel</span>
                                                <span class="text-[11px] text-slate-500">Private keycard locker access and fresh luxury sports towels.</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 500</strong>
                                    </label>

                                    <!-- Add-on 4: Floodlights (Checked) -->
                                    <label class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                           :class="floodlights ? 'bg-brand-50 border-brand-500 shadow-xs' : 'bg-slate-50 hover:bg-slate-100 border-slate-200'">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="addon_lights" value="1" x-model="floodlights" class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                                            <div>
                                                <span class="block text-xs font-bold text-slate-900">Evening LED Arena Floodlights</span>
                                                <span class="text-[11px] text-slate-500">High-lumen shadowless lighting for evening sessions.</span>
                                            </div>
                                        </div>
                                        <strong class="text-xs font-bold text-slate-900">+ LKR 1,000</strong>
                                    </label>

                                </div>
                            </div>

                            <!-- Step 1 CTA -->
                            <div class="pt-4 border-t border-slate-100 flex justify-end">
                                <button type="button" @click="nextStep()" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md transition-all text-sm flex items-center gap-2">
                                    Next: Player Information &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- STEP 2: PLAYER INFORMATION FORM -->
                        <div x-show="currentStep === 2" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 2 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Player Details & Contact Info</h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name *</label>
                                    <input type="text" name="customer_name" value="Kavinda Perera" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold text-slate-900">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address *</label>
                                    <input type="email" name="customer_email" value="kavinda@example.com" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phone / WhatsApp *</label>
                                    <input type="tel" name="customer_phone" value="+94 77 123 4567" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Invite Partner / Opponent (Optional)</label>
                                    <input type="email" name="partner_email" placeholder="partner@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900">
                                    <span class="text-[11px] text-slate-500 mt-1 block">We'll send them a calendar invite and QR gate pass for doubles match.</span>
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Special Requests / Notes</label>
                                    <textarea name="notes" rows="2" placeholder="e.g., Please ensure net height is set to tournament spec..." class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900"></textarea>
                                </div>
                            </div>

                            <!-- Step 2 Navigation -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" @click="prevStep()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-slate-900 text-xs">
                                    &larr; Back to Add-ons
                                </button>
                                <button type="button" @click="nextStep()" class="px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md transition-all text-sm flex items-center gap-2">
                                    Next: Payment Options &rarr;
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3: MOCKED PAYMENT UI -->
                        <div x-show="currentStep === 3" x-cloak class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-card space-y-6">
                            <div>
                                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Step 3 of 3</span>
                                <h2 class="text-xl font-extrabold text-slate-900 mt-1">Payment Method & Final Confirmation</h2>
                                <p class="text-xs text-slate-500 mt-1">This is a visual mockup demo. No real funds will be charged.</p>
                            </div>

                            <!-- Payment Method Tabs -->
                            <div class="grid grid-cols-3 gap-3 p-1 rounded-xl bg-slate-100 border border-slate-200">
                                <button type="button" @click="paymentMethod = 'card'"
                                        :class="paymentMethod === 'card' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                        class="py-2.5 rounded-lg text-xs transition-all text-center flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    Credit / Debit Card
                                </button>
                                <button type="button" @click="paymentMethod = 'bank'"
                                        :class="paymentMethod === 'bank' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                        class="py-2.5 rounded-lg text-xs transition-all text-center flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V10M5 21V10M9 21V10M13 21V10M3 10l9-7 9 7M4 21h16"/></svg>
                                    Bank Transfer
                                </button>
                                <button type="button" @click="paymentMethod = 'wallet'"
                                        :class="paymentMethod === 'wallet' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                                        class="py-2.5 rounded-lg text-xs transition-all text-center flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="7" y="3" width="10" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M11 18h2"/></svg>
                                    SLT Pay / mCash
                                </button>
                            </div>

                            <!-- Option A: Card Form -->
                            <div x-show="paymentMethod === 'card'" class="space-y-4 p-5 rounded-xl bg-slate-50 border border-slate-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Card Details (Mocked)</span>
                                    <div class="flex items-center gap-1.5 text-slate-400 font-bold text-xs">
                                        <span>VISA</span> &bull; <span>MasterCard</span> &bull; <span>AMEX</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Card Number</label>
                                    <input type="text" value="4532 •••• •••• 8910" readonly class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-sm font-mono font-bold text-slate-800">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Expiry Date</label>
                                        <input type="text" value="11 / 28" readonly class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-sm font-mono text-slate-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">CVC Code</label>
                                        <input type="text" value="382" readonly class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-sm font-mono text-slate-800">
                                    </div>
                                </div>
                            </div>

                            <!-- Option B: Bank Transfer -->
                            <div x-show="paymentMethod === 'bank'" x-cloak class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Commercial Bank Account Details</span>
                                <div class="bg-white p-4 rounded-lg border border-slate-200 text-xs space-y-1 text-slate-700 font-mono">
                                    <p><strong>Account Name:</strong> Colombo Courts Club (Pvt) Ltd</p>
                                    <p><strong>Account Number:</strong> 1000 8492 3109</p>
                                    <p><strong>Bank & Branch:</strong> Commercial Bank &bull; Maitland Branch</p>
                                </div>
                            </div>

                            <!-- Option C: Digital Wallet -->
                            <div x-show="paymentMethod === 'wallet'" x-cloak class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Instant Mobile Wallet Checkout</span>
                                <p class="text-xs text-slate-600">Scan QR or enter mobile number to receive payment authorization request on SLT Pay / mCash.</p>
                            </div>

                            <!-- Step 3 Submit CTA -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <button type="button" @click="prevStep()" class="px-5 py-2.5 rounded-xl font-semibold text-slate-600 hover:text-slate-900 text-xs">
                                    &larr; Back to Player Details
                                </button>
                                <button type="submit" class="px-8 py-4 rounded-xl font-extrabold text-slate-950 bg-accent-400 hover:bg-accent-300 shadow-xl transition-all hover:scale-[1.02] text-sm flex items-center gap-2">
                                    <span x-show="!isSubmitting">Confirm & Pay LKR <span x-text="grandTotal.toLocaleString()"></span> &rarr;</span>
                                    <span x-show="isSubmitting" x-cloak class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        Processing Order...
                                    </span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Live Order Summary Card (5 cols) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <div class="bg-white rounded-2xl p-6 border border-slate-200/90 shadow-card space-y-5 sticky top-6">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-3">Booking Order Summary</h3>

                            <!-- Itemized Breakdown -->
                            <div class="space-y-3 text-xs">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <strong class="text-slate-900 block font-bold">{{ $court->name }}</strong>
                                        <span class="text-slate-500 text-[11px]">{{ $bookingDate }} ({{ substr($startTime, 0, 5) }} - {{ substr($endTime, 0, 5) }})</span>
                                    </div>
                                    <span class="font-bold text-slate-900">LKR {{ number_format($baseCourtPrice) }}</span>
                                </div>

                                <!-- Add-ons Itemization -->
                                <div x-show="racketRental" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Pro Racket Rental x2</span>
                                    <span>LKR 1,200</span>
                                </div>

                                <div x-show="matchBalls" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Babolat Match Ball Can</span>
                                    <span>LKR 1,500</span>
                                </div>

                                <div x-show="lockerAccess" x-cloak class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; Locker Room & Towel</span>
                                    <span>LKR 500</span>
                                </div>

                                <div x-show="floodlights" class="flex justify-between items-center text-slate-600 text-[11px]">
                                    <span>&bull; LED Arena Floodlights</span>
                                    <span>LKR 1,000</span>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-slate-500 text-[11px]">
                                    <span>Taxes & Service Charge</span>
                                    <span class="text-emerald-600 font-bold">Included</span>
                                </div>
                            </div>

                            <!-- Grand Total Box -->
                            <div class="bg-slate-900 text-white p-4 rounded-xl flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Due</span>
                                    <span class="text-xs text-brand-300 font-semibold">Instant Gate Pass Included</span>
                                </div>
                                <span class="text-2xl font-black text-accent-400">LKR <span x-text="grandTotal.toLocaleString()"></span></span>
                            </div>

                            <!-- Guarantee Badges -->
                            <div class="pt-2 flex items-center justify-center gap-4 text-[10px] font-semibold text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    256-Bit SSL Encrypted
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Instant Pass Issue
                                </span>
                            </div>
                        </div>

                    </div>

                </div>

            </form>

        </div>

    </section>

</x-layouts.app>
