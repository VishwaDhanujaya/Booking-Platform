<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Staff & Admin Portal | SLT Digital Multi-Tenant Engine' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="h-full font-sans text-slate-100 bg-slate-950 selection:bg-brand-500 selection:text-white flex overflow-hidden">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-xs md:hidden"></div>

    <!-- Admin Dark Sidebar Navigation (Distinct Visual Treatment) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
           class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 transition-transform duration-200 ease-in-out">
        
        <!-- Tenant & Admin Brand Header -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-brand-600 to-accent-500 flex items-center justify-center text-white font-black text-lg shadow-lg">
                    A
                </div>
                <div>
                    <h2 class="text-sm font-extrabold tracking-tight text-white leading-tight">Apex Admin</h2>
                    <span class="text-[10px] font-semibold text-brand-400 uppercase tracking-widest block">Tenant Staff Portal</span>
                </div>
            </div>
            
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1">
                ✕
            </button>
        </div>

        <!-- Tenant Context Selector Badge -->
        <div class="p-4 border-b border-slate-800/80">
            <div class="bg-slate-800/90 p-3 rounded-xl border border-slate-700/70 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-200">Colombo Courts</span>
                </div>
                <span class="text-[9px] font-mono bg-slate-700 text-slate-300 px-2 py-0.5 rounded-md font-bold">SLT-T1</span>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="p-4 space-y-1.5 grow overflow-y-auto">
            <span class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block mb-2">Management</span>

            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard Overview</span>
            </a>

            <a href="{{ route('admin.bookings') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.bookings') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Bookings Management</span>
            </a>

            <a href="{{ route('admin.courts') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.courts') ? 'bg-brand-600 text-white shadow-md shadow-brand-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Courts & Pricing Editor</span>
            </a>

            <div class="pt-4 border-t border-slate-800/80">
                <span class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block mb-2">Public Site</span>
                
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-400 hover:bg-slate-800 hover:text-white transition-all">
                    <svg class="w-4 h-4 shrink-0 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span>View Public Tenant Site &rarr;</span>
                </a>
            </div>
        </nav>

        <!-- Staff Footer Account Info -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/90 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-800 text-brand-400 font-bold flex items-center justify-center border border-slate-700">
                    SM
                </div>
                <div>
                    <span class="block font-bold text-white leading-tight">Senior Staff Manager</span>
                    <span class="text-[10px] text-slate-500">staff@colombocourts.lk</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Admin Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-sm sm:text-base font-extrabold text-white">{{ $title ?? 'Admin Dashboard' }}</h1>
                <span class="text-xs text-slate-500 hidden sm:inline">&bull; Real-Time Multi-Tenant Oversight</span>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="hidden sm:inline">Live Booking System Online</span>
                    <span class="sm:hidden">Online</span>
                </div>
            </div>
        </header>

        <!-- Main Admin Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-slate-950">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
