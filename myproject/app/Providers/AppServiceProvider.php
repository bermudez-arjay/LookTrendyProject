<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\CreditObserver;
use App\Models\Credit;




class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
        if ($this->app->environment('local')) {
        $this->app->register(\KitLoong\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Credit::observe(CreditObserver::class);
    }
}
