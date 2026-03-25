<?php

namespace Database\Seeders;

use App\Models\Quartier;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class QuartierSeeder extends Seeder
{
    public function run()
    {
        $zones = Zone::all();

        foreach ($zones as $zone) {
            // Create 1-3 quartiers per zone
            Quartier::factory()
                ->count(rand(1, 3))
                ->create(['zone_id' => $zone->id]);
        }
    }
}