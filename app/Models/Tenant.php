<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'tagline',
        'description',
        'location',
        'domain',
        'custom_domain',
        'address',
        'phone',
        'email',
        'brand_color',
        'logo_url',
        'favicon_url',
        'opening_hours',
        'hero_headline',
        'hero_subheading',
        'hero_image_url',
        'hero_highlights',
        'theme_settings',
        'notices',
        'nav_settings',
        'subscription_plan',
        'subscription_status',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'hero_highlights' => 'array',
        'notices' => 'array',
        'nav_settings' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function sportCategories()
    {
        return $this->hasMany(SportCategory::class);
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }

    public function addOns()
    {
        return $this->hasMany(AddOn::class);
    }

    public function pricingRules()
    {
        return $this->hasMany(PricingRule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function customerPasses()
    {
        return $this->hasMany(CustomerPass::class);
    }

    public function creditLedgers()
    {
        return $this->hasMany(CreditLedger::class);
    }
}
