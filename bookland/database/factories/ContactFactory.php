<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Ville;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'email' => $this->faker->optional()->safeEmail(),
            'telephone' => $this->faker->optional()->phoneNumber(),
            'ville_id' => Ville::inRandomOrder()->first()->id ?? Ville::factory(),
            'civilite' => $this->faker->randomElement(['M.', 'Mme', 'Mlle']),
            'fonction' => $this->faker->randomElement(['Directeur', 'Responsable', 'Enseignant', 'Autre']),
            'categories' => $this->faker->randomElements(
                ['Gestion des Contacts Clients', 'Gestion des Contacts Editeurs', 'Gestion des Contacts Formateurs', 'Gestion des Contacts Collaborateurs'],
                $this->faker->numberBetween(0, 2)
            ),
            'cycles' => $this->faker->randomElements(
                ['Maternelle', 'Primaire', 'Collège', 'Lycée', 'Kids', 'Teens', 'Adults'],
                $this->faker->numberBetween(0, 3)
            ),
        ];
    }
}