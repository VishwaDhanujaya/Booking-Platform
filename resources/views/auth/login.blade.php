@php
    $tenantModel = \App\Services\TenantResolver::getActiveTenantModel();
    $tenantName = $tenantModel ? $tenantModel->name : 'Colombo Courts Club';
    $brandColor = $tenantModel ? ($tenantModel->brand_color ?? '#0284c7') : '#0284c7';
    $logoInitial = strtoupper(substr($tenantName, 0, 1));
@endphp
<x-layouts.app title="Sign In | {{ $tenantName }}">

    <section class="py-16 bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-4">
            
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-2xl space-y-6">
                
                <!-- Header -->
                <div class="text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md" style="background-color: {{ $brandColor }};">
                        {{ $logoInitial }}
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Welcome Back</h1>
                    <p class="text-xs text-slate-500">Sign in to {{ $tenantName }} to manage reservations, passes & credits.</p>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs space-y-1">
                        <strong class="font-bold block text-rose-800">Authentication Failed:</strong>
                        @foreach ($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs">
                        <strong class="font-bold block text-rose-800">Access Restricted:</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.perform') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', 'kavinda@example.com') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                            <a href="#" onclick="alert('Demo mode: Password reset logged to console.')" class="text-[11px] font-semibold text-brand-600 hover:underline">Forgot password?</a>
                        </div>
                        <input type="password" name="password" value="password" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-brand-600 focus:ring-brand-500 border-slate-300">
                            <span class="text-slate-600 font-medium">Remember me for 30 days</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-bold text-white shadow-lg transition-all hover:scale-[1.01] text-sm flex items-center justify-center gap-2" style="background-color: {{ $brandColor }};">
                        Sign In to Account
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <!-- Demo Credentials Quick Helper Box -->
                <div class="bg-slate-100/80 p-3.5 rounded-2xl border border-slate-200/80 text-xs space-y-1">
                    <span class="font-extrabold text-slate-800 uppercase tracking-wider text-[10px] block">Seeded Demo Credentials (Password: password)</span>
                    <div class="grid grid-cols-1 gap-1 text-[11px] text-slate-600">
                        <div>&bull; <strong>Customer</strong>: <code>kavinda@example.com</code></div>
                        <div>&bull; <strong>Manager / Staff</strong>: <code>admin@colombocourts.lk</code></div>
                        <div>&bull; <strong>Apex Tenant (Kandy)</strong>: <code>owner@apexsports.lk</code></div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                    Don't have an account yet? 
                    <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:underline">Create a Member Account &rarr;</a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
