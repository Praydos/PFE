<?php

namespace Database\Seeders;

use App\Models\Compte;
use App\Models\Zone;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompteSeeder extends Seeder
{
    public function run(): void
    {
        // Get all zones with their delegates
        $zones = Zone::with('delegates')->get();

        foreach ($zones as $zone) {
            // Create 5-10 accounts per zone
            $comptesCount = rand(5, 10);

            for ($i = 0; $i < $comptesCount; $i++) {
                // Pick a random delegate from this zone's delegates
                $delegue = $zone->delegates->random();

                Compte::factory()->create([
                    'ville_id' => $zone->ville_id,
                    'zone_id' => $zone->id,
                    'delegue_id' => $delegue->id,
                ]);
            }
        }
    }
}