@php
    $tenantModel = \App\Services\TenantResolver::getActiveTenantModel();
    $allTenants = \App\Services\TenantResolver::getAllTenants();

    $tenantName = $tenantModel ? $tenantModel->name : 'Colombo Courts Club';
    $tenantSlug = $tenantModel ? $tenantModel->slug : 'colombo-courts-club';
    $brandColor = $tenantModel ? ($tenantModel->brand_color ?? '#0284c7') : '#0284c7';
    $logoInitial = strtoupper(substr($tenantName, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($tenantName . ' Staff Portal | SLT Digital Multi-Tenant Engine') }}</title>

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

    <!-- Admin Dark Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
           class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 transition-transform duration-200 ease-in-out">
        
        <!-- Tenant & Admin Brand Header -->
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-lg" style="background-color: {{ $brandColor }};">
                    {{ $logoInitial }}
                </div>
                <div>
                    <h2 class="text-sm font-extrabold tracking-tight text-white leading-tight">{{ $tenantName }}</h2>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest block">Tenant Staff Portal</span>
                </div>
            </div>
            
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1">
                ✕
            </button>
        </div>

        <!-- Tenant Context Selector Badge -->
        <div class="p-4 border-b border-slate-800/80">
            <div class="bg-slate-800/90 p-3 rounded-xl border border-slate-700/70 flex flex-col gap-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span class="text-xs font-bold text-slate-200">{{ $tenantName }}</span>
                    </div>
                    <span class="text-[9px] font-mono bg-slate-700 text-slate-300 px-2 py-0.5 rounded-md font-bold">{{ strtoupper($tenantSlug) }}</span>
                </div>
                <!-- Subdomain & Query Switcher inside Admin -->
                <select onchange="window.location.href = this.value" class="w-full bg-slate-900 text-white text-[11px] font-semibold py-1 px-2 rounded-lg border border-slate-700">
                    @foreach($allTenants as $t)
                        <option value="{{ request()->fullUrlWithQuery(['tenant' => $t->slug]) }}" {{ $tenantSlug === $t->slug ? 'selected' : '' }}>
                            Switch: {{ $t->name }} ({{ $t->slug }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="p-4 space-y-1.5 grow overflow-y-auto">
            <span class="px-3 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block mb-2">Management</span>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard Overview
            </a>

            <a href="{{ route('admin.bookings') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Bookings Schedule
            </a>

            <a href="{{ route('admin.courts') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.courts') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Courts & Facilities
            </a>

            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
            <a href="{{ route('admin.pricing') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.pricing') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pricing & Rate Rules
            </a>
            @endif

            <a href="{{ route('admin.customers') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-colors {{ request()->routeIs('admin.customers') ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Customers & Credits
            </a>
        </nav>

        <!-- Footer link back to public site -->
        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-slate-800 hover:bg-slate-700 transition-colors">
                &larr; Return to Public Website
            </a>
        </div>
    </aside>

    <!-- Main Admin Workspace Layout -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Admin Header Bar -->
        <header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base font-bold text-white leading-tight">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 hidden sm:inline">Logged in as: <strong class="text-white">{{ auth()->user()->name ?? 'Staff User' }}</strong></span>
            </div>
        </header>

        <!-- Main Workspace Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-950">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
