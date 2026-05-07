<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CompteContact extends Model
{
    protected $table = 'compte_contact';

    protected $fillable = [
        'compte_id',
        'contact_id',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function getIsActiveAttribute()
    {
        return is_null($this->date_fin);
    }

    public function getDurationAttribute()
    {
        $start = $this->date_debut;
        $end = $this->date_fin ?? now();

        return $start
            ? $start->diffForHumans($end, true)
            : null;
    }
}