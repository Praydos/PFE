<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'source' => $this->faker->randomElement(['bookland', 'esprit_du_livre']),
            'isbn_13' => $this->faker->optional(0.7)->isbn13(),
            'isbn_10' => $this->faker->optional(0.5)->isbn10(),
            'reference_interne' => $this->faker->optional()->numerify('REF-#####'),
            'titre' => $this->faker->sentence(3),
            'sous_titre' => $this->faker->optional()->sentence(5),
            'niveau' => $this->faker->randomElement(['CP', 'CE1', 'CE2', 'CM1', 'CM2', '6ème', '5ème', '4ème', '3ème', 'Seconde', 'Première', 'Terminale']),
            'type' => $this->faker->randomElement(['Livre de l\'élève', 'Guide pédagogique', 'Cahier d\'activités', 'Fichier ressources', 'Manuel numérique']),
            'edition' => $this->faker->optional()->year(),
            'auteur' => $this->faker->optional()->name(),
            'description' => $this->faker->optional()->paragraph(),
            'langue' => $this->faker->randomElement(['Français', 'Anglais', 'Arabe', 'Espagnol']),
            'rayon' => $this->faker->randomElement(['Scolaire', 'Parascolaire', 'Universitaire']),
            'sous_rayon' => $this->faker->randomElement(['Primaire', 'Collège', 'Lycée', 'Supérieur']),
            'categorie' => $this->faker->randomElement(['Méthode', 'Lecture', 'Grammaire', 'Mathématiques', 'Sciences']),
            'sous_categorie' => $this->faker->randomElement(['Français', 'Anglais', 'Maths', 'SVT', 'Histoire-Géo']),
            'editeur' => $this->faker->randomElement(['Bookland', 'Hachette', 'Nathan', 'Magnard', 'Bordas', 'Hatier']),
            'collection' => $this->faker->optional()->word(),
            'support' => $this->faker->randomElement(['Papier', 'Numérique', 'Papier + Numérique']),
            'nbr_pages' => $this->faker->numberBetween(24, 400),
            'prix' => $this->faker->optional(0.8)->randomFloat(2, 5, 60),
            'date_parution' => $this->faker->optional()->date(),
            'image' => $this->faker->optional()->imageUrl(200, 250, 'books', true),
        ];
    }
}