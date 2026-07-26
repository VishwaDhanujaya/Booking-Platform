<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassLedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_pass_id',
        'booking_id',
        'units_in',
        'units_out',
        'units_after',
        'reason',
    ];

    protected $casts = [
        'units_in' => 'integer',
        'units_out' => 'integer',
        'units_after' => 'integer',
    ];

    public function customerPass()
    {
        return $this->belongsTo(CustomerPass::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
