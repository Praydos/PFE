<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetourLigne extends Model
{
    use HasFactory;

    protected $table = 'retour_bss_ligne';

    protected $fillable = ['retour_id', 'bss_ligne_id', 'quantite_retournee'];

    public function retour()
    {
        return $this->belongsTo(Retour::class);
    }

    public function bssLigne()
    {
        return $this->belongsTo(BssLigne::class);
    }
}