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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 font-sans antialiased selection:bg-white selection:text-slate-950">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }" class="h-full font-sans text-slate-100 bg-slate-950 flex flex-col overflow-hidden relative selection:bg-white selection:text-slate-950">

    <!-- Parent-Inspired High-Res Stadium Lights Background Canvas -->
    <img src="/images/contact_stadium_lights.png" alt="Stadium Lights Backdrop" class="fixed inset-0 w-full h-full object-cover opacity-35 scale-105 z-0 pointer-events-none">
    <div class="fixed inset-0 bg-linear-to-b from-slate-950/80 via-slate-950/60 to-slate-950 z-0 pointer-events-none"></div>

    <div class="flex-1 flex overflow-hidden relative z-10">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" 
         x-cloak 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-md md:hidden"></div>

    <!-- Admin Specular Liquid Glass Sidebar Navigation -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
           class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-slate-900/90 backdrop-blur-3xl border-r border-white/20 flex flex-col shrink-0 transition-all duration-300 ease-out shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),inset_-1px_0_1px_rgba(255,255,255,0.25),30px_0_70px_rgba(0,0,0,0.85)]">
        
        <!-- Tenant & Parent Platform Brand Header -->
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <a href="@tenantUrl('admin.dashboard')" class="shrink-0 group flex items-center gap-2">
                    <span class="text-xl font-black italic tracking-tighter text-white uppercase group-hover:text-slate-300 transition-colors drop-shadow-md">
                        BOOKFLOW
                    </span>
                </a>
                <span class="text-white/20 font-light text-xs shrink-0">/</span>
                <div class="min-w-0">
                    <h2 class="text-xs font-black italic tracking-tighter uppercase text-slate-300 leading-tight truncate drop-shadow-sm">{{ $tenantName }}</h2>
                </div>
            </div>
            
            <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1 cursor-pointer transition-colors">
                ✕
            </button>
        </div>
        <!-- Sidebar Navigation Menu -->
        <nav class="p-4 space-y-6 grow overflow-y-auto custom-scrollbar">
            
            <!-- CORE NAVIGATION GROUP -->
            <div class="space-y-1.5">
                <span class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Core Navigation</span>
                
                <a href="@tenantUrl('admin.dashboard')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span>Dashboard Overview</span>
                    </div>
                </a>

                <a href="@tenantUrl('admin.bookings')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.bookings') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-sky-500/20 border border-sky-400/30 text-sky-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span>Bookings Schedule</span>
                    </div>
                </a>

                <a href="@tenantUrl('admin.courts')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.courts') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-teal-500/20 border border-teal-400/30 text-teal-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <span>Courts & Facilities</span>
                    </div>
                </a>
            </div>

            <!-- REVENUE & GOVERNANCE GROUP -->
            <div class="space-y-1.5">
                <span class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Revenue & Governance</span>

                @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
                <a href="@tenantUrl('admin.pricing')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.pricing') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-purple-500/20 border border-purple-400/30 text-purple-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span>Pricing & Rate Rules</span>
                    </div>
                </a>
                @endif

                <a href="@tenantUrl('admin.customers')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.customers') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-cyan-500/20 border border-cyan-400/30 text-cyan-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span>Customers & Credits</span>
                    </div>
                </a>

                @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']))
                <a href="@tenantUrl('admin.staff')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.staff') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-amber-500/20 border border-amber-400/30 text-amber-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span>Staff & Roles</span>
                    </div>
                </a>
                @endif

                @if(in_array(auth()->user()->role ?? '', ['owner', 'manager', 'super_admin']) || auth()->user()?->isSuperAdmin())
                <a href="@tenantUrl('admin.settings')" 
                   class="flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs font-bold transition-all duration-200 group {{ request()->routeIs('admin.settings') ? 'bg-white/20 text-white font-black border border-white/40 shadow-[inset_0_1px_1px_rgba(255,255,255,0.5),0_10px_25px_rgba(0,0,0,0.5)] translate-x-1' : 'text-slate-300 hover:text-white hover:bg-white/10 border border-transparent hover:border-white/15 hover:translate-x-1' }}">
                    <div class="flex items-center gap-3">
                        <div class="p-1.5 rounded-xl bg-slate-500/20 border border-slate-400/30 text-slate-300 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] group-hover:scale-110 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span>Site Settings & Branding</span>
                    </div>
                </a>
                @endif
            </div>
        </nav>

        <!-- Sidebar Footer Action Controls -->
        <div class="p-4 border-t border-white/10 space-y-2.5 bg-slate-950/50 backdrop-blur-2xl">
            <a href="@tenantUrl('booking.index')" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/25 transition-all duration-200 shadow-[inset_0_1px_1px_rgba(255,255,255,0.35)] hover:scale-[1.02] active:scale-95">
                <span>View Venue Site</span>
                <span class="text-[10px]">↗</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-black text-rose-200 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-400/30 transition-all duration-200 hover:scale-[1.02] active:scale-95 cursor-pointer shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    <svg class="w-4 h-4 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>
            <div class="text-center text-[9px] font-medium text-slate-500 tracking-wider pt-1">
                BookFlow Engine v2.4 &bull; Confidential
            </div>
        </div>
    </aside>

    <!-- Main Admin Workspace Layout -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top Admin Specular Liquid Glass Header Bar -->
        <header class="h-20 bg-slate-950/80 backdrop-blur-3xl border-b border-white/15 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-40 shrink-0 sticky top-0 shadow-[0_20px_50px_rgba(0,0,0,0.8)]">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 border border-white/15">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-lg sm:text-xl font-black italic tracking-tighter uppercase text-white leading-tight drop-shadow">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-3 bg-white/10 backdrop-blur-2xl border border-white/20 rounded-full px-4 py-2 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                    <div class="w-6 h-6 rounded-full bg-white text-slate-950 font-black text-[10px] flex items-center justify-center shadow-md">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 2)) }}
                    </div>
                    <div class="text-right leading-tight">
                        <div class="text-white font-black text-xs">{{ auth()->user()->name ?? 'Staff User' }}</div>
                        <div class="text-slate-400 text-[10px] font-mono">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>

                @if(session('is_impersonating') || (auth()->check() && auth()->user()->isSuperAdmin()))
                    <a href="{{ route('superadmin.stop-impersonating') }}" class="px-4 py-2 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.6),0_10px_20px_rgba(245,158,11,0.3)] transition-all flex items-center gap-1.5 hover:scale-105 active:scale-95">
                        ← Exit to Super-Admin
                    </a>
                @endif
                <form action="{{ route('logout', ['tenant' => $tenantSlug]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] hover:scale-105 active:scale-95 cursor-pointer">
                        Sign Out
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Workspace Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
            <!-- Global Flash Status / Error / Validation Alerts -->
            <div class="max-w-7xl mx-auto space-y-4">
                @if(session('status'))
                    <div class="bg-emerald-500/20 backdrop-blur-2xl border border-emerald-400/40 text-emerald-200 px-6 py-4 rounded-3xl text-xs font-bold flex items-center justify-between shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-300 hover:text-white font-black text-sm cursor-pointer">✕</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-rose-500/20 backdrop-blur-2xl border border-rose-400/40 text-rose-200 px-6 py-4 rounded-3xl text-xs font-bold flex items-center justify-between shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-300 hover:text-white font-black text-sm cursor-pointer">✕</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-rose-500/20 backdrop-blur-2xl border border-rose-400/40 text-rose-200 px-6 py-4 rounded-3xl text-xs font-semibold shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] space-y-2">
                        <div class="font-extrabold flex items-center gap-2 text-rose-100">
                            <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Please fix the following validation errors:</span>
                        </div>
                        <ul class="list-disc list-inside pl-5 space-y-1 text-rose-200 font-medium">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{ $slot }}

            <!-- Specular Dark Liquid Glass Tenant Admin Footer -->
            <footer class="relative z-10 bg-slate-950/90 backdrop-blur-2xl text-slate-400 py-8 border-t border-white/15 text-xs text-center shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)] mt-12 rounded-3xl">
                <div class="max-w-7xl mx-auto px-4 space-y-1">
                    <div class="font-black text-white uppercase tracking-wider text-xs">{{ $tenantName }} Operations Engine</div>
                    <div class="text-slate-400 font-normal">Powered by BookFlow Multi-Tenant Booking Engine &bull; Confidential Internal System</div>
                </div>
            </footer>
        </main>
    </div>
</div>
</body>
</html>

