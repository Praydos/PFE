<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonConformite extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'compte_id', 'contact_id', 'delegue_id', 'date_nc',
        'categorie', 'sous_categorie', 'evaluation', 'objet', 'description',
        'statut', 'date_cloture', 'mode_controle', 'description_resultat',
        'action_efficace', 'besoin_action_amelioration', 'responsable_efficacite_id',
        'date_efficacite', 'reclamation_id','module_type', 'module_id'
    ];

    protected $casts = [
        'date_nc' => 'date',
        'date_cloture' => 'date',
        'date_efficacite' => 'date',
        'action_efficace' => 'boolean',
        'besoin_action_amelioration' => 'boolean',
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

    public function responsableEfficacite()
    {
        return $this->belongsTo(User::class, 'responsable_efficacite_id');
    }

    public function reclamation()
    {
        return $this->belongsTo(Reclamation::class);
    }
}