<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bss extends Model
{
    use HasFactory;

    protected $table = 'bsses';

    protected $fillable = [
        'numero', 'compte_id', 'contact_id', 'moyen_contact', 'delegate_id', 'annee_scolaire_id',
        'source', 'date_bss', 'date_livraison', 'date_recuperation', 'recupere_par_type',
        'recupere_par_contact_id', 'numero_expedition', 'statut', 'validated_by', 'is_active',
        'motif_validation', 'controle', 'feedback_statut', 'feedback_date', 'feedback_contact_id',
        'feedback_moyen', 'observation'
    ];

    protected $casts = [
        'date_bss' => 'date',
        'date_livraison' => 'date',
        'date_recuperation' => 'date',
        'feedback_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function feedbackContact()
    {
        return $this->belongsTo(Contact::class, 'feedback_contact_id');
    }

    public function lignes()
    {
        return $this->hasMany(BssLigne::class);
    }

    public function retours()
    {
        return $this->hasMany(Retour::class);
    }
}