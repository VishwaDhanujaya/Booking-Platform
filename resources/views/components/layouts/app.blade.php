@php
    $tenantModel = \App\Services\TenantResolver::getActiveTenantModel();
    $allTenants = \App\Services\TenantResolver::getAllTenants();

    $tenantName = $tenantModel ? $tenantModel->name : 'Colombo Courts Club';
    $tenantSlug = $tenantModel ? $tenantModel->slug : 'colombo-courts-club';
    $brandColor = $tenantModel ? ($tenantModel->brand_color ?? '#0284c7') : '#0284c7';
    $logoInitial = strtoupper(substr($tenantName, 0, 1));
    $tenantAddress = $tenantModel ? $tenantModel->address : 'Colombo, Sri Lanka';
    $tenantPhone = $tenantModel ? $tenantModel->phone : '+94 11 234 5678';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($tenantName . ' | Powered by SLT Digital Services') }}</title>
    <meta name="description" content="Premier multi-tenant court booking platform for Tennis, Padel, Badminton & Squash by SLT Digital Services.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Dynamic Tenant Brand Theme Injection -->
    <style>
        :root {
            --color-brand-600: {{ $brandColor }};
            --color-brand-700: {{ $brandColor }};
            --color-brand-500: {{ $brandColor }};
            --color-brand-50: #f0f9ff;
            --color-accent-500: #0f172a;
        }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-full font-sans text-slate-900 bg-slate-50 selection:bg-brand-500 selection:text-white">

    <!-- TOP DEMO MULTI-TENANT SWITCHER BAR -->
    <div class="bg-slate-950 text-white text-xs py-2 px-4 border-b border-slate-800 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-amber-400 text-slate-950 font-black uppercase text-[10px] tracking-wider shadow-xs">
                    Multi-Tenant Platform
                </span>
                <span class="text-slate-300 font-semibold text-xs hidden sm:inline">&bull; Facility:</span>
                <span class="text-amber-300 font-bold hidden sm:inline">{{ $tenantName }} ({{ $tenantSlug }})</span>
            </div>

            <!-- Subdomain & Query Tenant Switcher -->
            <div class="flex items-center gap-4">
                <div class="relative">
                    <select onchange="window.location.href = this.value" 
                            class="bg-slate-900 text-white text-xs font-bold py-1.5 pl-3 pr-8 rounded-xl border border-slate-700 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer shadow-sm appearance-none transition-colors">
                        @foreach($allTenants as $t)
                            <option value="{{ request()->fullUrlWithQuery(['tenant' => $t->slug]) }}" {{ $tenantSlug === $t->slug ? 'selected' : '' }}>
                                {{ $t->name }} ({{ $t->slug }}.localhost)
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                @auth
                    @if(in_array(auth()->user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']))
                        <a href="{{ route('admin.dashboard') }}" class="hidden md:flex items-center gap-1 font-bold text-amber-400 hover:text-amber-300 transition-colors text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Staff Portal &rarr;
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </div>

    <!-- Header Navigation -->
    <header x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Dynamic Tenant Identity -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform" style="background-color: {{ $brandColor }};">
                            {{ $logoInitial }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold tracking-tight text-slate-900 leading-tight transition-colors">
                                {{ $tenantName }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-widest flex items-center gap-1">
                                Powered by SLT Digital SaaS
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Primary Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                        Home
                    </a>
                    <a href="{{ route('booking.index') }}" class="text-sm font-semibold {{ request()->routeIs('booking.*') ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                        Book a Court
                    </a>
                    <a href="{{ route('pricing') }}" class="text-sm font-semibold {{ request()->routeIs('pricing') ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                        Pricing & Membership
                    </a>
                    <a href="{{ route('tenant.register') }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 transition-colors">
                        Start Free Trial &rarr;
                    </a>
                    @auth
                        <a href="{{ route('customer.my-bookings') }}" class="text-sm font-semibold {{ request()->routeIs('customer.*') ? 'text-slate-900 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors">
                            My Account
                        </a>
                    @endauth
                </nav>

                <!-- Desktop Navigation Controls (Logged-in vs Guest) -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <!-- Authenticated User Profile Menu -->
                        <div class="relative" @click.away="userMenuOpen = false">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    class="flex items-center gap-3 p-1.5 pl-3 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-slate-300 transition-all cursor-pointer">
                                <div class="flex flex-col text-right">
                                    <span class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] font-semibold text-emerald-600">
                                        LKR {{ number_format(auth()->user()->credit_balance, 0) }} Credits
                                    </span>
                                </div>
                                <div class="w-9 h-9 rounded-xl text-white font-bold text-sm flex items-center justify-center shadow-xs" style="background-color: {{ $brandColor }};">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <svg class="w-4 h-4 text-slate-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="userMenuOpen" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50 space-y-1">
                                
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-slate-100 text-[10px] font-extrabold uppercase text-slate-700">
                                        {{ ucfirst(auth()->user()->role) }} Account
                                    </span>
                                </div>

                                <a href="{{ route('customer.my-bookings') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Bookings & Passes
                                </a>

                                @if(in_array(auth()->user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']))
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-amber-600 hover:bg-amber-50">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Staff Management Portal
                                    </a>
                                @endif

                                <div class="pt-1 border-t border-slate-100">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $brandColor }};">
                            Book Court
                        </a>
                    @else
                        <!-- Guest Controls -->
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900 transition-colors px-3 py-2">
                            Sign In
                        </a>
                        <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $brandColor }};">
                            Book Court Now
                            <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Toggle Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen">
                        <span class="sr-only">Open menu</span>
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200" 
             x-transition:enter-start="opacity-0 -translate-y-4" 
             x-transition:enter-end="opacity-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-150" 
             x-transition:leave-start="opacity-100 translate-y-0" 
             x-transition:leave-end="opacity-0 -translate-y-4" 
             class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3 shadow-lg">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50">Home</a>
            <a href="{{ route('booking.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50">Book a Court</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50">Pricing & Membership</a>
            
            @auth
                <a href="{{ route('customer.my-bookings') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50">My Account</a>
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                    <div class="px-3 py-2 bg-slate-50 rounded-xl">
                        <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-emerald-600 font-semibold">LKR {{ number_format(auth()->user()->credit_balance, 0) }} Credits Available</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 cursor-pointer">Sign Out</button>
                    </form>
                </div>
            @else
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200">Sign In</a>
                    <a href="{{ route('booking.index') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md" style="background-color: {{ $brandColor }};">Book a Court Now</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="grow">
        {{ $slot }}
    </main>

    <!-- Shared Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Column 1: Brand -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-extrabold text-lg" style="background-color: {{ $brandColor }};">
                            {{ $logoInitial }}
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">{{ $tenantName }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Premier multi-tenant court booking & facility platform powered by SLT Digital Engine.
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Quick Links</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('booking.index') }}" class="hover:text-white transition-colors">Book a Court</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition-colors">Pricing & Membership</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Location -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Facility Contact</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $tenantAddress }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $tenantPhone }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Multi-Tenant Platform Engine -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Multi-Tenant Platform</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Subdomain-based tenant isolation and custom white-label branding.
                    </p>
                </div>
            </div>

            <!-- Bottom Copyright bar -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $tenantName }}. Powered by SLT Digital Services.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
