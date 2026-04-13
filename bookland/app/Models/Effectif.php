<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Effectif extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte_id', 'annee_scolaire_id', 'niveau', 'cycle', 'massar',
        'source_1', 'nombre_classes_1',
        'source_2', 'nombre_classes_2',
        'source_3', 'nombre_classes_3',
        'effectif_valide', 'valide_par', 'is_validated'
    ];

    protected $casts = [
        'is_validated' => 'boolean',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function sourceContact1()
    {
        return $this->belongsTo(Contact::class, 'source_1');
    }

    public function sourceContact2()
    {
        return $this->belongsTo(Contact::class, 'source_2');
    }

    public function sourceContact3()
    {
        return $this->belongsTo(Contact::class, 'source_3');
    }

    public function validePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}