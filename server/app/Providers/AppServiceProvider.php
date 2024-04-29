<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
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
    }
}
