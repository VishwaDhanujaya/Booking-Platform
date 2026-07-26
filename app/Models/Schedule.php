<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'court_id',
        'title',
        'type',
        'day_of_week',
        'start_time',
        'end_time',
        'specific_date',
        'start_date',
        'end_date',
        'is_available',
        'is_peak',
        'override_rate',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_peak' => 'boolean',
        'override_rate' => 'float',
        'specific_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
