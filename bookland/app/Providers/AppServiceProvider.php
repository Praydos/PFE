<?php

namespace App\Providers;

use App\Models\MpDelivery;
use App\Observers\MpDeliveryObserver;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        MpDelivery::observe(MpDeliveryObserver::class);
    }
}
