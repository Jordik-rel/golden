<?php

namespace App\Providers;

use App\Services\QuantityServices;
use Illuminate\Support\ServiceProvider;

class QuantityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(QuantityServices::class, function($app){
            return new QuantityServices();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
