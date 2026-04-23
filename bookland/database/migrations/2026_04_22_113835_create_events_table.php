<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ville_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->string('type'); // Public Speaking, Ateliers de lecture, etc.
            $table->string('editeur'); // Esprit du livre, Matifica, Express publishing, Bookland
            $table->date('date_event');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};