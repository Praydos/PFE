<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'zone_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function comptes()
    {
        return $this->hasMany(Compte::class);
    }
}