<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50 font-sans antialiased selection:bg-sltds-endeavour selection:text-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SLTDS Platform Super-Admin' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ mobileMenuOpen: false }" class="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Top SLTDS Announcement / Status Bar -->
    <div class="bg-sltds-endeavour py-2 px-4 text-center text-xs font-semibold text-white tracking-wide shadow-xs border-b border-sltds-sky/30">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] uppercase font-black bg-sltds-green text-white shadow-xs">SLT DIGITAL SERVICES</span>
                <span class="hidden sm:inline">Platform Super-Admin Central Command</span>
            </div>
            <div class="text-[11px] font-mono text-sltds-sky">
                Confidential Staff Control Panel
            </div>
        </div>
    </div>

    <!-- Super Admin Header Bar -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 group">
                        <img src="/images/sltmobitel.png" alt="SLT Digital Services" class="h-8 w-auto object-contain group-hover:scale-105 transition-transform">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase bg-blue-50 text-sltds-endeavour border border-sltds-sky/30">SUPER-ADMIN</span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 text-xs font-bold text-slate-700">
                    <a href="{{ route('superadmin.dashboard') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('superadmin.dashboard') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-200' : 'hover:text-sltds-endeavour hover:bg-slate-100' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('superadmin.tenants') }}" class="px-3.5 py-2 rounded-xl transition-all {{ request()->routeIs('superadmin.tenants*') && !request()->routeIs('superadmin.tenants.create') ? 'text-sltds-endeavour font-black bg-blue-50 border border-blue-200' : 'hover:text-sltds-endeavour hover:bg-slate-100' }}">
                        Tenant Directory
                    </a>
                    <a href="{{ route('superadmin.tenants.create') }}" class="px-3.5 py-2 rounded-xl text-sltds-endeavour bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-all font-extrabold">
                        + Provision Tenant
                    </a>
                    <a href="{{ route('parent.home') }}" target="_blank" class="px-3.5 py-2 rounded-xl text-slate-500 hover:text-sltds-endeavour transition-colors">
                        Parent Site &rarr;
                    </a>
                </nav>

                <!-- Right Profile & Mobile Toggle -->
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <div class="text-right hidden sm:block">
                        <div class="text-slate-900 font-bold">{{ Auth::user()->name ?? 'Platform Staff' }}</div>
                        <div class="text-slate-500 text-[10px] font-mono">{{ Auth::user()->email ?? 'superadmin@sltdigital.com' }}</div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-colors">
                            Sign Out
                        </button>
                    </form>

                    <!-- Mobile Menu Toggle Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Navigation Drawer -->
            <div x-show="mobileMenuOpen" x-cloak class="md:hidden py-3 border-t border-slate-100 space-y-2 text-xs font-bold">
                <a href="{{ route('superadmin.dashboard') }}" class="block px-4 py-2.5 rounded-xl text-slate-900 hover:bg-slate-100">
                    Dashboard Overview
                </a>
                <a href="{{ route('superadmin.tenants') }}" class="block px-4 py-2.5 rounded-xl text-slate-900 hover:bg-slate-100">
                    Tenant Directory
                </a>
                <a href="{{ route('superadmin.tenants.create') }}" class="block px-4 py-2.5 rounded-xl text-sltds-endeavour bg-blue-50 font-extrabold">
                    + Provision New Tenant
                </a>
                <a href="{{ route('parent.home') }}" target="_blank" class="block px-4 py-2.5 rounded-xl text-slate-600 hover:bg-slate-100">
                    Parent Marketing Site ↗
                </a>
                <div class="pt-2 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-left font-bold text-rose-700 bg-rose-50 hover:bg-rose-100">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </header>

    <!-- Global Status, Error & Validation Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-3">
        @if(session('status'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-xs font-bold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-black text-sm cursor-pointer">✕</button>
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
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-black text-sm cursor-pointer">✕</button>
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

    <!-- Main Content Slot -->
    <main class="grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </main>

    <!-- Super Admin Footer -->
    <footer class="bg-slate-950 text-slate-400 py-8 border-t border-slate-800 text-xs text-center">
        <div class="max-w-7xl mx-auto px-4 space-y-2">
            <div class="font-bold text-white">SLT Digital Services (Pvt) Ltd</div>
            <div class="text-slate-500">Platform Staff Super-Admin Control Panel &bull; Confidential Internal System</div>
        </div>
    </footer>

</body>
</html>
