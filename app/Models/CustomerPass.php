<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPass extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'pass_name',
        'total_units',
        'remaining_units',
        'price_paid',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'total_units' => 'integer',
        'remaining_units' => 'integer',
        'price_paid' => 'float',
        'expires_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passLedgerEntries()
    {
        return $this->hasMany(PassLedgerEntry::class);
    }
}
