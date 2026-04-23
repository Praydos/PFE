<?php

namespace Database\Seeders;

use App\Models\Compte;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run()
    {
        $comptes = Compte::all();
        if ($comptes->isEmpty()) {
            $this->command->error('No comptes found. Run CompteSeeder first.');
            return;
        }

        // For each compte, create 1-3 contacts belonging to the same city and attach them
        foreach ($comptes as $compte) {
            $contactsCount = rand(1, 3);
            for ($i = 0; $i < $contactsCount; $i++) {
                $contact = Contact::factory()->create([
                    'ville_id' => $compte->ville_id,
                ]);
                $contact->comptes()->attach($compte->id);
            }
        }

        // Optional: create some extra contacts without any compte (to have "Non-Affecté" status)
        Contact::factory()->count(10)->create();

        $this->command->info('Contacts created and attached to comptes correctly.');
    }
}