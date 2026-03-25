<?php

namespace Database\Factories;

use App\Models\Quartier;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuartierFactory extends Factory
{
    protected $model = Quartier::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->streetName(),
            'zone_id' => Zone::factory(),
        ];
    }
}