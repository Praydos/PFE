<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionAmelioration extends Model
{
    use HasFactory;

    protected $table = 'actions_amelioration';

    protected $fillable = [
        'numero', 'compte_id', 'emetteur_id', 'dateAA', 'type', 'origine',
        'analyse_causes', 'sanctions', 'resultats_attendus',
        'verification_mise_en_oeuvre', 'responsable_suivi_id', 'date_suivi',
        'date_efficacite', 'responsable_efficacite_id', 'mode_controle',
        'description_resultat', 'action_efficace', 'besoin_action_amelioration',
        'statut', 'date_cloture'
    ];

    protected $casts = [
        'dateAA' => 'date',
        'date_suivi' => 'date',
        'date_efficacite' => 'date',
        'date_cloture' => 'date',
        'action_efficace' => 'boolean',
        'besoin_action_amelioration' => 'boolean',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function emetteur()
    {
        return $this->belongsTo(Contact::class, 'emetteur_id');
    }

    public function responsableSuivi()
    {
        return $this->belongsTo(User::class, 'responsable_suivi_id');
    }

    public function responsableEfficacite()
    {
        return $this->belongsTo(User::class, 'responsable_efficacite_id');
    }
}