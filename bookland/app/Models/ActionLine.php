<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLine extends Model
{
    use HasFactory;

    protected $fillable = ['action_id', 'categorie', 'action_type', 'moyen', 'description','bss_id', 'retour_id'];

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'action_line_contact');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'action_line_product');
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class, 'action_line_examen');
    }
}