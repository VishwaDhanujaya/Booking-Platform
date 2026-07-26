<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingRule extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'court_id',
        'sport_category_id',
        'name',
        'rule_type',
        'discount_type',
        'adjustment_type',
        'adjustment_value',
        'start_time',
        'end_time',
        'days_of_week',
        'start_date',
        'end_date',
        'min_slots',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'adjustment_value' => 'float',
        'min_slots' => 'integer',
        'priority' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function sportCategory()
    {
        return $this->belongsTo(SportCategory::class);
    }
}
