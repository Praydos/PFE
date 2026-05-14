<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mp_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('compte_id')->constrained('comptes')->restrictOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->restrictOnDelete();
            $table->foreignId('delegate_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->restrictOnDelete();
            $table->foreignId('mp_product_id')->constrained('mp_products')->restrictOnDelete();
            $table->date('date_delivery');
            $table->enum('statut', ['delivered', 'returned'])->default('delivered');
            $table->timestamps();

            $table->index(['compte_id', 'mp_product_id', 'annee_scolaire_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_deliveries');
    }
};
