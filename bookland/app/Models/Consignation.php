<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignation extends Model
{
    use HasFactory, LogsModelActivity;

    protected $fillable = ['delegate_id', 'product_id', 'annee_scolaire_id', 'quantity'];

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function anneeScolaire()
    {
        return $this->belongsTo(AnneeScolaire::class);
    }
}