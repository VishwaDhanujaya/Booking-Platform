<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static void creating(\Closure|string $callback)
 * @method static void addGlobalScope(string $scope, \Closure $implementation)
 */
trait BelongsToTenant
{
    /**
     * Boot the BelongsToTenant trait for an Eloquent model.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function ($model) {
            if (!$model->tenant_id && session()->has('tenant_id')) {
                $model->tenant_id = session('tenant_id');
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (session()->has('tenant_id')) {
                $builder->where($builder->getQuery()->from . '.tenant_id', '=', session('tenant_id'));
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
