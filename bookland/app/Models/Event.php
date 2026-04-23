<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'ville_id', 'zone_id', 'delegue_id', 'annee_scolaire_id',
        'type', 'editeur', 'date_event'
    ];

    protected $casts = [
        'date_event' => 'date',
    ];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'event_contact')
                    ->withPivot('statut')
                    ->withTimestamps();
    }
}