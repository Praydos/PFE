<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('consignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegate_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['delegate_id', 'product_id', 'annee_scolaire_id'], 'consignation_unique');
            $table->index('delegate_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('consignations');
    }
};