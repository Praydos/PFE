<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'etablissement', 'ville_id', 'zone_id', 'adresse',
        'delegue_id', 'status', 'motif_fermeture', 'suspendre_actions',
        'motif_suspension', 'site_web', 'tel_bureau_1', 'tel_bureau_2',
        'tel_mobile', 'fax', 'email', 'parent_compte_id','quartier_id'
    ];

    protected $casts = [
        'suspendre_actions' => 'boolean',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function delegue()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    public function quartier()
    {
        return $this->belongsTo(Quartier::class);
    }

    // public function parent()
    // {
    //     return $this->belongsTo(Compte::class, 'parent_compte_id');
    // }

    // public function enfants()
    // {
    //     return $this->hasMany(Compte::class, 'parent_compte_id');
    // }

    // public function cycles()
    // {
    //     return $this->belongsToMany(Cycle::class, 'compte_cycle');
    // }

    // Accessor for taille (will be calculated later when effectifs are added)
    public function getTailleAttribute()
{
    $currentYear = AnneeScolaire::where('is_active', true)->first();
    if (!$currentYear) {
        return 'Non défini';
    }

    $total = $this->effectifs()
        ->where('annee_scolaire_id', $currentYear->id)
        ->sum('effectif_valide');

    if ($total < 250) return 'Petit';
    if ($total < 500) return 'Moyen';
    if ($total < 1000) return 'Grand';
    return 'Très Grand';
}


    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'compte_contact');
    }

    public function effectifs()
    {
        return $this->hasMany(Effectif::class);
    }


    
}
