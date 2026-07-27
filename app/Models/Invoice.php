<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'booking_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'paid_at',
        'payment_method',
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'price_breakdown',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'paid_at' => 'datetime',
        'subtotal_amount' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'total_amount' => 'float',
        'price_breakdown' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
