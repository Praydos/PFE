<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory, LogsModelActivity;

    protected $fillable = [
        'compte_id', 'contact_id', 'zone_id', 'ville_id', 'delegue_id',
        'annee_scolaire_id', 'date_demande', 'dates_proposees', 'statut',
        'type', 'cible',
        'rapport_titre', 'rapport_description', 'date_validation', 'valide_par',
    ];

    protected $casts = [
        'dates_proposees' => 'array',
        'date_demande' => 'array',
        'date_validation' => 'datetime',
    ];

    public function isValidated(): bool
    {
        return $this->statut === 'validee';
    }

    public function canBeCompletedByDelegate(): bool
    {
        return in_array($this->statut, ['demande', 'planifiee', 'reportee'], true);
    }

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

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}