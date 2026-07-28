<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Temporarily Unavailable | SLT Digital Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-16 h-16 rounded-3xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center mx-auto text-2xl font-black shadow-lg">
            ⚠️
        </div>

        <div class="space-y-2">
            <span class="text-xs font-black uppercase tracking-wider text-amber-400">Status Notice</span>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Facility Temporarily Unavailable</h1>
            <p class="text-xs text-slate-400 leading-relaxed">
                The online court reservation portal for <strong class="text-white">{{ $tenant->name ?? 'this facility' }}</strong> is currently offline for maintenance or subscription updates.
            </p>
        </div>

        <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700 text-left text-xs space-y-2">
            <div class="flex items-center justify-between text-slate-400 font-bold">
                <span>Facility Name:</span>
                <span class="text-white">{{ $tenant->name ?? 'Facility' }}</span>
            </div>
            <div class="flex items-center justify-between text-slate-400 font-bold">
                <span>Support Contact:</span>
                <span class="text-amber-400">{{ $tenant->phone ?? '+94 11 234 5678' }}</span>
            </div>
        </div>

        <div class="pt-4">
            <a href="{{ route('parent.home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs border border-slate-700 transition-colors">
                &larr; Return to SLT Digital Services Directory
            </a>
        </div>
    </div>

</body>
</html>
