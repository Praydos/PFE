<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory, LogsModelActivity;

    protected $fillable = ['name', 'ville_id', 'rbo_id'];

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function rbo()
    {
        return $this->belongsTo(User::class, 'rbo_id');
    }

    public function delegates()
    {
        return $this->belongsToMany(User::class, 'delegue_zone', 'zone_id', 'delegue_id')
        ->where('role', 'delegue');;
    }

    public function comptes()
    {
        return $this->hasMany(Compte::class);
    }

    public function quartiers()
    {
        return $this->hasMany(Quartier::class);
    }
}