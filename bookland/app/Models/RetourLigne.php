public function retours()
{
    return $this->belongsToMany(Retour::class, 'retour_bss_ligne')
                ->withPivot('quantite_retournee')
                ->withTimestamps();
}