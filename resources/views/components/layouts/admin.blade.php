@php
    $tenantModel = \App\Services\TenantResolver::getActiveTenantModel();
    $allTenants = \App\Services\TenantResolver::getAllTenants();

    $tenantName = $tenantModel ? $tenantModel->name : 'Colombo Courts Club';
    $tenantSlug = $tenantModel ? $tenantModel->slug : 'colombo-courts-club';
    $brandColor = $tenantModel ? ($tenantModel->brand_color ?? '#0056A2') : '#0056A2';
    $logoInitial = strtoupper(substr($tenantName, 0, 1));
    $logoUrl = $tenantModel ? ($tenantModel->logo_url ?? null) : null;
    $faviconUrl = $tenantModel ? ($tenantModel->favicon_url ?? null) : null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased selection:bg-sltds-endeavour selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? ($tenantName . ' Staff Portal | SLT Digital Multi-Tenant Engine') }}</title>
    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="h-full font-sans text-slate-800 bg-slate-50 flex flex-col overflow-hidden">



    <div class="flex-1 flex overflow-hidden">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"></div>

    <!-- Admin Light Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
           class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/90 flex flex-col shrink-0 transition-transform duration-200 ease-in-out shadow-xs">
        
        <!-- Tenant & Admin Brand Header -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-9 max-w-40 object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-lg shadow-md" style="background-color: {{ $brandColor }};">
                        {{ $logoInitial }}
                    </div>
                    <div>
                        <h2 class="text-sm font-black tracking-tight text-slate-900 leading-tight">{{ $tenantName }}</h2>
                        <span class="text-[10px] font-bold text-sltds-endeavour uppercase tracking-wider block">Tenant Staff Portal</span>
                    </div>
                @endif
            </div>
            
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-700 p-1">
                ✕
            </button>
        </div>

        <!-- Tenant Context Badge -->
        <div class="p-3.5 border-b border-slate-100">
            <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-2 overflow-hidden">
                    <span class="w-2.5 h-2.5 rounded-full bg-sltds-green shrink-0"></span>
                    <span class="text-xs font-bold text-slate-800 truncate">{{ $tenantName }}</span>
                </div>
                <span class="text-[9px] font-mono bg-white text-slate-700 px-2 py-0.5 rounded-md font-bold border border-slate-200 shrink-0">{{ strtoupper($tenantSlug) }}</span>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="p-3.5 space-y-1 grow overflow-y-auto">
            <span class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Management</span>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-sltds-endeavour" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard Overview
            </a>

            <a href="{{ route('admin.bookings') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.bookings') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-sltds-sky" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Bookings Schedule
            </a>

            <a href="{{ route('admin.courts') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.courts') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-sltds-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Courts & Facilities
            </a>

            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
            <a href="{{ route('admin.pricing') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.pricing') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pricing & Rate Rules
            </a>
            @endif

            <a href="{{ route('admin.customers') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.customers') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Customers & Credits
            </a>

            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
            <a href="{{ route('admin.staff') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.staff') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Staff & Roles
            </a>
            @endif

            @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']) || auth()->user()?->isSuperAdmin())
            <a href="{{ route('admin.settings') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.settings') ? 'bg-blue-50 text-sltds-endeavour font-black border border-blue-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-4 h-4 text-sltds-endeavour" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Site Settings & Branding
            </a>
            @endif
        </nav>

        <!-- Footer links -->
        <div class="p-3.5 border-t border-slate-100 space-y-2">
            <a href="{{ route('booking.index') }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                View Venue Site ↗
            </a>
            <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Admin Workspace Layout -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Admin Header Bar -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0 shadow-xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-base font-black text-slate-900 leading-tight">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-500 hidden sm:inline">Logged in as: <strong class="text-slate-900 font-bold">{{ auth()->user()->name ?? 'Staff User' }}</strong></span>
                @if(session('is_impersonating') || (auth()->check() && auth()->user()->isSuperAdmin()))
                    <a href="{{ route('superadmin.stop-impersonating') }}" class="px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs shadow-xs transition-all flex items-center gap-1.5">
                        ← Exit to Super-Admin
                    </a>
                @endif
                <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-rose-50 hover:text-rose-700 border border-slate-200 hover:border-rose-200 transition-colors cursor-pointer">
                        Sign Out
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Workspace Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
            <!-- Global Flash Status / Error / Validation Alerts -->
            <div class="max-w-7xl mx-auto space-y-4 mb-6">
                @if(session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-xs font-bold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-black text-sm">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-xs font-bold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-black text-sm">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-2xl text-xs font-semibold shadow-xs space-y-1">
                        <div class="font-extrabold flex items-center gap-2 text-rose-900">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Please fix the following validation errors:</span>
                        </div>
                        <ul class="list-disc list-inside pl-5 space-y-0.5 text-rose-700 font-medium">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
