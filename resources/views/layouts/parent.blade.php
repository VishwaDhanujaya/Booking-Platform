<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-sltds-endeavour selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BookFlow | Universal Court & Facility Management System' }}</title>
    <meta name="description" content="Simple, modern online court booking, member pass, and venue management software for sports clubs, gyms, studios, and facilities.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Single Main Navigation Header for Entire Website (VADEL Inspired Aesthetic) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200/50 shadow-xs transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo (VADEL Style Display Brand Text) -->
                <a href="{{ route('parent.home') }}" class="flex items-center gap-3 group">
                    <img src="/images/logo.png" alt="BookFlow Logo" class="h-9 w-auto object-contain group-hover:scale-[1.03] transition-all duration-300">
                    <span class="text-xl font-black italic tracking-tighter text-slate-950 uppercase group-hover:text-sltds-endeavour transition-colors">
                        BOOKFLOW
                    </span>
                </a>

                <!-- Desktop Navigation Links (VADEL Minimalist Text Style) -->
                <nav class="hidden lg:flex items-center gap-8 text-xs font-bold text-slate-700 tracking-wide">
                    <a href="{{ route('parent.home') }}" class="transition-colors duration-200 {{ request()->routeIs('home', 'parent.home') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Home
                    </a>
                    <a href="{{ route('parent.features') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.features') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Features
                    </a>
                    <a href="{{ route('parent.where-to-use') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.where-to-use') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Where to Use
                    </a>
                    <a href="{{ route('parent.customers') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.customers') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Our Customers
                    </a>
                    <a href="{{ route('parent.pricing') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.pricing') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Pricing
                    </a>
                    <a href="{{ route('parent.contact') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.contact') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Contact
                    </a>
                </nav>

                <!-- Right Action CTAs (VADEL Styled Pill Buttons) -->
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('superadmin.login') }}" class="inline-flex text-xs font-bold text-slate-600 hover:text-slate-950 px-3 py-2 transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="{{ route('tenant.register') }}" class="hidden sm:inline-flex items-center text-xs font-black text-white bg-slate-950 hover:bg-slate-800 px-5 py-2.5 rounded-full shadow-md transition-all duration-200 active:scale-[0.98]">
                            Register Venue
                        </a>
                        <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-black text-slate-950 bg-slate-100 hover:bg-slate-200 border border-slate-250 transition-all duration-200 active:scale-[0.98]">
                            Book a Demo
                        </a>
                    @else
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ \Illuminate\Support\Facades\Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/platform-admin') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-slate-950 hover:bg-slate-800 px-5 py-2.5 rounded-full shadow-md transition-all">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Platform Admin
                            </a>
                        @elseif(in_array(Auth::user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk']))
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-black text-white bg-slate-950 hover:bg-slate-800 px-5 py-2.5 rounded-full shadow-md transition-all">
                                Admin Portal
                            </a>
                        @else
                            <a href="{{ route('customer.my-bookings') }}" class="inline-flex items-center text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-full transition-all">
                                My Bookings
                            </a>
                        @endif
                    @endguest
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="grow">
        {{ $slot }}
    </main>

    <!-- Footer with SLTDS Corporate Identity -->
    <footer class="bg-slate-950 text-slate-400 py-14 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <!-- Brand Overview -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center">
                        <img src="/images/logo.png" alt="BookFlow Logo" class="h-10 w-auto object-contain">
                    </div>
                    <p class="text-xs leading-relaxed text-slate-400 max-w-sm">
                        Online booking, scheduling, and space management software designed for sports complexes, gyms, studios, and childcare providers.
                    </p>
                    <div class="pt-2 text-xs text-slate-500">
                        &copy; {{ date('Y') }} BookFlow by SLT Digital Services (Pvt) Ltd. All rights reserved.
                    </div>
                </div>

                <!-- Column 2: Product -->
                <div class="space-y-3 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-[11px]">Product Features</div>
                    <ul class="space-y-2">
                        <li><a href="{{ route('parent.features') }}" class="hover:text-sltds-sky transition-colors">Multi-Court Day Grid</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-sltds-sky transition-colors">Capacity & Waitlists</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-sltds-sky transition-colors">Peak Hour Pricing Rules</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-sltds-sky transition-colors">Member Credits & Passes</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-sltds-sky transition-colors">Tax Invoicing</a></li>
                    </ul>
                </div>

                <!-- Column 3: Verticals -->
                <div class="space-y-3 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-[11px]">Solutions</div>
                    <ul class="space-y-2">
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-sltds-sky transition-colors">Badminton & Tennis Courts</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-sltds-sky transition-colors">Padel & Squash Arenas</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-sltds-sky transition-colors">Group Fitness Studios</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-sltds-sky transition-colors">Gym Equipment Bays</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-sltds-sky transition-colors">Childcare & Creche</a></li>
                    </ul>
                </div>

                <!-- Column 4: Links -->
                <div class="space-y-3 text-xs">
                    <div class="font-bold text-white uppercase tracking-wider text-[11px]">Get Started</div>
                    <ul class="space-y-2">
                        <li><a href="{{ route('parent.customers') }}" class="hover:text-sltds-sky transition-colors">Live Customer Sites</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-sltds-sky transition-colors">Pricing Plans</a></li>
                        <li><a href="{{ route('tenant.register') }}" class="hover:text-sltds-sky transition-colors">Register Venue</a></li>
                        <li><a href="{{ route('parent.contact') }}" class="hover:text-sltds-sky transition-colors">Request a Demo</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

</body>
</html>
