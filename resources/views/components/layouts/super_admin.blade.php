<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 font-sans antialiased selection:bg-white selection:text-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'BookFlow Platform Super-Admin' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileMenuOpen: false }" class="flex flex-col min-h-screen bg-slate-950 text-white font-sans antialiased selection:bg-white selection:text-slate-950 relative overflow-x-hidden">

    <!-- Parent-Inspired High-Res Stadium Lights Background Canvas -->
    <img src="/images/contact_stadium_lights.png" alt="Stadium Lights Backdrop" class="fixed inset-0 w-full h-full object-cover opacity-40 scale-105 z-0 pointer-events-none">
    <div class="fixed inset-0 bg-linear-to-b from-slate-950/70 via-slate-950/50 to-slate-950 z-0 pointer-events-none"></div>

    <!-- Super Admin Header Bar (VADEL Parent-Inspired Specular Liquid Glass Navbar) -->
    <header class="z-50 bg-slate-950/80 backdrop-blur-3xl border-b border-white/15 sticky top-0 shadow-[0_20px_50px_rgba(0,0,0,0.8)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Left Brand Logo & Platform Pill -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 group">
                        <span class="text-2xl sm:text-3xl font-black italic tracking-tighter text-white uppercase group-hover:text-slate-300 transition-colors drop-shadow-md">
                            BOOKFLOW
                        </span>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase bg-white/15 backdrop-blur-xl text-white border border-white/25 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] tracking-widest">
                            SUPER-ADMIN
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-4 text-xs font-bold text-slate-300">
                    <a href="{{ route('superadmin.dashboard') }}" class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('superadmin.dashboard') ? 'bg-white/20 text-white font-black border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]' : 'hover:text-white hover:bg-white/10' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('superadmin.tenants') }}" class="px-4 py-2 rounded-full transition-all duration-200 {{ request()->routeIs('superadmin.tenants*') && !request()->routeIs('superadmin.tenants.create') ? 'bg-white/20 text-white font-black border border-white/30 shadow-[inset_0_1px_1px_rgba(255,255,255,0.4)]' : 'hover:text-white hover:bg-white/10' }}">
                        Tenant Directory
                    </a>
                    <a href="{{ route('superadmin.tenants.create') }}" class="inline-flex items-center px-6 py-2.5 rounded-full text-xs font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[inset_0_1px_1px_rgba(255,255,255,0.8),0_10px_20px_rgba(255,255,255,0.2)] transition-all duration-200 hover:scale-105 active:scale-[0.98]">
                        + Provision Tenant
                    </a>
                    <a href="{{ route('parent.home') }}" target="_blank" class="px-3 py-2 text-slate-400 hover:text-white transition-colors font-medium">
                        Parent Site &rarr;
                    </a>
                </nav>

                <!-- Right Profile Badge & Mobile Toggle -->
                <div class="flex items-center gap-4 text-xs font-semibold">
                    <div class="hidden sm:flex items-center gap-3 bg-white/10 backdrop-blur-2xl border border-white/20 rounded-full px-4 py-2 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)]">
                        <div class="w-6 h-6 rounded-full bg-white text-slate-950 font-black text-[10px] flex items-center justify-center shadow-md">
                            SA
                        </div>
                        <div class="text-right leading-tight">
                            <div class="text-white font-black text-xs">{{ Auth::user()->name ?? 'Platform Staff' }}</div>
                            <div class="text-slate-300 text-[10px] font-mono">{{ Auth::user()->email ?? 'superadmin@sltdigital.com' }}</div>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-full text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all shadow-[inset_0_1px_1px_rgba(255,255,255,0.25)] hover:scale-105 active:scale-[0.98]">
                            Sign Out
                        </button>
                    </form>

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2.5 rounded-full border border-white/20 text-slate-200 hover:bg-white/10 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Navigation Drawer -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden py-4 border-t border-white/15 space-y-2 text-xs font-bold">
                <a href="{{ route('superadmin.dashboard') }}" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">
                    Dashboard Overview
                </a>
                <a href="{{ route('superadmin.tenants') }}" class="block px-4 py-2.5 rounded-2xl text-white hover:bg-white/10">
                    Tenant Directory
                </a>
                <a href="{{ route('superadmin.tenants.create') }}" class="block px-4 py-2.5 rounded-full text-slate-950 bg-white font-black text-center shadow-lg">
                    + Provision New Tenant
                </a>
                <a href="{{ route('parent.home') }}" target="_blank" class="block px-4 py-2.5 rounded-2xl text-slate-300 hover:bg-white/10">
                    Parent Marketing Site &rarr;
                </a>
                <div class="pt-2 border-t border-white/15">
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 rounded-full text-center font-black text-rose-300 bg-rose-500/20 border border-rose-400/30">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <!-- Global Flash Messages -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-3">
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

    <!-- Main Content Slot -->
    <main class="relative z-10 grow py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Super Admin Specular Dark Liquid Glass Footer -->
    <footer class="relative z-10 bg-slate-950/90 backdrop-blur-2xl text-slate-400 py-10 border-t border-white/15 text-xs text-center shadow-[inset_0_1px_1px_rgba(255,255,255,0.15)]">
        <div class="max-w-7xl mx-auto px-4 space-y-2">
            <div class="font-black text-white uppercase tracking-wider text-sm">SLT Digital Services (Pvt) Ltd</div>
            <div class="text-slate-400 font-normal">Platform Staff Super-Admin Control Panel &bull; Confidential Internal System</div>
        </div>
    </footer>

</body>
</html>
