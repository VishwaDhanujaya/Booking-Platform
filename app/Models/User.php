<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_super_admin',
        'is_banned',
        'banned_until',
        'ban_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_until' => 'datetime',
        'is_banned' => 'boolean',
        'is_super_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function isSuperAdmin(): bool
    {
        return (bool) ($this->is_super_admin || $this->role === 'platform_admin' || $this->role === 'super_admin');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function creditLedgers()
    {
        return $this->hasMany(CreditLedger::class);
    }

    public function passes()
    {
        return $this->hasMany(CustomerPass::class);
    }

    public function noShowRecords()
    {
        return $this->hasMany(NoShowRecord::class);
    }

    public function getCreditBalanceAttribute(): float
    {
        $in = $this->creditLedgers()->sum('amount_in');
        $out = $this->creditLedgers()->sum('amount_out');
        return (float) ($in - $out);
    }

    public function hasRole(string $roleSlug): bool
    {
        if ($this->role === $roleSlug || $this->isSuperAdmin()) {
            return true;
        }

        return $this->roles->contains('slug', $roleSlug);
    }
}
