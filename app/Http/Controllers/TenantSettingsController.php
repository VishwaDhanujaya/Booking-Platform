<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TenantResolver;
use App\Models\Tenant;

class TenantSettingsController extends Controller
{
    /**
     * Show the Site Settings & Customization Form.
     */
    public function edit()
    {
        $tenant = TenantResolver::getActiveTenantModel();

        if (!$tenant) {
            return redirect()->route('admin.dashboard')->with('error', 'No active tenant context found.');
        }

        $categories = [
            'Sports Venues & Facilities',
            'Group Classes & Courses',
            'Fitness Equipment',
            'Creche',
            'Other',
        ];

        return view('admin.settings', compact('tenant', 'categories'));
    }

    /**
     * Update Site Settings & Branding in Database.
     */
    public function update(Request $request)
    {
        $tenant = TenantResolver::getActiveTenantModel();

        if (!$tenant) {
            return redirect()->route('admin.dashboard')->with('error', 'No active tenant context found.');
        }

        $validated = $request->validate([
            // 1. Branding Files & Colors
            'logo_file' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp,svg', 'max:5120'],
            'logo_url' => ['nullable', 'string', 'max:1000'],
            'brand_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'favicon_file' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,svg', 'max:2048'],
            'favicon_url' => ['nullable', 'string', 'max:1000'],

            // 2. Business Info & Contact
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'opening_hours' => ['nullable', 'string', 'max:500'],

            // 3. Homepage Hero Banner Content
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_subheading' => ['nullable', 'string', 'max:1000'],
            'hero_image_file' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:8192'],
            'hero_image_url' => ['nullable', 'string', 'max:1000'],
            'hero_highlights' => ['nullable', 'array'],

            // 4. Notices & Promo Links Banner
            'notices' => ['nullable', 'array'],
            'notices.*.title' => ['nullable', 'string', 'max:255'],
            'notices.*.link' => ['nullable', 'string', 'max:500'],
            'notices.*.icon' => ['nullable', 'string', 'max:100'],

            // 5. Navigation Visibility Toggles
            'nav_settings' => ['nullable', 'array'],

            // 6 & 7. Directory Category & Custom Domain
            'category' => ['required', 'string', 'max:255'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);

        // File Uploads Handling
        $uploadDir = public_path('uploads/tenants/' . $tenant->slug);
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $logoUrl = $validated['logo_url'] ?? $tenant->logo_url;
        if ($request->boolean('remove_logo')) {
            $logoUrl = null;
        } elseif ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $logoUrl = '/uploads/tenants/' . $tenant->slug . '/' . $filename;
        }

        $faviconUrl = $validated['favicon_url'] ?? $tenant->favicon_url;
        if ($request->boolean('remove_favicon')) {
            $faviconUrl = null;
        } elseif ($request->hasFile('favicon_file')) {
            $file = $request->file('favicon_file');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $faviconUrl = '/uploads/tenants/' . $tenant->slug . '/' . $filename;
        }

        $heroImageUrl = $validated['hero_image_url'] ?? $tenant->hero_image_url;
        if ($request->boolean('remove_hero_image')) {
            $heroImageUrl = null;
        } elseif ($request->hasFile('hero_image_file')) {
            $file = $request->file('hero_image_file');
            $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $heroImageUrl = '/uploads/tenants/' . $tenant->slug . '/' . $filename;
        }

        // Process Repeatable Notices Strip
        $notices = [];
        if (!empty($validated['notices'])) {
            foreach ($validated['notices'] as $n) {
                if (!empty($n['title']) && trim($n['title']) !== '') {
                    $notices[] = [
                        'title' => trim($n['title']),
                        'link' => !empty($n['link']) ? trim($n['link']) : '#',
                        'icon' => !empty($n['icon']) ? trim($n['icon']) : 'info',
                    ];
                }
            }
        }

        // Process Navigation Visibility Settings
        $navSettings = [
            'show_courts' => (bool) $request->boolean('nav_settings.show_courts', true),
            'show_pricing' => (bool) $request->boolean('nav_settings.show_pricing', true),
            'show_passes' => (bool) $request->boolean('nav_settings.show_passes', true),
            'show_rules' => (bool) $request->boolean('nav_settings.show_rules', true),
            'show_contact' => (bool) $request->boolean('nav_settings.show_contact', true),
        ];

        // Persist Tenant Model Changes
        $tenant->update([
            'name' => $validated['name'],
            'logo_url' => $logoUrl,
            'brand_color' => $validated['brand_color'] ?? '#0056A2',
            'favicon_url' => $faviconUrl,
            'tagline' => $validated['tagline'] ?? null,
            'description' => $validated['description'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'opening_hours' => $validated['opening_hours'] ?? null,
            'hero_headline' => $validated['hero_headline'] ?? null,
            'hero_subheading' => $validated['hero_subheading'] ?? null,
            'hero_image_url' => $heroImageUrl,
            'hero_highlights' => $validated['hero_highlights'] ?? null,
            'notices' => $notices,
            'nav_settings' => $navSettings,
            'category' => $validated['category'],
            'custom_domain' => $validated['custom_domain'] ?? null,
        ]);

        // Keep active session context fresh
        TenantResolver::setActiveTenantContext($tenant);

        return back()->with('status', "Site Settings & Branding for '{$tenant->name}' updated successfully! Changes are live immediately.");
    }
}
