<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->string('langue');
            $table->string('organisme');
            $table->string('titre');
            $table->string('abreviation')->nullable();
            $table->string('niveau_cecr')->nullable();
            $table->json('niveaux_scolaires')->nullable(); // multiple values
            $table->date('date_demande');
            $table->date('date_examen')->nullable();
            $table->enum('statut', [
                'en_attente_feedback', 'avis_favorable', 'avis_defavorable', 'signature_convention',
                'commande', 'planifie', 'annule', 'decline', 'reporte', 'en_attente_resultats',
                'communication_resultats', 'livraison_attestations'
            ])->default('en_attente_feedback');
            $table->text('description')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examens');
    }
};