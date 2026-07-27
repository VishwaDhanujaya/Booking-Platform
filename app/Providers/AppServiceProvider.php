<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Contracts\PaymentGatewayInterface;
use App\Services\PaymentGateway\ManualPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, ManualPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', function (User $user) {
            return in_array($user->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']);
        });

        Gate::define('manage-courts', function (User $user) {
            return in_array($user->role, ['owner', 'manager', 'super_admin']);
        });

        Gate::define('manage-staff', function (User $user) {
            return in_array($user->role, ['owner', 'manager', 'super_admin']);
        });

        Gate::define('view-bookings', function (User $user) {
            return in_array($user->role, ['owner', 'manager', 'trainer_staff', 'front_desk', 'super_admin']);
        });
    }
}
