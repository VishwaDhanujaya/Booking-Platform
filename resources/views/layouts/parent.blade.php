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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Single Main Navigation Header for Entire Website (Seamless Top Merge & Specular Glass CTAs) -->
    <header class="sticky top-0 z-50 bg-slate-50/90 backdrop-blur-2xl py-1.5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand Logo (VADEL Style Display Brand Text Logo) -->
                <a href="{{ route('parent.home') }}" class="flex items-center group">
                    <span class="text-2xl font-black italic tracking-tighter text-slate-950 uppercase group-hover:text-sltds-endeavour transition-colors">
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
                    <a href="{{ route('parent.where-to-use') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.where-to-use') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
                        Solutions
                    </a>
                    <a href="{{ route('parent.customers') }}" class="transition-colors duration-200 {{ request()->routeIs('parent.customers') ? 'text-slate-950 font-black border-b-2 border-slate-950 pb-0.5' : 'hover:text-slate-950' }}">
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
                    <a href="{{ route('superadmin.login') }}" class="inline-flex text-xs font-bold text-slate-600 hover:text-slate-950 px-3 py-2 transition-colors duration-200">
                        Sign In
                    </a>
                    <a href="{{ route('tenant.register') }}" class="inline-flex items-center text-xs font-black text-white bg-slate-950 hover:bg-slate-900 px-6 py-2.5 rounded-full shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_20px_-5px_rgba(0,0,0,0.3)] transition-all duration-200 hover:scale-105 active:scale-[0.98]">
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

    <!-- Footer with SLTDS Corporate Identity & VADEL Liquid Glass Design -->
    <footer class="bg-slate-950 text-slate-400 py-16 border-t border-white/10 relative overflow-hidden shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                
                <!-- Brand Overview Card (Liquid Glass Framed) -->
                <div class="lg:col-span-2 space-y-4 bg-white/5 backdrop-blur-2xl border border-white/10 p-6 rounded-3xl shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-black italic tracking-tighter text-white uppercase">
                            BOOKFLOW
                        </span>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-white/10 border border-white/20 text-slate-300 backdrop-blur-xl shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">SLTDS Platform</span>
                    </div>
                    <p class="text-xs leading-relaxed text-slate-300 max-w-sm">
                        Online booking, scheduling, and space management software designed for sports complexes, gyms, studios, and childcare providers.
                    </p>
                    <div class="pt-2 text-xs text-slate-400 font-medium">
                        &copy; {{ date('Y') }} BookFlow by SLT Digital Services (Pvt) Ltd. All rights reserved.
                    </div>
                </div>

                <!-- Column 2: Product -->
                <div class="space-y-3 text-xs pt-2">
                    <div class="font-black text-white uppercase tracking-widest text-[11px]">Product Features</div>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Multi-Court Day Grid</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Capacity & Waitlists</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Peak Hour Pricing Rules</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Member Credits & Passes</a></li>
                        <li><a href="{{ route('parent.features') }}" class="hover:text-white transition-colors">Tax Invoicing</a></li>
                    </ul>
                </div>

                <!-- Column 3: Verticals -->
                <div class="space-y-3 text-xs pt-2">
                    <div class="font-black text-white uppercase tracking-widest text-[11px]">Solutions</div>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Badminton & Tennis Courts</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Padel & Squash Arenas</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Group Fitness Studios</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Gym Equipment Bays</a></li>
                        <li><a href="{{ route('parent.where-to-use') }}" class="hover:text-white transition-colors">Childcare & Creche</a></li>
                    </ul>
                </div>

                <!-- Column 4: Links -->
                <div class="space-y-3 text-xs pt-2">
                    <div class="font-black text-white uppercase tracking-widest text-[11px]">Get Started</div>
                    <ul class="space-y-2.5">
                        <li><a href="{{ route('parent.customers') }}" class="hover:text-white transition-colors">Live Customer Sites</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition-colors">Pricing Plans</a></li>
                        <li><a href="{{ route('tenant.register') }}" class="hover:text-white transition-colors">Register Venue</a></li>
                        <li><a href="{{ route('parent.contact') }}" class="hover:text-white transition-colors">Request a Demo</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </footer>

</body>
</html>
