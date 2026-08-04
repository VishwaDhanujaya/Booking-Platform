<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-slate-950 selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BookFlow | Universal Court & Facility Management System' }}</title>
    <meta name="description" content="Simple, modern online court booking, member pass, and venue management software for sports clubs, gyms, studios, and facilities.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileMenuOpen: false }" class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Seamless Merge Top Navigation Header (No Bottom Border, No Heavy Shadow, Exactly 4rem / h-16) -->
    <header class="sticky top-0 z-50 bg-slate-50 py-1 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo (VADEL Style Display Brand Text Logo) -->
                <a href="{{ route('parent.home') }}" class="flex items-center gap-3 group">
                    <span class="text-2xl sm:text-3xl font-black italic tracking-tighter text-slate-950 uppercase group-hover:text-slate-700 transition-colors">
                        BOOKFLOW
                    </span>
                </a>

                <!-- Desktop Navigation Links (Standard B2B Link Names) -->
                <nav class="hidden lg:flex items-center gap-8 text-xs font-bold text-slate-700 tracking-wide">
                    <a href="{{ route('parent.home') }}" class="transition-colors duration-200 {{ request()->routeIs('home', 'parent.home') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Home
                    </a>
                    <a href="{{ route('parent.features') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.features') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Features
                    </a>
                    <a href="{{ route('parent.where-to-use') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.where-to-use', 'parent.solutions') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Solutions
                    </a>
                    <a href="{{ route('parent.customers') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.customers', 'parent.live-venues') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Live Venues
                    </a>
                    <a href="{{ route('parent.pricing') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.pricing') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Pricing
                    </a>
                    <a href="{{ route('parent.contact') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.contact') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Contact
                    </a>
                </nav>

                <!-- Right Action CTAs (Universal B2B Header: Sign In + Register Venue) -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.login') }}" class="inline-flex text-xs font-bold text-slate-700 hover:text-slate-950 px-3 py-2 transition-colors duration-200">
                        Sign In
                    </a>
                    <a href="{{ route('tenant.register') }}" class="inline-flex items-center text-xs font-black text-white bg-slate-950 hover:bg-slate-900 px-6 py-2.5 rounded-full shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_20px_-5px_rgba(0,0,0,0.3)] transition-all duration-200 hover:scale-105 active:scale-[0.98]">
                        Register Venue
                    </a>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2.5 rounded-full border border-slate-200 text-slate-800 hover:bg-slate-100 focus:outline-none ml-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Navigation Drawer -->
            <div x-show="mobileMenuOpen" x-cloak class="lg:hidden py-4 border-t border-slate-200 space-y-2 text-xs font-bold">
                <a href="{{ route('parent.home') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Home
                </a>
                <a href="{{ route('parent.features') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Features
                </a>
                <a href="{{ route('parent.where-to-use') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Solutions
                </a>
                <a href="{{ route('parent.customers') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Live Venues
                </a>
                <a href="{{ route('parent.pricing') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Pricing
                </a>
                <a href="{{ route('parent.contact') }}" class="block px-4 py-2.5 rounded-2xl text-slate-900 hover:bg-slate-100">
                    Contact
                </a>
                <div class="pt-2 border-t border-slate-200 flex flex-col gap-2">
                    <a href="{{ route('superadmin.login') }}" class="block px-4 py-2.5 rounded-2xl text-center text-slate-700 bg-slate-100 hover:bg-slate-200 font-black">
                        Sign In
                    </a>
                    <a href="{{ route('tenant.register') }}" class="block px-4 py-2.5 rounded-full text-center text-white bg-slate-950 font-black shadow-md">
                        Register Venue
                    </a>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content -->
    <main class="grow">
        {{ $slot }}
    </main>

    <!-- Minimal Modern B2B Footer -->
    <footer class="bg-slate-950 text-slate-400 py-10 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pb-6 border-b border-white/10 text-xs">
                
                <!-- Left: Minimal Brand Text -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('parent.home') }}" class="text-xl font-black italic tracking-tighter text-white uppercase hover:text-slate-300 transition-colors">
                        BOOKFLOW
                    </a>
                    <span class="text-slate-400 text-[11px] font-medium hidden sm:inline">&bull;</span>
                    <span class="text-slate-400 text-xs font-medium">SLT Digital Services (Pvt) Ltd</span>
                </div>

                <!-- Right: Inline Minimal Links -->
                <div class="flex flex-wrap items-center justify-center gap-6 font-semibold text-slate-300">
                    <a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Features</a>
                    <a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Solutions</a>
                    <a href="{{ route('parent.customers') }}" class="hover:text-white transition-colors">Live Venues</a>
                    <a href="{{ route('parent.pricing') }}" class="hover:text-white transition-colors">Pricing</a>
                    <a href="{{ route('parent.contact') }}" class="hover:text-white transition-colors">Contact</a>
                    <a href="{{ route('superadmin.login') }}" class="hover:text-white transition-colors">Platform Admin</a>
                </div>

            </div>

            <!-- Bottom Copyright Line -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-400 font-medium">
                <div>
                    &copy; {{ date('Y') }} BookFlow. All rights reserved.
                </div>
                <div>
                    Universal Sports Venue & Facility Management System
                </div>
            </div>

        </div>
    </footer>

</body>
</html>
