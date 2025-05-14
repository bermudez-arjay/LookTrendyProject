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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Credit::observe(CreditObserver::class);
    }
}
