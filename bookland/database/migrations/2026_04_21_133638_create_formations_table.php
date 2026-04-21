<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('ville_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');

            $table->json('date_demande'); //Dates proposées par l’école // store multiple
            $table->json('dates_proposees')->nullable(); // store multiple proposed dates // Dates proposées a l’école
            $table->enum('statut', ['demande', 'planifiee', 'annulee', 'reportee', 'realisee'])->default('demande');
            $table->enum('type', ['Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique', 'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE']);
            $table->enum('cible', ['Direction', 'Enseignants', 'Parents'])->nullable();            
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formations');
    }
};
