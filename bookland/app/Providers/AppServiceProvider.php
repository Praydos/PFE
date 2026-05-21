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

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log('logged_in');
        });

    }
}
