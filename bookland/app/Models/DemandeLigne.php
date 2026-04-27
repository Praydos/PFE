<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeLigne extends Model
{
    use HasFactory;

    protected $fillable = ['demande_id', 'product_id', 'quantity'];

    public function demande()
{
    return $this->belongsTo(DemandeSpecimen::class, 'demande_id');
}

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}