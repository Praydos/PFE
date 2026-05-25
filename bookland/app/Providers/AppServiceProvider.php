<?php

namespace App\Providers;

use App\Models\MpDelivery;
use App\Observers\MpDeliveryObserver;
use App\Models\Action;
use App\Models\ActionAmelioration;
use App\Models\Bss;
use App\Models\DemandeSpecimen;
use App\Models\Event;
use App\Models\Examen;
use App\Models\Formation;
use App\Models\NonConformite;
use App\Models\Reclamation;
use App\Models\Tache;
use App\Observers\NotificationObserver;
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

        // Register NotificationObserver for multiple models
        $modelsToObserve = [
            Tache::class,
            Action::class,
            DemandeSpecimen::class,
            Event::class,
            Examen::class,
            Formation::class,
            Bss::class,
            Reclamation::class,
            NonConformite::class,
            ActionAmelioration::class,
        ];
        foreach ($modelsToObserve as $modelClass) {
            $modelClass::observe(NotificationObserver::class);
        }

        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            activity('auth')
                ->causedBy($event->user)
                ->log('logged_in');
        });

    }
}
