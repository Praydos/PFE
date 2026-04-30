<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actions_amelioration', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); //code systeme, e.g., AA-2025-0001
            $table->foreignId('compte_id')->constrained('users')->onDelete('cascade'); //comptes
            $table->foreignId('emetteur_id')->constrained('users')->onDelete('cascade'); //contact
            $table->date('dateAA');
            $table->string('type'); // e.g., "Action corrective", "Action préventive", "Action d'amélioration"
            $table->string('origine'); // e.g., "Réclamation client", "Audit et controle interne", "PROJET D’AMÉLIORATION "
            $table->text('analyse_causes')->nullable();
            $table->text('sanctions')->nullable();
            $table->text('resultats_attendus')->nullable();
            //suivi de l'action
            $table->text('verification_mise_en_oeuvre')->nullable();
            $table->foreignId('responsable_suivi_id')->nullable()->constrained('users')->onDelete('cascade');// contacts
            $table->date('date_suivi')->nullable();

            //effecacité de l'action
            $table->date('date_effecacite')->nullable();
            $table->foreignId('responsable_effecacite_id')->nullable()->constrained('users')->onDelete('cascade');// contacts
            $table->text('mode_controle')->nullable();
            $table->text('description_resultat')->nullable();
            $table->boolean('action_efficace')->nullable();
            $table->boolean('besoin_action_amelioration')->nullable();
            $table->enum('statut', ['brouillon', 'en_cours', 'termine', 'annule', 'en_attente'])->default('brouillon');
            $table->date('date_cloture')->nullable();

            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actions_amelioration');
    }
};