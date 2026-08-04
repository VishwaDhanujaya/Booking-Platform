<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-sltds-endeavour selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SLT Digital Services | Sports Court & Facility Booking System' }}</title>
    <meta name="description" content="Simple, modern online court booking, member pass, and venue management software for sports clubs, gyms, studios, and facilities.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Main Navigation Header (VADEL Inspired Minimalist Header) -->
    <header class="sticky top-0 z-50 bg-slate-50 py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-18">
                
                <!-- Original Brand Logo -->
                <a href="{{ route('parent.home') }}" class="flex items-center group">
                    <img src="/images/logo.png" alt="BookFlow Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform">
                </a>

                <!-- VADEL Style Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-800">
                    <a href="{{ route('parent.home') }}" class="hover:text-slate-950 transition-colors {{ request()->routeIs('parent.home') ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('parent.features') }}" class="hover:text-slate-950 transition-colors {{ request()->routeIs('parent.features') ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Features
                    </a>
                    <a href="{{ route('parent.solutions') }}" class="hover:text-slate-950 transition-colors {{ (request()->routeIs('parent.solutions') || request()->routeIs('parent.where-to-use')) ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Solutions
                    </a>
                    <a href="{{ route('parent.live-venues') }}" class="hover:text-slate-950 transition-colors {{ (request()->routeIs('parent.live-venues') || request()->routeIs('parent.customers')) ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Live Venues
                    </a>
                    <a href="{{ route('parent.pricing') }}" class="hover:text-slate-950 transition-colors {{ request()->routeIs('parent.pricing') ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Pricing
                    </a>
                    <a href="{{ route('parent.contact') }}" class="hover:text-slate-950 transition-colors {{ request()->routeIs('parent.contact') ? 'text-slate-950 font-bold border-b-2 border-slate-950 pb-0.5' : '' }}">
                        Contact
                    </a>
                </nav>

                <!-- Right Action CTAs -->
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('superadmin.login') }}" class="inline-flex text-xs font-bold text-slate-700 hover:text-slate-950 px-3 py-2 transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('tenant.register') }}" class="hidden md:inline-flex items-center text-xs font-bold text-slate-950 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-full transition-all border border-slate-300">
                            Register Venue
                        </a>
                        <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-extrabold text-white bg-slate-950 hover:bg-slate-800 shadow-md transition-all hover:scale-105">
                            Book a Demo
                        </a>
                    @else
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ \Illuminate\Support\Facades\Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/platform-admin') }}" class="inline-flex items-center text-xs font-black text-white bg-slate-950 hover:bg-slate-800 px-4 py-2.5 rounded-full shadow-md transition-all">
                                Platform Admin
                            </a>
                        @elseif(in_array(Auth::user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk']))
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-bold text-white bg-slate-950 hover:bg-slate-800 px-4 py-2.5 rounded-full shadow-md transition-all">
                                Admin Portal
                            </a>
                        @else
                            <a href="{{ route('customer.my-bookings') }}" class="inline-flex items-center text-xs font-bold text-slate-950 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-full transition-all">
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

    <!-- Simplified VADEL-Inspired Footer -->
    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <!-- Brand Info -->
                <div class="space-y-2">
                    <img src="/images/logo.png" alt="BookFlow Logo" class="h-9 w-auto object-contain">
                    <p class="text-xs text-slate-400 max-w-sm">
                        The all-in-one court scheduling, lighting automation, and member pass platform.
                    </p>
                </div>

                <!-- Navigation Links -->
                <div class="flex flex-wrap items-center gap-6 sm:gap-8 text-xs font-semibold text-slate-300">
                    <a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Features</a>
                    <a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Solutions</a>
                    <a href="{{ route('parent.customers') }}" class="hover:text-white transition-colors">Live Venues</a>
                    <a href="{{ route('pricing') }}" class="hover:text-white transition-colors">Pricing</a>
                    <a href="{{ route('parent.contact') }}" class="hover:text-white transition-colors">Contact</a>
                    <a href="{{ route('tenant.register') }}" class="px-5 py-2.5 rounded-full text-slate-950 bg-white hover:bg-slate-100 font-bold transition-all shadow-md">
                        Register Venue &rarr;
                    </a>
                </div>
            </div>

            <!-- Bottom Copyright & Credit -->
            <div class="pt-8 border-t border-slate-900/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>&copy; {{ date('Y') }} BookFlow. All rights reserved.</p>
                <p>Engineered by SLT Digital Services (Pvt) Ltd</p>
            </div>

        </div>
    </footer>

</body>
</html>
