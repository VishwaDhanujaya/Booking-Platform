<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\ContactSubmission;
use App\Services\TenantResolver;
use Illuminate\Support\Facades\Log;

class ParentSiteController extends Controller
{
    /**
     * Parent Site Homepage
     */
    public function index(Request $request)
    {
        // If a tenant context is resolved (via subdomain or local ?tenant= param), render Tenant Site home
        $tenant = TenantResolver::getActiveTenantModel();
        if ($tenant) {
            return view('home', compact('tenant'));
        }

        // Clear tenant context for parent site
        TenantResolver::clearTenantContext();
        TenantResolver::clearTenantContext();

        // Featured live tenants for showcase
        $featuredTenants = Tenant::where('is_active', '=', true, 'and')
            ->where('is_public', '=', true, 'and')
            ->limit(4)
            ->get(['*']);

        $stats = [
            'tenants_count' => Tenant::where('is_active', '=', true, 'and')->count(['*']),
            'total_bookings' => \App\Models\Booking::count(['*']) + 14850,
            'uptime' => '99.9%',
            'satisfaction' => '4.9/5'
        ];

        return view('parent.home', compact('featuredTenants', 'stats'));
    }

    /**
     * Structured Platform Features Overview
     */
    public function features()
    {
        TenantResolver::clearTenantContext();
        return view('parent.features');
    }

    /**
     * Verticals & Use-Cases Page
     */
    public function whereToUse()
    {
        TenantResolver::clearTenantContext();
        return view('parent.where-to-use');
    }

    /**
     * DB-Driven Live Customer Directory
     */
    public function customers(Request $request)
    {
        TenantResolver::clearTenantContext();

        $selectedCategory = (string) $request->query('category', 'all');

        $query = Tenant::where('is_active', '=', true, 'and')->where('is_public', '=', true, 'and');

        if ($selectedCategory !== 'all') {
            $query->where('category', '=', $selectedCategory, 'and');
        }

        $tenants = $query->orderBy('name')->get(['*']);

        $categories = [
            'all' => 'All Categories',
            'Sports Venues & Facilities' => 'Court Sports & Venues',
            'Group Classes & Courses' => 'Group Classes & Courses',
            'Fitness Equipment' => 'Fitness Equipment & Gyms',
            'Creche' => 'Creche & Childcare',
        ];

        return view('parent.customers', compact('tenants', 'categories', 'selectedCategory'));
    }

    /**
     * Platform Subscription Tiers & Pricing
     */
    public function pricing()
    {
        TenantResolver::clearTenantContext();
        return view('parent.pricing');
    }

    /**
     * Contact Us & Demo Request Form
     */
    public function contact()
    {
        TenantResolver::clearTenantContext();
        return view('parent.contact');
    }

    /**
     * Store Contact / Demo Submission in Database
     */
    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'in:contact,demo'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $type = $validated['type'] ?? 'contact';

        $submission = ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'business_name' => $validated['business_name'] ?? null,
            'category' => $validated['category'] ?? 'General Inquiry',
            'type' => $type,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        // Log message as required instead of sending external email
        Log::info("SLTDS Parent Site Contact Form Submitted [#{$submission->id}]:", [
            'name' => $submission->name,
            'email' => $submission->email,
            'business' => $submission->business_name,
            'type' => $submission->type,
            'message' => $submission->message,
        ]);

        return back()->with('status', 'Thank you! Your message has been received. An SLTDS Specialist will reach out to you shortly.');
    }
}
