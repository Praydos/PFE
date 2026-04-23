<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('action_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('action_id')->constrained()->onDelete('cascade');
            $table->string('categorie');
            $table->string('action_type');
            $table->string('moyen')->nullable();
            $table->text('description')->nullable();

            // Liens vers les autres entités
            //to add pivot tables for bss/retours
            $table->foreignId('bss_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('retour_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_lines');
    }
};