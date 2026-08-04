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
            if (!$model->tenant_id) {
                $tenant = \App\Services\TenantResolver::getActiveTenantModel();
                if ($tenant) {
                    $model->tenant_id = $tenant->id;
                }
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = \App\Services\TenantResolver::getActiveTenantModel();
            if ($tenant) {
                $builder->where($builder->getQuery()->from . '.tenant_id', '=', $tenant->id);
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
