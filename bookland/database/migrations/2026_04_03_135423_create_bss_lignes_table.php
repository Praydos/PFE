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
            $table->foreignId('bss_id')->constrained('bsses')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            //quantity n and n-1
            $table->integer('quantity');
            $table->enum('source', ['consignation', 'magasin'])->default('consignation');
            $table->enum('statut_ligne', ['en_attente', 'livree', 'retournee','Adoptee'])->default('en_attente');
            $table->date('date_retour')->nullable();
            $table->timestamps();

            $table->unique(['bss_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bss_lines');
    }
};