<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('adoptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('date_adoption');
            $table->foreignId('delegate_id')->constrained('users')->onDelete('cascade');
            $table->string('niveau_scolaire')->nullable(); // e.g., CE1, 6ème, etc.
            $table->foreignId('bss_ligne_id')->nullable()->constrained('bss_lignes')->onDelete('set null');
            $table->timestamps();

            // Ensure one adoption per product per compte per year (optional, but good for uniqueness)
            $table->unique(['compte_id', 'product_id', 'annee_scolaire_id'], 'adoption_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('adoptions');
    }
};