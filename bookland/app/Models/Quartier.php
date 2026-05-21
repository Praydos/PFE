<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    use HasFactory, LogsModelActivity;

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