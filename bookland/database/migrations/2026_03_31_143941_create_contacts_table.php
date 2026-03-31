<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('category', 
            ['Gestion des Contacts Clients', 'Gestion des Contacts Editeurs',
             'Gestion des Contacts Formateurs', 'Gestion des Contacts Collaborateurs'])->default('');
            
            $table->enum('civilite', ['M.', 'Mme', 'Mlle'])->nullable();
            $table->enum('fonction', ['Directeur', 'Responsable', 'Enseignant', 'Autre'])->nullable();

            $table->foreignId('ville_id')->constrained()->onDelete('cascade');

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
