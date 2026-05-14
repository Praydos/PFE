<?php

namespace App\Services;

use App\Models\Action;
use App\Models\ActionLine;
use App\Models\MpDelivery;

class MpDeliveryCommercialActionCreator
{
    public function create(MpDelivery $delivery): void
    {
        $delivery->loadMissing(['compte.zone', 'compte.ville', 'mpProduct', 'contact']);

        $compte = $delivery->compte;
        if (! $compte) {
            return;
        }

        $lieu = 'Zone: '.($compte->zone->name ?? 'N/A').' - Ville: '.($compte->ville->nom ?? 'N/A');

        $action = Action::create([
            'objet' => 'Livraison MP '.$delivery->numero,
            'compte_id' => $delivery->compte_id,
            'delegue_id' => $delivery->delegate_id,
            'date_planification' => $delivery->date_delivery,
            'statut' => 'planifie',
            'type' => 'commercial',
            'module_lie' => 'mp_delivery',
            'module_id' => $delivery->id,
            'mp_delivery_id' => $delivery->id,
            'lieu' => $lieu,
        ]);

        $product = $delivery->mpProduct;
        $desc = $product
            ? ($product->nom.' ('.$product->code_article.')')
            : 'Matériel pédagogique';

        $line = ActionLine::create([
            'action_id' => $action->id,
            'categorie' => 'Correspondance',
            'action_type' => 'Livraison MP',
            'moyen' => 'Visite',
            'description' => $desc,
        ]);

        if ($delivery->contact_id) {
            $line->contacts()->sync([$delivery->contact_id]);
        }
    }
}
