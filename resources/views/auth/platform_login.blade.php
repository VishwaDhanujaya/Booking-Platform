<x-layouts.parent title="BookFlow Platform Admin Sign In | SLTDS Staff">

    <section class="py-16 bg-slate-50 min-h-[calc(100vh-4rem)] flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-4">
            
            <div class="bg-white rounded-3xl p-8 border-2 border-slate-200/80 shadow-2xl space-y-6">
                
                <!-- Brand Header -->
                <div class="text-center space-y-3">
                    <img src="/images/logo.png" alt="BookFlow" class="h-14 w-auto mx-auto object-contain">
                    <div>
                        <span class="inline-block px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-sltds-endeavour border border-blue-200 mb-1">
                            SLTDS STAFF ONLY
                        </span>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Platform Admin Login</h1>
                        <p class="text-xs text-slate-500 mt-1">Sign in with your SLT Digital Services staff credentials to manage tenants and system metrics.</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs space-y-1">
                        <strong class="font-bold block text-rose-800">Authentication Failed:</strong>
                        @foreach ($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl text-xs">
                        <strong class="font-bold block text-rose-800">Access Restricted:</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-bold">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.perform') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Staff Email Address</label>
                        <input type="email" name="email" value="{{ old('email', 'superadmin@sltdigital.com') }}" required
                               placeholder="superadmin@sltdigital.com"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-sltds-endeavour text-xs text-slate-900 font-medium bg-slate-50 focus:bg-white">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                        </div>
                        <input type="password" name="password" value="password" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:outline-none focus:border-sltds-endeavour text-xs text-slate-900 font-medium bg-slate-50 focus:bg-white">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-sltds-endeavour border-slate-300">
                            <span class="text-slate-600 font-medium">Keep staff session active</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-black text-white bg-sltds-endeavour hover:bg-sltds-sky shadow-lg shadow-sltds-endeavour/20 transition-all text-xs flex items-center justify-center gap-2">
                        Sign In to Platform Admin &rarr;
                    </button>
                </form>

                <!-- Staff Credentials Quick Helper Box -->
                <div class="bg-blue-50/70 p-3.5 rounded-2xl border border-blue-200 text-xs space-y-1">
                    <span class="font-extrabold text-sltds-endeavour uppercase tracking-wider text-[10px] block">SLTDS Staff Credentials</span>
                    <div class="text-[11px] text-slate-600">
                        &bull; <strong>Super Admin Email</strong>: <code>superadmin@sltdigital.com</code><br>
                        &bull; <strong>Password</strong>: <code>password</code>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                    Looking for a specific customer venue site? 
                    <a href="{{ route('parent.customers') }}" class="font-bold text-sltds-endeavour hover:underline">View Live Customer Directory &rarr;</a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.parent>
