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
        'domain',
        'address',
        'phone',
        'email',
        'brand_color',
        'logo_url',
        'theme_settings',
        'subscription_plan',
        'subscription_status',
        'is_active',
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'is_active' => 'boolean',
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
