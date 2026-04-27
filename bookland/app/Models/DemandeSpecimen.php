<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeSpecimen extends Model
{
    use HasFactory;

    protected $table = 'demandes_specimens';

    protected $fillable = [
        'type', 'compte_id', 'contact_id', 'delegue_id', 'annee_scolaire_id',
        'ville_id', 'zone_id', 'date_demande', 'description', 'statut',
        'valide_par', 'date_validation', 'bss_id'
    ];

    protected $casts = [
        'date_demande' => 'date',
        'date_validation' => 'datetime',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function lignes()
{
    return $this->hasMany(DemandeLigne::class, 'demande_id');
}
}