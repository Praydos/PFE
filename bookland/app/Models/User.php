<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['nom', 'prenom', 'email', 'password', 'is_active', 'role', 'ville_id'];
    protected $casts = [
        'is_active' => 'boolean',
        'role' => 'string',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // A user belongs to a city / must review this later 
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    // A delegate belongs to many zones
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'delegue_zone', 'delegue_id', 'zone_id');
    }

    // A user (RBO) may be responsible for many zones
    public function zonesAsRbo()
    {
        return $this->hasMany(Zone::class, 'rbo_id');
    }

    // A delegate has many accounts
    public function comptes()
    {
        return $this->hasMany(Compte::class, 'delegue_id');
    }

    // a rbo has many cities
    public function rboVilles()
    {
        return $this->belongsToMany(Ville::class, 'rbo_ville');
    }

    //check if user is assigned to a zone 
    
    public function isRboForZone(Zone $zone)
    {
        return $this->role === 'rbo' && $this->rboVilles->contains($zone->ville);
    }

    // Helper to check role
    public function hasRole($role)
    {
        return $this->role === $role;
    }


    public function supervisedDelegates()
    {
        return User::whereHas('zones', function ($query) {
            $query->where('rbo_id', $this->id);
        })->where('role', 'delegue');
    }










    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
