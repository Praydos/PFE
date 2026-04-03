<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ville extends Model
{
    use HasFactory;
    //
    protected $fillable = ['nom'];

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function rbos()
    {
        return $this->belongsToMany(User::class, 'rbo_ville');
    }

    public function comptes()
    {
        return $this->hasMany(Compte::class);
    }
    
}
