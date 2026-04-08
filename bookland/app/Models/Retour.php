<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retour extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'bss_id', 'date_retour', 'created_by', 'motif'];

    public function bss()
    {
        return $this->belongsTo(Bss::class);
    }

    public function lignes()
    {
        return $this->belongsToMany(BssLigne::class, 'retour_bss_ligne')
                    ->withPivot('quantite_retournee');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}