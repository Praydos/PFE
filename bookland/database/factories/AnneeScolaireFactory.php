<?php

namespace Database\Factories;

use App\Models\AnneeScolaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnneeScolaireFactory extends Factory
{
    protected $model = AnneeScolaire::class;

    public function definition(): array
    {
        $year = $this->faker->unique()->year;
        return [
            'libelle' => $year . '-' . ($year + 1),
            'date_debut' => $year . '-09-01',
            'date_fin' => ($year + 1) . '-06-30',
            'is_active' => false,
            'is_closed' => false,
        ];
    }
}
