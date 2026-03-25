<?php

namespace Database\Factories;

use App\Models\Compte;
use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\Quartier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompteFactory extends Factory
{
    protected $model = Compte::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['ecole', 'centre_de_langue', 'librairie', 'autre']),
            'etablissement' => $this->faker->company(),
            'ville_id' => Ville::factory(),
            'zone_id' => Zone::factory(),
            'quartier_id' => Quartier::factory(),
            'adresse' => $this->faker->address(),
            'delegue_id' => User::factory()->delegue(),
            'status' => 'actif',
            'motif_fermeture' => null,
            'cycle' => $this->faker->randomElement(['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults']),
            'tel_bureau_1' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
        ];
    }
}