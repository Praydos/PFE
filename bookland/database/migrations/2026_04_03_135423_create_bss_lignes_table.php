<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bss_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bss_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantite')->default(1);
            $table->integer('quantite_n')->nullable();
            $table->integer('quantite_n_1')->nullable();
            $table->enum('statut_ligne', ['en_attente', 'livree', 'retournee', 'convertie'])->default('en_attente');
            $table->date('date_retour')->nullable();
            $table->boolean('converted_to_adoption')->default(false);
            $table->unsignedBigInteger('adoption_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bss_lignes');
    }
};