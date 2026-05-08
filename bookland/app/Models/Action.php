<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $table = 'actions';
    protected $fillable = [
        'objet', 'compte_id', 'delegue_id', 'date_planification', 'heure',
        'duree', 'lieu', 'rappel', 'rappel_avant', 'recurrence_frequence',
        'recurrence_intervalle', 'recurrence_fin', 'parent_action_id',
        'statut', 'date_realisation', 'date_validation', 'valide_par',
        'type', 'module_lie', 'module_id','bss_id',
    ];

    protected $casts = [
        'date_planification' => 'date',
        'date_realisation' => 'datetime',
        'date_validation' => 'datetime',
        'rappel' => 'boolean',
    ];
    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }
    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function parent()
    {
        return $this->belongsTo(Action::class, 'parent_action_id');
    }

    public function enfants()
    {
        return $this->hasMany(Action::class, 'parent_action_id');
    }

    public function lignes()
    {
        return $this->hasMany(ActionLine::class);
    }
    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}