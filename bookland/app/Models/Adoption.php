<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adoption extends Model
{
    use HasFactory;

    protected $fillable = [
        'compte_id', 'product_id', 'annee_scolaire_id', 'quantity',
        'date_adoption', 'delegate_id', 'niveau_scolaire', 'bss_ligne_id', 'contact_id', 
        'methode','type_adoption','isbn','sous_categorie', 'cycle','niveau'
    ];

    protected $casts = [
        'date_adoption' => 'date',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function bssLigne()
    {
        return $this->belongsTo(BssLigne::class, 'bss_ligne_id');
    }
}