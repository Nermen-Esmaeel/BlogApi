<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\UserActionsObserver;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
       //Registering the observer
    public function boot(): void
    {
    
        User::observe(UserActionsObserver::class);
    }
}
