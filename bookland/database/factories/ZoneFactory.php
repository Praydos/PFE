<?php

namespace Database\Factories;

use App\Models\Zone;
use App\Models\Ville;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZoneFactory extends Factory
{
    protected $model = Zone::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'ville_id' => Ville::factory(), // creates a new ville if not provided
            'rbo_id' => User::factory()->rbo(), // creates a new rbo user
        ];
    }
}