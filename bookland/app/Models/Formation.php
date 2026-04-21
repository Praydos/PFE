<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte_id', 'contact_id', 'zone_id', 'ville_id', 'delegue_id',
        'annee_scolaire_id', 'date_demande', 'dates_proposees', 'statut',
        'type', 'cible'
    ];

    protected $casts = [
        'dates_proposees' => 'array',
        'date_demande' => 'array',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}