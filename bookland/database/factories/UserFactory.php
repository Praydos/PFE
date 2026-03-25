<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Ville;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password
            'is_active' => true,
            'role' => $this->faker->randomElement(['admin', 'rbo', 'delegue', 'abo']),
            'ville_id' => Ville::factory(), // will be set manually if needed
            'remember_token' => Str::random(10),
        ];
    }

    // State for specific roles
    public function admin()
    {
        return $this->state(fn (array $attributes) => ['role' => 'admin']);
    }

    public function rbo()
    {
        return $this->state(fn (array $attributes) => ['role' => 'rbo']);
    }

    public function delegue()
    {
        return $this->state(fn (array $attributes) => ['role' => 'delegue']);
    }

    public function abo()
    {
        return $this->state(fn (array $attributes) => ['role' => 'abo']);
    }
}