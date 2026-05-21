<?php

namespace App\Models;

use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionLine extends Model
{
    use HasFactory, LogsModelActivity;

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

    public function bss()
{
    return $this->belongsTo(Bss::class);
}

public function retour()
{
    return $this->belongsTo(Retour::class);
}
}