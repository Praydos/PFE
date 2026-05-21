<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MpProduct extends Model
{
    use LogsModelActivity;
    protected $fillable = [
        'code_article',
        'editeur',
        'nom',
        'description',
    ];

    public function deliveries(): HasMany
    {
        return $this->hasMany(MpDelivery::class, 'mp_product_id');
    }
}
