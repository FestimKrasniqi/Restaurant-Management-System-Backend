<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        

        Gate::define('manage-menu', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-staff', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-tables',function(User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-review',function(User $user){
        return $user->role === 'user';
        });

        Gate::define('manage-supplier',function(User $user){
            return $user->role === 'admin';
            });

        Gate::define('create-order',function(User $user) {
            return $user->role === 'user';
        } );

        Gate::define('manage-order', function (User $user, Order $order) {
            return $user->id === $order->user_id;
        });
    }
}
