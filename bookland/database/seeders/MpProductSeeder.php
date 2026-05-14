<?php

namespace Database\Seeders;

use App\Models\MpProduct;
use Illuminate\Database\Seeder;

class MpProductSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code_article' => 'MP-GUIDE-IW1', 'editeur' => 'Bookland', 'nom' => 'Guide pédagogique I Wonder 1', 'description' => null],
            ['code_article' => 'MP-POSTER-IW2', 'editeur' => 'Bookland', 'nom' => 'Poster I Wonder 2', 'description' => null],
            ['code_article' => 'MP-FLASH-ESP', 'editeur' => 'Esprit du livre', 'nom' => 'Flashcards Esprit Maths CP', 'description' => null],
            ['code_article' => 'MP-GUIDE-ESP3', 'editeur' => 'Esprit du livre', 'nom' => 'Guide du professeur Esprit Science CE1', 'description' => null],
            ['code_article' => 'MP-AUDIO-ENG', 'editeur' => 'Bookland', 'nom' => 'CD audio Young Learners', 'description' => null],
        ];

        foreach ($rows as $row) {
            MpProduct::updateOrCreate(
                ['code_article' => $row['code_article']],
                $row
            );
        }
    }
}
