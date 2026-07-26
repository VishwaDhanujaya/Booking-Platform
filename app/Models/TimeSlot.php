<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'court_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'block_reason',
        'is_peak',
        'price',
        'booked_by_name',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_peak' => 'boolean',
        'price' => 'float',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }
}
