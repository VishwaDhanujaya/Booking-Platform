@php
    $tenantConfig = \App\Services\TenantResolver::getActiveTenant();
    $allTenants = \App\Services\TenantResolver::getAllTenants();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($tenantConfig['name'] . ' | Powered by SLT Digital Services') }}</title>
    <meta name="description" content="Premier multi-tenant court booking platform for Tennis, Padel, Badminton & Squash by SLT Digital Services.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Dynamic Tenant Brand Theme Injection -->
    <style>
        :root {
            --color-brand-600: {{ $tenantConfig['primary_hex'] }};
            --color-brand-700: {{ $tenantConfig['primary_hover'] }};
            --color-brand-500: {{ $tenantConfig['primary_hex'] }};
            --color-brand-50: {{ $tenantConfig['primary_light'] }};
            --color-accent-500: {{ $tenantConfig['accent_hex'] }};
        }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-full font-sans text-slate-900 bg-slate-50 selection:bg-brand-500 selection:text-white">

    <!-- TOP DEMO MULTI-TENANT SWITCHER BAR (Manager Resale Demo Showcase) -->
    <div class="bg-slate-950 text-white text-xs py-2 px-4 border-b border-slate-800 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-amber-400 text-slate-950 font-black uppercase text-[10px] tracking-wider shadow-xs">
                    Multi-Tenant Demo
                </span>
                <span class="text-slate-300 font-semibold text-xs hidden sm:inline">&bull; Active Client:</span>
            </div>

            <!-- Clean Dropdown Selector (No scrollbar/slider) -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select onchange="window.location.href = this.value" 
                            class="bg-slate-900 text-white text-xs font-bold py-1.5 pl-3 pr-8 rounded-xl border border-slate-700 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-amber-400 cursor-pointer shadow-sm appearance-none transition-colors">
                        @foreach($allTenants as $slug => $t)
                            <option value="{{ request()->fullUrlWithQuery(['tenant' => $slug]) }}" {{ $tenantConfig['slug'] === $slug ? 'selected' : '' }}>
                                {{ $t['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="text-brand-400 hover:text-brand-300 font-bold flex items-center gap-1 transition-colors text-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Manager Admin Portal &rarr;
                </a>
            </div>

        </div>
    </div>

    <!-- Header Navigation -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Dynamic Tenant Identity -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="group flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform" style="background-color: {{ $tenantConfig['primary_hex'] }};">
                            {{ $tenantConfig['logo_initial'] }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold tracking-tight text-slate-900 leading-tight group-hover:text-brand-600 transition-colors">
                                {{ $tenantConfig['name'] }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-500 uppercase tracking-widest flex items-center gap-1">
                                Powered by SLT Digital Engine
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Primary Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Home
                    </a>
                    <a href="{{ route('booking.index') }}" class="text-sm font-semibold {{ request()->routeIs('booking.*') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Book a Court
                    </a>
                    <a href="{{ route('pricing') }}" class="text-sm font-semibold {{ request()->routeIs('pricing') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        Pricing & Membership
                    </a>
                    <a href="{{ route('customer.my-bookings') }}" class="text-sm font-semibold {{ request()->routeIs('customer.*') ? 'text-brand-600' : 'text-slate-600 hover:text-brand-600' }} transition-colors">
                        My Account
                    </a>
                </nav>

                <!-- Desktop CTAs -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('customer.my-bookings') }}" class="text-sm font-medium text-slate-700 hover:text-brand-600 transition-colors px-3 py-2">
                        Sign In
                    </a>
                    <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/20 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        Book Court Now
                        <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
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
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50 hover:text-brand-600">Home</a>
            <a href="{{ route('booking.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50 hover:text-brand-600">Book a Court</a>
            <a href="{{ route('pricing') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50 hover:text-brand-600">Pricing & Membership</a>
            <a href="{{ route('customer.my-bookings') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-800 hover:bg-slate-50 hover:text-brand-600">My Account</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('customer.my-bookings') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200">Sign In</a>
                <a href="{{ route('booking.index') }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-md">Book a Court Now</a>
            </div>
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
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-extrabold text-lg" style="background-color: {{ $tenantConfig['primary_hex'] }};">
                            {{ $tenantConfig['logo_initial'] }}
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">{{ $tenantConfig['name'] }}</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ $tenantConfig['tagline'] }}
                    </p>
                    <div class="flex items-center gap-2 text-xs text-brand-400 bg-slate-800/80 p-2.5 rounded-lg border border-slate-700/60">
                        <svg class="w-4 h-4 text-accent-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Spec: <strong>{{ $tenantConfig['badge'] }}</strong></span>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Available Facilities</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        @foreach($tenantConfig['sports'] as $sportName)
                            <li><a href="{{ route('booking.index') }}" class="hover:text-white transition-colors">{{ $sportName }} Courts</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Column 3: Contact & Location -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Facility Address</h3>
                    <ul class="space-y-2.5 text-sm text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $tenantConfig['address'] }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $tenantConfig['phone'] }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Multi-Tenant SaaS Platform Engine -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Multi-Tenant Platform Engine</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Built on SLT Digital Services' multi-tenant SaaS court engine. High-performance isolation, real-time scheduling, and white-label theme customization.
                    </p>
                    <div class="pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                            <svg class="w-3.5 h-3.5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            SLT Digital SaaS Engine v1.0
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright bar -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $tenantConfig['name'] }}. Powered by SLT Digital Services.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
