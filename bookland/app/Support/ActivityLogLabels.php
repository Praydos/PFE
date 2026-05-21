<?php

namespace App\Support;

class ActivityLogLabels
{
    public static function subjectLabel(?string $subjectType): string
    {
        if (! $subjectType) {
            return '';
        }

        $class = class_basename($subjectType);

        return match ($class) {
            'Adoption' => 'adoption',
            'DemandeSpecimen' => 'demande spécimen',
            'DemandeLigne' => 'ligne spécimen',
            'Reclamation' => 'réclamation',
            'ActionAmelioration' => 'action amélioration',
            'Action' => 'action commerciale',
            'Tache' => 'tâche',
            'Event' => 'événement',
            'Formation' => 'formation',
            'Bss' => 'BSS',
            'BssLigne' => 'ligne BSS',
            'Examen' => 'examen',
            'Epreuve' => 'épreuve',
            'NonConformite' => 'non-conformité',
            'Retour' => 'retour',
            'RetourLigne' => 'ligne retour',
            'Product' => 'produit',
            'MpProduct' => 'produit MP',
            'MpDelivery' => 'livraison MP',
            'Effectif' => 'effectif',
            'Consignation' => 'consignation',
            'Compte' => 'compte',
            'Contact' => 'contact',
            'CompteContact' => 'liaison compte-contact',
            'User' => 'utilisateur',
            'Zone' => 'zone',
            'Ville' => 'ville',
            'Quartier' => 'quartier',
            'Vacation' => 'vacation',
            'AnneeScolaire' => 'année scolaire',
            'ActionLine' => 'ligne action',
            default => strtolower($class),
        };
    }

    public static function eventLabel(string $description): string
    {
        return match ($description) {
            'created' => 'A créé',
            'updated' => 'A modifié',
            'deleted' => 'A supprimé',
            'restored' => 'A restauré',
            default => $description,
        };
    }
}
