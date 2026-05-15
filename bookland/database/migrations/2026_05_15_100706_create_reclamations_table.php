<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->date('date_reclamation');
            $table->date('date_echeance')->nullable();
            $table->enum('priorite', ['basse', 'moyenne', 'haute'])->default('moyenne');
            $table->enum('type', ['face_a_face', 'email', 'telephone', 'fax'])->nullable();
            $table->string('categorie');
            $table->string('sous_categorie')->nullable();
            $table->text('description');
            $table->text('analyse')->nullable();
            $table->text('reponse')->nullable();
            $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('statut', ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'])->default('brouillon');
            $table->date('date_cloture')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();

            // Optional links to other modules
            $table->string('module_lie')->nullable(); // 'product', 'specimen', 'mp', 'examen', 'event', 'facturation'
            $table->unsignedBigInteger('module_id')->nullable();
            $table->foreignId('non_conformite_id')->nullable()->constrained('non_conformites')->onDelete('set null');
            $table->foreignId('action_amelioration_id')->nullable()->constrained('actions_amelioration')->onDelete('set null');

            $table->index('statut');
            $table->index('categorie');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamations');
    }
};