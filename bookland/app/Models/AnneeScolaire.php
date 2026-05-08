<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnneeScolaire extends Model
{
    use HasFactory;

    protected $table = 'annees_scolaires';

    protected $fillable = [
        'libelle', 'date_debut', 'date_fin', 'is_active', 'is_closed'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
    ];

    // Helper: Get the currently active year
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    // Helper: Activate this year and deactivate others
    public function setActive()
    {
        self::where('is_active', true)->update(['is_active' => false]);
        $this->is_active = true;
        $this->save();
    }

    // Helper: Close the year (lock data)
    public function close()
    {
        $this->is_closed = true;
        $this->save();
    }

    //helper: to  check if the year is closed
    public function isLocked()
    {
        return $this->is_closed;    
    }
}