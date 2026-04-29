<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tache extends Model
{
    use HasFactory;

    protected $fillable = [
        'objet', 'description', 'date_planification', 'lieu', 'contacts',
        'all_day', 'date_fin', 'date_validation', 'is_validated', 'delegue_id'
    ];

    protected $casts = [
        'contacts' => 'array',
        'date_planification' => 'date',
        'date_fin' => 'date',
        'date_validation' => 'date',
        'all_day' => 'boolean',
        'is_validated' => 'boolean',
    ];

    public function delegate()
    {
        return $this->belongsTo(User::class, 'delegue_id');
    }

    // Helper to get contact models
    public function getContactsListAttribute()
    {
        if (empty($this->contacts)) return collect();
        return Contact::whereIn('id', $this->contacts)->get();
    }
}