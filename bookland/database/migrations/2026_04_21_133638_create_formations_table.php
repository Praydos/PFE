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

            $table->date('date_demande');
            $table->json('dates_proposees')->nullable(); // store multiple proposed dates
            $table->enum('statut', ['demande', 'planifiee', 'annulee', 'reportee', 'realisee'])->default('demande');
            $table->enum('type', ['Formation méthode', 'Présentation méthode', 'Accompagnement pédagogique', 'Leçon modèle', 'Intégration de classe', 'Audit de classe', 'Formation Examen CAMBRIDGE']);
            $table->enum('cible', ['Direction', 'Enseignants', 'Parents'])->nullable();


            $table->string('langue');
            $table->string('formation'); // e.g., "i wonder", "Esprit Math", etc.
            
            
            $table->date('date_formation')->nullable();
            
            
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formations');
    }
};
