<x-layouts.app title="Sign Up | Colombo Courts Club">

    <section class="py-16 bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-4">
            
            <div class="bg-white rounded-3xl p-8 border border-slate-200/90 shadow-2xl space-y-6">
                
                <!-- Header -->
                <div class="text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md">
                        C
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900">Create Player Account</h1>
                    <p class="text-xs text-slate-500">Join Colombo Courts Club for instant court locking & member perks.</p>
                </div>

                <!-- Form -->
                <form action="{{ route('register.perform') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="name" value="Kavinda Perera" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                        <input type="email" name="email" value="kavinda@example.com" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone</label>
                        <input type="tel" name="phone" value="+94 77 123 4567" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                        <input type="password" name="password" value="password123" required class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-sm text-slate-900 font-medium">
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-lg shadow-brand-600/20 transition-all hover:scale-[1.01] text-sm flex items-center justify-center gap-2">
                        Create Account & Join
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </form>

                <div class="pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:underline">Sign In &rarr;</a>
                </div>

            </div>

        </div>
    </section>

</x-layouts.app>
