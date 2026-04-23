<?php

namespace App\Observers;

use App\Models\Bss;
use App\Models\Action;

class BssObserver
{
    /**
     * Handle the Bss "created" event.
     */
    public function created(Bss $bss)
{
    if ($bss->statut !== 'refuse') {
        Action::create([
            'objet' => 'Livraison BSS ' . $bss->numero,
            'compte_id' => $bss->compte_id,
            'delegue_id' => $bss->delegate_id,
            'date_planification' => $bss->date_livraison_prevue ?? $bss->created_at,
            'type' => 'commercial',
            'module_lie' => 'bss',
            'module_id' => $bss->id,
            'statut' => 'planifie',
        ]);
    }
}

    /**
     * Handle the Bss "updated" event.
     */
    public function updated(Bss $bss): void
    {
        //
    }

    /**
     * Handle the Bss "deleted" event.
     */
    public function deleted(Bss $bss): void
    {
        //
    }

    /**
     * Handle the Bss "restored" event.
     */
    public function restored(Bss $bss): void
    {
        //
    }

    /**
     * Handle the Bss "force deleted" event.
     */
    public function forceDeleted(Bss $bss): void
    {
        //
    }
}
