<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'source',
        'isbn_13', 'isbn_10', 'reference_interne',
        'titre', 'sous_titre', 'niveau', 'type', 'edition',
        'auteur', 'description',
        'langue', 'rayon', 'sous_rayon', 'categorie', 'sous_categorie',
        'editeur', 'collection', 'support',
        'nbr_pages', 'prix', 'date_parution', 'image'
    ];

    protected $casts = [
        'date_parution' => 'date',
        'prix' => 'decimal:2',
    ];

    // Later: specimens, adoptions relationships
    // public function specimens() { return $this->hasMany(Specimen::class); }
    // public function adoptions() { return $this->hasMany(Adoption::class); }



    //product has many consignations
    public function consignations()
    {
        return $this->hasMany(Consignation::class);
    }
}