<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
    'reference', 'compte_id', 'contact_id', 'delegue_id', 'date_reclamation',
    'priorite', 'type', 'categorie', 'sous_categorie', 'produit_id', 'specimen_id', 'mp_id',
    'description', 'analyse', 'reponse', 'date_reponse', 'responsable_id', 'statut',
    'date_cloture', 'created_by', 'updated_by', 'est_non_conformite', 'besoin_action_amelioration'
];

public function produit()
{
    return $this->belongsTo(Product::class, 'produit_id');
}

public function specimen()
{
    return $this->belongsTo(Bss::class, 'specimen_id');
}

public function mp()
{
    return $this->belongsTo(MpProduct::class, 'mp_id');
}

    protected $casts = [
        'date_reclamation' => 'date',
        'date_echeance' => 'date',
        'date_cloture' => 'date',
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

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function nonConformite()
    {
        return $this->belongsTo(NonConformite::class);
    }

    public function actionAmelioration()
    {
        return $this->belongsTo(ActionAmelioration::class);
    }

    // Helper to get the linked module instance (polymorphic‑like)
    public function getLinkedModuleAttribute()
    {
        if (!$this->module_lie || !$this->module_id) return null;
        $classMap = [
            'product'   => Product::class,
            'specimen'  => Bss::class,
            'mp'        => MpDelivery::class,
            'examen'    => Examen::class,
            'event'     => Event::class,
            'facturation'=> null,
        ];
        $class = $classMap[$this->module_lie] ?? null;
        return $class ? $class::find($this->module_id) : null;
    }
}