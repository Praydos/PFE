<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BssLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'bss_id', 'product_id', 'quantite', 'quantite_n', 'quantite_n_1',
        'statut_ligne', 'date_retour', 'converted_to_adoption', 'adoption_id'
    ];

    protected $casts = [
        'date_retour' => 'date',
        'converted_to_adoption' => 'boolean',
    ];

    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function adoption()
    // {
    //     return $this->belongsTo(Adoption::class);
    // }

    public function retourLines()
    {
        return $this->hasMany(Retour::class, 'bss_ligne_id');
    }
}