@php
    $tenantModel = \App\Services\TenantResolver::getActiveTenantModel();
    $tenantName = $tenantModel ? $tenantModel->name : 'Colombo Courts Club';
    $brandColor = $tenantModel ? ($tenantModel->brand_color ?? '#0056A2') : '#0056A2';
    $logoInitial = strtoupper(substr($tenantName, 0, 1));
    $logoUrl = $tenantModel ? $tenantModel->logo_url : null;

    // Dynamic venue-specific demo accounts
    $ownerUser = $tenantModel ? \App\Models\User::where('tenant_id', $tenantModel->id)->whereIn('role', ['owner', 'manager'])->first() : null;
    $customerUser = $tenantModel ? \App\Models\User::where('tenant_id', $tenantModel->id)->where('role', 'customer')->first() : null;

    $defaultEmail = old('email', $customerUser ? $customerUser->email : ($ownerUser ? $ownerUser->email : 'kavinda@example.com'));
@endphp
<x-layouts.app title="Sign In | {{ $tenantName }}">

    <!-- Page Banner (VADEL Liquid Glass Aesthetic - Flush Top Alignment) -->
    <div class="px-2 sm:px-3 pb-2 sm:pb-3 pt-0 bg-slate-50">
        <section class="relative rounded-3xl sm:rounded-4xl overflow-hidden py-10 sm:py-12 p-6 sm:p-8 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)] bg-slate-950 text-white text-center">
            <div class="absolute inset-0 bg-linear-to-r from-slate-950 via-slate-900/90 to-slate-950 z-0"></div>
            <div class="relative z-10 max-w-3xl mx-auto space-y-3">
                <span class="text-xs font-black text-slate-300 uppercase tracking-widest block">{{ $tenantName }} Portal</span>
                <h1 class="text-3xl sm:text-5xl font-black italic tracking-tighter text-white uppercase drop-shadow-lg">Account Sign In</h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal">Access your court bookings, digital player passes, and account wallet credits.</p>
            </div>
        </section>
    </div>

    <!-- Main Sign In Form Section -->
    <section class="py-12 bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-4">
            
            <!-- Liquid Glass Login Card Container -->
            <div class="bg-white rounded-4xl p-8 sm:p-10 border border-slate-200/90 shadow-xl shadow-slate-200/50 space-y-6">
                
                <!-- Venue Logo & Title -->
                <div class="text-center space-y-3">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $tenantName }}" class="h-12 w-auto mx-auto object-contain">
                    @else
                        <div class="w-12 h-12 rounded-2xl text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md" style="background-color: {{ $brandColor }};">
                            {{ $logoInitial }}
                        </div>
                    @endif
                    <div class="space-y-1">
                        <h2 class="text-2xl font-black italic tracking-tighter text-slate-950 uppercase">Welcome Back</h2>
                        <p class="text-xs text-slate-500 font-medium">Sign in to manage reservations, passes & wallet credits.</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs space-y-1 font-medium">
                        <strong class="font-black block text-rose-800">Authentication Failed:</strong>
                        @foreach ($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs font-medium">
                        <strong class="font-black block text-rose-800">Access Restricted:</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-bold">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.perform', request()->only('tenant')) }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Email Address</label>
                        <input type="email" name="email" value="{{ $defaultEmail }}" required class="w-full px-5 py-3.5 rounded-full border border-slate-300 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 text-xs text-slate-950 font-bold shadow-xs">
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Password</label>
                            <a href="#" onclick="alert('Demo mode: Password reset logged to console.')" class="text-[11px] font-bold text-slate-500 hover:text-slate-950">Forgot password?</a>
                        </div>
                        <input type="password" name="password" value="password" required class="w-full px-5 py-3.5 rounded-full border border-slate-300 focus:ring-2 focus:ring-slate-950 focus:border-slate-950 text-xs text-slate-950 font-bold shadow-xs">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-slate-950 border-slate-300">
                            <span class="text-slate-600 font-bold">Remember me for 30 days</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 rounded-full font-black text-white shadow-[inset_0_1px_1px_rgba(255,255,255,0.4),0_10px_20px_-5px_rgba(0,0,0,0.3)] transition-all hover:scale-105 active:scale-[0.98] text-xs flex items-center justify-center gap-2" style="background-color: {{ $brandColor }};">
                        Sign In to Account
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Demo Credentials Quick Helper Box -->
                <div class="bg-slate-950 text-white p-5 rounded-3xl border border-slate-900 text-xs space-y-2 shadow-md">
                    <span class="font-black text-slate-300 uppercase tracking-widest text-[10px] block">Demo Accounts for {{ $tenantName }} (Password: password)</span>
                    <div class="grid grid-cols-1 gap-1 text-[11px] text-slate-300 font-mono">
                        @if($ownerUser)
                            <div>&bull; <strong class="text-white font-bold">Staff / Manager</strong>: {{ $ownerUser->email }}</div>
                        @endif
                        @if($customerUser)
                            <div>&bull; <strong class="text-white font-bold">Player / Customer</strong>: {{ $customerUser->email }}</div>
                        @endif
                        <div>&bull; <strong class="text-white font-bold">Super-Admin</strong>: superadmin@sltdigital.com</div>
                    </div>
                </div>

                <div class="pt-2 text-center text-xs text-slate-500 font-medium">
                    Don't have an account yet? 
                    <a href="{{ route('register') }}" class="font-black text-slate-950 hover:underline">Create Account &rarr;</a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
