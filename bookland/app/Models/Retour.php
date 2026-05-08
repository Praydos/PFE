<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retour extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'bss_id', 'date_retour', 'created_by', 'motif'];

    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lignes()
    {
        return $this->belongsToMany(BssLigne::class, 'retour_bss_ligne')
                    ->withPivot('quantite_retournee')
                    ->withTimestamps();
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}