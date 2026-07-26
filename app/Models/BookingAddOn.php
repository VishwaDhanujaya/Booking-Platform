<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAddOn extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'add_on_id',
        'name',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'total_price' => 'float',
        'quantity' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function addOn()
    {
        return $this->belongsTo(AddOn::class);
    }
}
