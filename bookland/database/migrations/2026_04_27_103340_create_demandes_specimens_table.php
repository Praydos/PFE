<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demandes_specimens', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['etablissement', 'personnelle']);
            $table->foreignId('compte_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->foreignId('ville_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            
            $table->date('date_demande');
            $table->text('description')->nullable();
            $table->enum('statut', ['demande', 'valide', 'decline', 'annule'])->default('demande');
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('date_validation')->nullable();
            $table->foreignId('original_bss_id')->nullable()->constrained('bsses')->onDelete('set null');
            $table->string('generated_bss_id')->nullable()->change();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes_specimens');
    }
};