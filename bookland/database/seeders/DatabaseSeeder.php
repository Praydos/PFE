<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            VilleZoneSeeder::class, // depends on users (rbos) and villes
            CompteSeeder::class,    // depends on zones and delegates
        ]);
    }
}