<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->foreignId('ville_id')->constrained()->onDelete('cascade');
            $table->json('categories')->nullable(); // store multiple values
            $table->enum('civilite', ['M.', 'Mme', 'Mlle'])->nullable();
            $table->enum('fonction', ['Directeur', 'Responsable', 'Enseignant', 'Autre'])->nullable();
            $table->json('cycles')->nullable(); // store multiple values
            $table->boolean('status')->default(false); // active/inactive status

            //add editeur options for contact
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
