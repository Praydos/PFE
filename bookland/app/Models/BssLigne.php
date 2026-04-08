<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BssLigne extends Model
{
    use HasFactory;

    protected $table = 'bss_lignes';

    protected $fillable = [
        'bss_id', 'product_id', 'quantity', 'source', 'statut_ligne', 'date_retour'
    ];

    protected $casts = [
        'date_retour' => 'date',
    ];

    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}