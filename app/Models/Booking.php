<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'court_id',
        'user_id',
        'booking_reference',
        'booking_date',
        'start_time',
        'end_time',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'payment_status',
        'payment_method',
        'base_amount',
        'addons_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'price_breakdown',
        'addons',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'addons' => 'array',
        'price_breakdown' => 'array',
        'base_amount' => 'float',
        'addons_amount' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'total_amount' => 'float',
        'cancelled_at' => 'datetime',
    ];

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingAddOns()
    {
        return $this->hasMany(BookingAddOn::class);
    }

    public function creditLedger()
    {
        return $this->hasOne(CreditLedger::class);
    }

    public function passLedgerEntries()
    {
        return $this->hasMany(PassLedgerEntry::class);
    }

    public function noShowRecord()
    {
        return $this->hasOne(NoShowRecord::class);
    }
}
