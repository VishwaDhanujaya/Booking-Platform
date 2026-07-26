<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'category',
        'description',
        'price',
        'pricing_type',
        'stock_quantity',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function bookingAddOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }
}
