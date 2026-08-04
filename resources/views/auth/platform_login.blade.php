<x-layouts.parent title="BookFlow Platform Admin Sign In | SLTDS Staff">

    <!-- PAGE CANVAS (VADEL Minimalist Dark Stadium Canvas with Liquid Glass Login Card) -->
    <section class="relative bg-slate-950 text-white min-h-[calc(100vh-4.5rem)] flex items-center justify-center py-16 px-4 overflow-hidden">
        
        <!-- Stadium Lights High-Res Background Image -->
        <img src="/images/contact_stadium_lights.png" alt="Sports Complex Stadium Lights" class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105 z-0">
        <div class="absolute inset-0 bg-linear-to-b from-slate-950/60 via-slate-950/40 to-slate-950/80 z-0"></div>

        <div class="relative z-10 max-w-md w-full mx-auto">
            
            <!-- Liquid Glass Specular Login Card Container -->
            <div class="bg-slate-950/80 backdrop-blur-3xl rounded-4xl sm:rounded-5xl p-8 sm:p-10 border border-white/15 shadow-[inset_0_1px_1px_rgba(255,255,255,0.25),0_30px_60px_rgba(0,0,0,0.7)] space-y-6">
                
                <!-- Brand Header -->
                <div class="text-center space-y-3">
                    <span class="text-4xl sm:text-5xl font-black italic tracking-tighter text-white uppercase block text-center drop-shadow-[0_10px_25px_rgba(0,0,0,0.9)]">
                        BOOKFLOW
                    </span>
                    <div class="space-y-1.5">
                        <h1 class="text-xl font-extrabold text-white tracking-tight pt-1">Platform Admin Login</h1>
                        <p class="text-xs text-slate-300 font-normal leading-relaxed">Sign in with your SLT Digital Services staff credentials to manage tenants and platform settings.</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="bg-rose-500/20 backdrop-blur-xl border border-rose-400/40 text-rose-200 p-4 rounded-2xl text-xs space-y-1 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        <strong class="font-black block text-rose-100">Authentication Failed:</strong>
                        @foreach ($errors->all() as $error)
                            <p>&bull; {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-rose-500/20 backdrop-blur-xl border border-rose-400/40 text-rose-200 p-4 rounded-2xl text-xs shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        <strong class="font-black block text-rose-100">Access Restricted:</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-emerald-500/20 backdrop-blur-xl border border-emerald-400/40 text-emerald-200 p-4 rounded-2xl text-xs font-black shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                        ✓ {{ session('status') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.perform') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Staff Email Address</label>
                        <input type="email" name="email" value="{{ old('email', 'superadmin@sltdigital.com') }}" required
                               placeholder="superadmin@sltdigital.com"
                               class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 text-xs font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Password</label>
                        <input type="password" name="password" value="password" required
                               class="w-full px-5 py-3.5 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 text-white placeholder-slate-400 focus:outline-none focus:border-white/60 focus:bg-white/20 text-xs font-medium shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] transition-all">
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-slate-950 border-white/30 accent-white">
                            <span class="text-slate-300 font-medium">Keep staff session active</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-4 px-6 rounded-full font-black text-slate-950 bg-white hover:bg-slate-100 shadow-[0_15px_30px_rgba(255,255,255,0.3)] transition-all hover:scale-[1.02] text-xs active:scale-[0.98]">
                        Sign In to Platform Admin &rarr;
                    </button>
                </form>

                <!-- Staff Credentials Quick Helper Box (Specular Liquid Glass) -->
                <div class="bg-white/5 backdrop-blur-2xl p-4 rounded-2xl border border-white/15 text-xs space-y-1.5 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)]">
                    <span class="font-black text-white uppercase tracking-widest text-[10px] block">SLTDS Staff Demo Credentials</span>
                    <div class="text-[11px] text-slate-300 leading-relaxed font-mono">
                        &bull; <span class="font-bold text-white">Super Admin Email</span>: superadmin@sltdigital.com<br>
                        &bull; <span class="font-bold text-white">Password</span>: password
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 text-center text-xs text-slate-400">
                    Looking for live customer facilities? 
                    <a href="{{ route('parent.customers') }}" class="font-bold text-white hover:underline">View Live Venues &rarr;</a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.parent>
