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

    <!-- Top SLTDS Announcement Bar (Endeavour Blue & Fruit Salad Green Accent) -->
    <div class="bg-sltds-endeavour py-2 px-4 text-center text-xs font-semibold text-white tracking-wide shadow-xs border-b border-sltds-sky/30">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black bg-sltds-green text-white shadow-xs">SLTDS Release</span>
            <span>Simple online court grids & member pass scheduling for sports venues.</span>
            <a href="{{ route('parent.features') }}" class="underline hover:text-sltds-sky font-bold ml-1">See How It Works &rarr;</a>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- SLTDS Brand Logo -->
                <a href="{{ route('parent.home') }}" class="flex items-center group">
                    <img src="/images/logo.png" alt="BookFlow Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform">
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden lg:flex items-center gap-1 text-xs font-bold text-slate-700">
                    <a href="{{ route('parent.home') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.home') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Home
                    </a>
                    <a href="{{ route('parent.features') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.features') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Features
                    </a>
                    <a href="{{ route('parent.where-to-use') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.where-to-use') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Where to Use
                    </a>
                    <a href="{{ route('parent.customers') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.customers') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Our Customers
                    </a>
                    <a href="{{ route('parent.pricing') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.pricing') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Pricing
                    </a>
                    <a href="{{ route('parent.contact') }}" class="px-3 py-1.5 rounded-xl transition-all {{ request()->routeIs('parent.contact') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-100' : 'hover:text-sltds-endeavour hover:bg-slate-100/70' }}">
                        Contact
                    </a>
                </nav>

                <!-- Right Action CTAs -->
                <div class="flex items-center gap-2 sm:gap-3">
                    @guest
                        <a href="{{ route('superadmin.login') }}" class="inline-flex text-xs font-bold text-slate-700 hover:text-sltds-endeavour px-3 py-2 transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('tenant.register') }}" class="hidden md:inline-flex items-center text-xs font-bold text-sltds-endeavour hover:text-sltds-endeavour bg-blue-50 hover:bg-blue-100/80 px-3.5 py-2 border border-sltds-endeavour/30 rounded-xl transition-all">
                            Register Venue
                        </a>
                        <a href="{{ route('parent.demo') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-md shadow-sltds-endeavour/20 transition-all hover:scale-[1.01]">
                            Book a Demo
                        </a>
                    @else
                        @if(Auth::user()->isSuperAdmin())
                            <a href="{{ \Illuminate\Support\Facades\Route::has('superadmin.dashboard') ? route('superadmin.dashboard') : url('/platform-admin') }}" class="inline-flex items-center gap-1.5 text-xs font-black text-white bg-sltds-endeavour hover:bg-sltds-sky px-3.5 py-2 rounded-xl shadow-md transition-all">
                                <span class="w-2 h-2 rounded-full bg-sltds-green animate-pulse"></span>
                                Platform Admin
                            </a>
                        @elseif(in_array(Auth::user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk']))
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-bold text-white bg-sltds-endeavour hover:bg-sltds-sky px-3.5 py-2 rounded-xl shadow-md transition-all">
                                Admin Portal
                            </a>
                        @else
                            <a href="{{ route('customer.my-bookings') }}" class="inline-flex items-center text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 px-3.5 py-2 rounded-xl transition-all">
                                My Bookings
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-slate-500 hover:text-rose-600 px-2 py-2 transition-colors">
                                Sign Out
                            </button>
                        </form>
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
