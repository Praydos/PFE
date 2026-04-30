<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte_id', 'contact_id', 'delegue_id', 'annee_scolaire_id',
        'langue', 'organisme', 'titre', 'abreviation', 'niveau_cecr',
        'niveaux_scolaires', 'date_demande', 'date_examen', 'statut',
        'description', 'observations',
    ];

    protected $casts = [
        'niveaux_scolaires' => 'array',
        'date_demande' => 'date',
        'date_examen' => 'date',
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

    public function epreuves()
    {
        return $this->hasMany(Epreuve::class);
    }
}