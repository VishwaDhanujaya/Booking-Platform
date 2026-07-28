@php
    $tenant = \App\Services\TenantResolver::getActiveTenant();
    $allTenants = \App\Services\TenantResolver::getAllTenants();

    $tenantName = $tenant['name'];
    $tenantSlug = $tenant['slug'];
    $brandColor = $tenant['brand_color'] ?? '#0056A2';
    $logoInitial = $tenant['logo_initial'] ?? 'C';
    $logoUrl = $tenant['logo_url'] ?? null;
    $faviconUrl = $tenant['favicon_url'] ?? null;
    $tenantAddress = $tenant['address'] ?? 'Colombo, Sri Lanka';
    $tenantPhone = $tenant['phone'] ?? '+94 11 234 5678';
    $tenantEmail = $tenant['email'] ?? ('info@' . $tenantSlug . '.lk');
    $openingHours = $tenant['opening_hours'] ?? 'Mon - Sun: 6:00 AM - 10:00 PM';
    $notices = $tenant['notices'] ?? [];
    $navSettings = $tenant['nav_settings'] ?? ['show_courts' => true, 'show_pricing' => true, 'show_passes' => true, 'show_rules' => true, 'show_contact' => true];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($tenantName . ' | Official Reservation Portal') }}</title>
    <meta name="description" content="{{ $tenant['tagline'] ?? ('Premier court booking and facility reservation platform for ' . $tenantName) }}">
    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">

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

    <!-- DEMO TENANT SWITCHER BAR (ONLY SHOWN WHEN EXPLICITLY REQUESTED WITH ?demo_switcher=1) -->
    @if(request()->has('demo_switcher'))
    <div class="bg-slate-950 text-white text-xs py-2 px-4 border-b border-slate-800 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-amber-400 text-slate-950 font-black uppercase text-[10px] tracking-wider shadow-xs">
                    Multi-Tenant Platform Demo Mode
                </span>
                <span class="text-slate-300 font-semibold text-xs hidden sm:inline">&bull; Active Tenant:</span>
                <span class="text-amber-300 font-bold hidden sm:inline">{{ $tenantName }}</span>
            </div>
            <div class="flex items-center gap-4">
                <select onchange="window.location.href = this.value" 
                        class="bg-slate-900 text-white text-xs font-bold py-1 px-3 rounded-xl border border-slate-700">
                    @foreach($allTenants as $t)
                        <option value="{{ url('/' . $t->slug . '/book') }}" {{ $tenantSlug === $t->slug ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->slug }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif

    <!-- DYNAMIC TENANT NOTICES BANNER STRIP -->
    @if(!empty($notices))
        <div class="py-2.5 px-4 text-center text-xs font-bold text-white tracking-wide shadow-xs border-b border-white/20" style="background-color: {{ $brandColor }};">
            <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center gap-6">
                @foreach($notices as $notice)
                    @if(!empty($notice['title']))
                        <a href="{{ $notice['link'] ?? '#' }}" class="inline-flex items-center gap-1.5 hover:underline font-extrabold">
                            <svg class="w-4 h-4 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $notice['title'] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Header Navigation -->
    <header x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Dynamic Tenant Identity -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('booking.index', request()->only('tenant')) }}" class="group flex items-center gap-3">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-12 max-w-55 object-contain">
                        @else
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform" style="background-color: {{ $brandColor }};">
                                {{ $logoInitial }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-lg font-black tracking-tight text-slate-900 leading-tight transition-colors">
                                    {{ $tenantName }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1">
                                    {{ $tenant['category'] ?? 'Sports Venue & Facility' }}
                                </span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Primary Desktop Nav (Controlled by Tenant Nav Settings) -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('booking.index', request()->only('tenant')) }}" class="text-sm font-bold {{ request()->routeIs('booking.*') && !request()->routeIs('pricing') ? 'text-slate-900 font-extrabold border-b-2' : 'text-slate-600 hover:text-slate-900' }} transition-colors py-1" style="{{ request()->routeIs('booking.*') && !request()->routeIs('pricing') ? 'border-color:' . $brandColor : '' }}">
                        Book a Court
                    </a>

                    @if(!isset($navSettings['show_pricing']) || $navSettings['show_pricing'])
                        <a href="{{ route('pricing', request()->only('tenant')) }}" class="text-sm font-bold {{ request()->routeIs('pricing') ? 'text-slate-900 font-extrabold border-b-2' : 'text-slate-600 hover:text-slate-900' }} transition-colors py-1" style="{{ request()->routeIs('pricing') ? 'border-color:' . $brandColor : '' }}">
                            Pricing & Membership
                        </a>
                    @endif

                    @auth
                        <a href="{{ route('customer.my-bookings', request()->only('tenant')) }}" class="text-sm font-bold {{ request()->routeIs('customer.*') ? 'text-slate-900 font-extrabold border-b-2' : 'text-slate-600 hover:text-slate-900' }} transition-colors py-1" style="{{ request()->routeIs('customer.*') ? 'border-color:' . $brandColor : '' }}">
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
                                    <span class="text-xs font-extrabold text-slate-900 leading-tight">{{ auth()->user()->name }}</span>
                                    <span class="text-[10px] font-bold text-emerald-600">
                                        LKR {{ number_format(auth()->user()->credit_balance, 0) }} Credits
                                    </span>
                                </div>
                                <div class="w-9 h-9 rounded-xl text-white font-black text-sm flex items-center justify-center shadow-xs" style="background-color: {{ $brandColor }};">
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
                                </div>

                                <a href="{{ route('customer.my-bookings') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Bookings & Passes
                                </a>

                                @if(in_array(auth()->user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']) || auth()->user()?->isSuperAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Staff Portal
                                    </a>
                                @endif

                                <div class="pt-1 border-t border-slate-100">
                                    <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-extrabold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $brandColor }};">
                            Book Court
                        </a>
                    @else
                        <!-- Guest Controls -->
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-slate-900 transition-colors px-3 py-2">
                            Sign In
                        </a>
                        <a href="{{ route('booking.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-extrabold text-white shadow-md transition-all hover:scale-[1.02] active:scale-[0.98]" style="background-color: {{ $brandColor }};">
                            Book Court Now
                            <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
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
            <a href="{{ route('booking.index', request()->only('tenant')) }}" class="block px-3 py-2 rounded-lg text-base font-bold text-slate-800 hover:bg-slate-50">Book a Court</a>
            <a href="{{ route('pricing', request()->only('tenant')) }}" class="block px-3 py-2 rounded-lg text-base font-bold text-slate-800 hover:bg-slate-50">Pricing & Membership</a>
            
            @auth
                <a href="{{ route('customer.my-bookings', request()->only('tenant')) }}" class="block px-3 py-2 rounded-lg text-base font-bold text-slate-800 hover:bg-slate-50">My Account</a>
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                    <div class="px-3 py-2 bg-slate-50 rounded-xl">
                        <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-emerald-600 font-bold">LKR {{ number_format(auth()->user()->credit_balance, 0) }} Credits Available</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 cursor-pointer">Sign Out</button>
                    </form>
                </div>
            @else
                <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                    <a href="{{ route('login', request()->only('tenant')) }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200">Sign In</a>
                    <a href="{{ route('booking.index', request()->only('tenant')) }}" class="w-full text-center px-4 py-2.5 rounded-xl text-sm font-extrabold text-white shadow-md" style="background-color: {{ $brandColor }};">Book a Court Now</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="grow">
        {{ $slot }}
    </main>

    <!-- Shared Standalone Tenant Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Column 1: Brand -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-9 object-contain">
                        @else
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white font-black text-lg" style="background-color: {{ $brandColor }};">
                                {{ $logoInitial }}
                            </div>
                        @endif
                        <span class="text-xl font-black tracking-tight text-white">{{ $tenantName }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ $tenant['tagline'] ?? ('Premier sports venue and court reservation facility in ' . $tenantAddress) }}
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Quick Navigation</h3>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('booking.index', request()->only('tenant')) }}" class="hover:text-white transition-colors">Book a Court</a></li>
                        <li><a href="{{ route('pricing', request()->only('tenant')) }}" class="hover:text-white transition-colors">Pricing & Rates</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact & Opening Hours -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">Facility Contact & Hours</h3>
                    <ul class="space-y-2.5 text-xs text-slate-400">
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
                        <li class="flex items-center gap-2.5 text-amber-400 font-bold">
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $openingHours }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Standalone Venue Information -->
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider">About {{ $tenantName }}</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ $tenant['description'] ?? 'State-of-the-art sports facilities with real-time online court booking and automated lighting.' }}
                    </p>
                </div>
            </div>

            <!-- Bottom Copyright bar -->
            <div class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $tenantName }}. All rights reserved.</p>
                <div class="flex items-center gap-6 text-[11px]">
                    <a href="#" class="hover:text-slate-400">Court Rules & Policies</a>
                    <a href="#" class="hover:text-slate-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
