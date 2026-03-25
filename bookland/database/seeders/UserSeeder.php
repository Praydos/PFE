<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create one admin
        User::factory()->admin()->create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@example.com',
        ]);

        // Create 3 RBOs
        User::factory()->rbo()->count(3)->create();

        // Create 10 delegates
        User::factory()->delegue()->count(10)->create();

        // Create 2 ABOs
        User::factory()->abo()->count(2)->create();

        // You can add more custom users as needed
    }
}