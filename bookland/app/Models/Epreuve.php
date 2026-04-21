<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epreuve extends Model
{
    use HasFactory;

    protected $fillable = ['examen_id', 'epreuve', 'duree', 'date_realisation'];

    protected $casts = [
        'date_realisation' => 'date',
    ];

    public function examen()
    {
        return $this->belongsTo(Examen::class);
    }
}