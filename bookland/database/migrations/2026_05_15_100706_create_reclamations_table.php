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
        $table->enum('priorite', ['basse', 'moyenne', 'haute'])->default('moyenne');
        $table->enum('type', ['face_a_face', 'email', 'telephone', 'fax'])->nullable();
        $table->string('categorie');
        $table->string('sous_categorie')->nullable();
        $table->foreignId('produit_id')->nullable()->constrained('products')->onDelete('set null');
        $table->foreignId('specimen_id')->nullable()->constrained('bss')->onDelete('set null');
        $table->foreignId('mp_id')->nullable()->constrained('mp_products')->onDelete('set null');
        $table->text('description');
        $table->text('analyse')->nullable();
        $table->text('reponse')->nullable();
        $table->date('date_reponse')->nullable();
        $table->foreignId('responsable_id')->nullable()->constrained('users')->onDelete('set null');
        $table->enum('statut', ['brouillon', 'en_cours', 'mise_en_attente', 'cloturee', 'annulee'])->default('brouillon');
        $table->date('date_cloture')->nullable();
        $table->foreignId('created_by')->constrained('users');
        $table->foreignId('updated_by')->nullable()->constrained('users');
        $table->boolean('est_non_conformite')->default(false);
        $table->boolean('besoin_action_amelioration')->default(false);
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('reclamations');
    }
};