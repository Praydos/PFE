<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('non_conformites', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->date('date_nc');
            $table->string('categorie'); // RECLAMATION CLIENTS / RECLAMATION FOURNISSEURS / RECLAMATION INTERNE / AUDIT & CONTROLE INTERNE
            $table->string('sous_categorie')->nullable();
            $table->string('evaluation')->nullable(); // observation / ameliorer / MINEUR / MAJEUR (only for audit)
            $table->string('objet');
            $table->text('description');
            $table->enum('statut', ['brouillon', 'en_cours', 'termine', 'annule', 'mise_en_attente'])->default('en_cours');
            $table->date('date_cloture')->nullable();

            // Effectiveness fields (stage 2)
            $table->text('mode_controle')->nullable();
            $table->text('description_resultat')->nullable();
            $table->boolean('action_efficace')->nullable();
            $table->boolean('besoin_action_amelioration')->nullable();
            $table->foreignId('responsable_efficacite_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date_efficacite')->nullable();

            // Links to other modules
            $table->foreignId('reclamation_id')->nullable()->constrained('reclamations')->onDelete('set null');
            $table->string('module_type')->nullable()->after('sous_categorie');
            $table->unsignedBigInteger('module_id')->nullable()->after('module_type');
        

            $table->timestamps();

            $table->index('categorie');
            $table->index(['module_type', 'module_id']);
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('non_conformites');
    }
};