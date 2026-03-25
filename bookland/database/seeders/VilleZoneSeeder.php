<?php

namespace Database\Seeders;

use App\Models\Ville;
use App\Models\Zone;
use App\Models\User;
use App\Models\Quartier;
use Illuminate\Database\Seeder;

class VilleZoneSeeder extends Seeder
{
    public function run(): void
    {
        // Create 5 cities
        $villes = Ville::factory()->count(5)->create();

        // For each city, create 2-4 zones
        foreach ($villes as $ville) {
            // Get random RBOs (users with role 'rbo')
            $rbos = User::where('role', 'rbo')->get();

            Zone::factory()
                ->count(rand(2, 4))
                ->create([
                    'ville_id' => $ville->id,
                    'rbo_id' => $rbos->random()->id,
                ]);
        }

        // Now assign delegates to zones
        $zones = Zone::all();
        $delegues = User::where('role', 'delegue')->get();

        foreach ($zones as $zone) {
            // Each zone gets 1-3 random delegates (many-to-many)
            $assignedDelegues = $delegues->random(rand(1, 3));
            $zone->delegates()->attach($assignedDelegues->pluck('id')->toArray());
        }

        $zones = Zone::all();
        foreach ($zones as $zone) {
            // Create 1–3 quartiers per zone
            Quartier::factory()->count(rand(1, 3))->create([
                'zone_id' => $zone->id,
            ]);
        }
    }
}