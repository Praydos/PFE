<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Compte;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $contacts = Contact::factory()->count(30)->create();
        $comptes = Compte::all();

        foreach ($contacts as $contact) {
            // Randomly assign to 1-3 comptes
            $assignedComptes = $comptes->random(rand(1, 3));
            $contact->comptes()->attach($assignedComptes->pluck('id')->toArray());
        }
    }
}