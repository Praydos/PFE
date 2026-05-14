<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MpDelivery extends Model
{
    protected $fillable = [
        'numero',
        'compte_id',
        'contact_id',
        'delegate_id',
        'annee_scolaire_id',
        'mp_product_id',
        'date_delivery',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_delivery' => 'date',
        ];
    }

    public function compte(): BelongsTo
    {
        return $this->belongsTo(Compte::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegate_id');
    }

    public function anneeScolaire(): BelongsTo
    {
        return $this->belongsTo(AnneeScolaire::class);
    }

    public function mpProduct(): BelongsTo
    {
        return $this->belongsTo(MpProduct::class, 'mp_product_id');
    }

    public function linkedCommercialAction(): HasOne
    {
        return $this->hasOne(Action::class, 'mp_delivery_id');
    }
}
