<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'email', 'telephone', 'ville_id',
        'categories', 'civilite', 'fonction', 'cycles'
    ];

    protected $casts = [
        'categories' => 'array',
        'cycles' => 'array',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function comptes()
    {
        return $this->belongsToMany(Compte::class, 'compte_contact');
                    
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_contact')
                    ->withPivot('statut')
                    ->withTimestamps();
    }


   public function compteHistory()
{
    return $this->hasMany(CompteContact::class)
        ->orderByDesc('date_debut');
}


    //is affected by the pivot table
    
}