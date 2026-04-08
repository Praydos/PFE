<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bss extends Model
{
    use HasFactory;

    protected $table = 'bsses';

    protected $fillable = [
        'numero', 'compte_id', 'contact_id', 'delegate_id', 'annee_scolaire_id',
        'date_bss', 'date_livraison_prevue', 'moyen_contact',
        'recupere_par_type', 'recupere_par_nom', 'statut', 'motif_refus',
        'is_validated_by_rbo', 'validated_at', 'validated_by',
        'feedback', 'controle_document', 'date_feedback'
    ];

    protected $casts = [
        'date_bss' => 'date',
        'date_livraison_prevue' => 'date',
        'validated_at' => 'datetime',
        'is_validated_by_rbo' => 'boolean',
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

    public function lignes()
    {
        return $this->hasMany(BssLigne::class);
    }
}