<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantProvisioningService;
use App\Services\TenantResolver;

class TenantRegistrationController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        return view('auth.register-business');
    }

    public function register(Request $request, TenantProvisioningService $provisioningService)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:63', 'unique:tenants,slug', 'alpha_dash'],
            'subscription_plan' => ['required', 'string', 'in:starter,pro,enterprise'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'unique:users,email'],
            'owner_phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $result = $provisioningService->provisionTenant($validated);
        $tenant = $result['tenant'];
        $owner = $result['owner'];

        Auth::login($owner);
        $request->session()->regenerate();
        TenantResolver::setActiveTenantContext($tenant);

        return redirect()->route('admin.dashboard')->with('status', "Welcome to your new venue portal! '{$tenant->name}' has been created with starter courts & bookable slots.");
    }
}
