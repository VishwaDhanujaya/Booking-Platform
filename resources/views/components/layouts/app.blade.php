@php
    $tenant = \App\Services\TenantResolver::getActiveTenant();
    $allTenants = \App\Services\TenantResolver::getAllTenants();

    $tenantName = $tenant['name'];
    $tenantSlug = $tenant['slug'];
    $brandColor = $tenant['brand_color'] ?? '#0056A2';
    $logoInitial = $tenant['logo_initial'] ?? strtoupper(substr($tenantName, 0, 1));
    $logoUrl = $tenant['logo_url'] ?? null;
    
    // Ignore default platform logo image on tenant venue pages
    if ($logoUrl === '/images/logo.png') {
        $logoUrl = null;
    }

    $faviconUrl = $tenant['favicon_url'] ?? null;
    $tenantAddress = $tenant['address'] ?? 'Colombo, Sri Lanka';
    $tenantPhone = $tenant['phone'] ?? '+94 11 234 5678';
    $tenantEmail = $tenant['email'] ?? ('info@' . $tenantSlug . '.lk');
    $openingHours = $tenant['opening_hours'] ?? 'Mon - Sun: 6:00 AM - 10:00 PM';
    $notices = $tenant['notices'] ?? [];
    $navSettings = $tenant['nav_settings'] ?? ['show_courts' => true, 'show_pricing' => true, 'show_passes' => true, 'show_rules' => true, 'show_contact' => true];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased scroll-smooth selection:bg-slate-950 selection:text-white">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

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
<body class="flex flex-col min-h-full font-sans text-slate-900 bg-slate-50 selection:bg-slate-950 selection:text-white">

    <!-- DEMO TENANT SWITCHER BAR (ONLY SHOWN WHEN EXPLICITLY REQUESTED WITH ?demo_switcher=1) -->
    @if(request()->has('demo_switcher'))
    <div class="bg-slate-950 text-white text-xs py-2 px-4 border-b border-white/10 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-white/15 text-white border border-white/20 font-black uppercase text-[10px] tracking-wider shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                    Multi-Tenant Platform Demo Mode
                </span>
                <span class="text-slate-300 font-semibold text-xs hidden sm:inline">&bull; Active Tenant:</span>
                <span class="text-white font-bold hidden sm:inline">{{ $tenantName }}</span>
            </div>
            <div class="flex items-center gap-4">
                <select onchange="window.location.href = this.value" 
                        class="bg-slate-900 text-white text-xs font-bold py-1 px-3 rounded-full border border-white/20">
                    @foreach($allTenants as $t)
                        <option value="{{ \App\Services\TenantResolver::tenantUrl('booking.index', [], $t) }}" {{ $tenantSlug === $t->slug ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->slug }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @endif



    <!-- Header Navigation (VADEL Specular Dark Liquid Glass Navbar) -->
    <header x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="sticky top-0 z-50 bg-slate-950/85 backdrop-blur-3xl border-b border-white/15 shadow-[0_20px_50px_rgba(0,0,0,0.85)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Logo & Dynamic Tenant Identity (100% Tenant Venue Brand - NO Bookflow logo) -->
                <div class="flex items-center gap-3">
                    <a href="@tenantUrl('booking.index')" class="group flex items-center gap-3">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-10 max-w-55 object-contain filter drop-shadow">
                        @else
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-slate-950 font-black text-xl shadow-[inset_0_1px_1px_rgba(255,255,255,0.9),0_10px_20px_rgba(0,0,0,0.5)] group-hover:scale-105 transition-transform" style="background-color: {{ $brandColor }};">
                                {{ $logoInitial }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xl font-black italic tracking-tighter text-white uppercase leading-none group-hover:text-slate-300 transition-colors drop-shadow-md">
                                    {{ $tenantName }}
                                </span>
                                <span class="text-[9px] font-mono font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1 mt-1">
                                    {{ $tenant['category'] ?? 'Sports Venue & Facility' }}
                                </span>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Primary Desktop Nav (Standard B2B Link Names) -->
                <nav class="hidden md:flex items-center gap-4 text-xs font-bold text-slate-300 tracking-wide">
                    <a href="@tenantUrl('booking.index')" class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('booking.*') && !request()->routeIs('pricing') ? 'bg-white/20 text-white font-black border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]' : 'hover:text-white hover:bg-white/10' }}">
                        Book a Court
                    </a>

                    @if(!isset($navSettings['show_pricing']) || $navSettings['show_pricing'])
                        <a href="@tenantUrl('pricing')" class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('pricing') ? 'bg-white/20 text-white font-black border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]' : 'hover:text-white hover:bg-white/10' }}">
                            Pricing & Membership
                        </a>
                    @endif

                    @auth
                        <a href="@tenantUrl('customer.my-bookings')" class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('customer.*') ? 'bg-white/20 text-white font-black border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]' : 'hover:text-white hover:bg-white/10' }}">
                            My Account
                        </a>
                    @endauth
                </nav>

                <!-- Desktop Navigation Controls (Logged-in vs Guest) -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <!-- Authenticated User Profile Menu -->
                        <div class="relative" x-data="{ userMenuOpen: false }" @click.away="userMenuOpen = false">
                            <button type="button" 
                                    @click="userMenuOpen = !userMenuOpen" 
                                    class="flex items-center gap-3 p-1.5 pl-4 rounded-full border border-white/20 bg-white/10 backdrop-blur-2xl hover:bg-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] cursor-pointer focus:outline-none">
                                <div class="flex flex-col text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <span class="text-xs font-black text-white leading-tight">{{ auth()->user()->name }}</span>
                                        @if(in_array(auth()->user()->role, ['owner', 'manager']))
                                            <span class="text-[8px] font-black uppercase bg-amber-400/20 text-amber-300 border border-amber-400/40 px-1.5 py-0.5 rounded-full leading-none">
                                                {{ auth()->user()->role === 'owner' ? 'Owner' : 'Manager' }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-400">
                                        LKR {{ number_format(auth()->user()->credit_balance, 0) }} Credits
                                    </span>
                                </div>
                                <div class="w-8 h-8 rounded-full text-white font-black text-xs flex items-center justify-center shadow-md border border-white/20 shrink-0" style="background-color: {{ $brandColor }};">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <svg class="w-4 h-4 text-slate-400 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- User Dropdown Menu (Liquid Glass) -->
                            <div x-show="userMenuOpen" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-slate-900/95 backdrop-blur-3xl rounded-3xl shadow-2xl border border-white/20 py-2 z-50 space-y-1 text-white">
                                
                                <div class="px-4 py-2 border-b border-white/10">
                                    <p class="text-xs font-black text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate font-mono">{{ auth()->user()->email }}</p>
                                </div>

                                <a href="@tenantUrl('customer.my-bookings')" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-300 hover:bg-white/10 hover:text-white">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Bookings & Passes
                                </a>

                                @if(in_array(auth()->user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']) || auth()->user()?->isSuperAdmin())
                                    <a href="@tenantUrl('admin.dashboard')" class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-slate-300 hover:bg-white/10 hover:text-white">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        </svg>
                                        Staff Portal
                                    </a>
                                @endif

                                <div class="pt-1 border-t border-white/10">
                                    <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs font-bold text-rose-300 hover:bg-rose-500/20 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <a href="@tenantUrl('booking.index')" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-[0.98]">
                            Book Court
                        </a>
                    @else
                        <!-- Guest Controls -->
                        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-300 hover:text-white transition-colors px-3 py-2">
                            Sign In
                        </a>
                        <a href="@tenantUrl('booking.index')" class="inline-flex items-center justify-center px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-[0.98]">
                            Book Court Now
                            <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Toggle Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2.5 rounded-full border border-white/20 text-slate-200 hover:bg-white/10 focus:outline-none" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen">
                        <span class="sr-only">Open menu</span>
                        <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
             class="md:hidden border-b border-white/15 bg-slate-950/95 backdrop-blur-3xl px-4 pt-2 pb-6 space-y-3 shadow-2xl text-xs font-bold">
            <a href="@tenantUrl('booking.index')" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">Book a Court</a>
            <a href="@tenantUrl('pricing')" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">Pricing & Membership</a>
            
            @auth
                <a href="@tenantUrl('customer.my-bookings')" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">My Bookings & Passes</a>
                @if(in_array(auth()->user()->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']) || auth()->user()?->isSuperAdmin())
                    <a href="@tenantUrl('admin.dashboard')" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">Staff Portal</a>
                @endif
                <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST" class="pt-2 border-t border-white/15">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 rounded-2xl font-black text-rose-300 hover:bg-rose-500/20">
                        Sign Out ({{ auth()->user()->name }})
                    </button>
                </form>
            @else
                <div class="pt-2 border-t border-white/15 flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 rounded-full border border-white/20 font-bold text-white hover:bg-white/10">
                        Sign In
                    </a>
                    <a href="@tenantUrl('booking.index')" class="block w-full text-center px-4 py-2.5 rounded-full font-black text-slate-950 bg-white shadow-lg">
                        Book Court Now
                    </a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Global Flash Notification Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 space-y-2">
        @if(session('status'))
            <div class="bg-emerald-500/20 backdrop-blur-2xl border border-emerald-400/40 text-emerald-200 px-5 py-3.5 rounded-2xl text-xs font-bold flex items-center justify-between shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white font-bold cursor-pointer">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/20 backdrop-blur-2xl border border-rose-400/40 text-rose-200 px-5 py-3.5 rounded-2xl text-xs font-bold flex items-center justify-between shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-300 hover:text-white font-bold cursor-pointer">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content Canvas Slot -->
    <main class="grow">
        {{ $slot }}
    </main>

    <!-- Specular Dark Liquid Glass Tenant Footer -->
    <footer class="bg-slate-950/95 backdrop-blur-3xl text-slate-400 py-12 border-t border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)] relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-white/10 text-xs">
                
                <!-- Left: Tenant Venue Identity (NO Bookflow logo) -->
                <div class="flex items-center gap-3.5">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-8 max-w-44 object-contain filter drop-shadow">
                    @else
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-950 font-black text-sm shadow-[inset_0_1px_1px_rgba(255,255,255,0.9)] shrink-0" style="background-color: {{ $brandColor }};">
                            {{ $logoInitial }}
                        </div>
                    @endif
                    <span class="text-xl font-black italic tracking-tighter text-white uppercase drop-shadow-sm">
                        {{ $tenantName }}
                    </span>
                    <span class="text-slate-600 text-xs font-medium hidden sm:inline">&bull;</span>
                    <span class="text-slate-400 text-xs font-medium hidden sm:inline">{{ $tenantAddress }}</span>
                </div>

                <!-- Right: Inline Navigation Links -->
                <div class="flex flex-wrap items-center justify-center gap-6 font-bold text-slate-300">
                    <a href="@tenantUrl('booking.index')" class="hover:text-white transition-colors">Book a Court</a>
                    <a href="@tenantUrl('pricing')" class="hover:text-white transition-colors">Pricing & Membership</a>
                    @auth
                        <a href="@tenantUrl('customer.my-bookings')" class="hover:text-white transition-colors">My Account</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors">Sign In</a>
                    @endauth
                </div>

            </div>

            <!-- Bottom Line: Powered by BookFlow attribution & Venue Details -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400 font-medium">
                <div>
                    &copy; {{ date('Y') }} {{ $tenantName }}. All rights reserved. <span class="text-slate-500">Powered by BookFlow</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>{{ $tenantPhone }}</span>
                    <span>&bull;</span>
                    <span>{{ $openingHours }}</span>
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
