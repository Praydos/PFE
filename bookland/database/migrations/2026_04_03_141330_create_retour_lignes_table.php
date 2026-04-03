<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retour_bss_ligne', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retour_id')->constrained()->onDelete('cascade');
            $table->foreignId('bss_ligne_id')->constrained()->onDelete('cascade');
            $table->integer('quantite_retournee');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retour_bss_ligne');
    }
};