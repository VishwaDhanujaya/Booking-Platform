<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'sport_category_id',
        'name',
        'type',
        'surface_type',
        'hourly_rate',
        'peak_hourly_rate',
        'buffer_time_minutes',
        'max_capacity',
        'image_url',
        'description',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'float',
        'peak_hourly_rate' => 'float',
        'buffer_time_minutes' => 'integer',
        'max_capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function sportCategory()
    {
        return $this->belongsTo(SportCategory::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function pricingRules()
    {
        return $this->hasMany(PricingRule::class);
    }
}
