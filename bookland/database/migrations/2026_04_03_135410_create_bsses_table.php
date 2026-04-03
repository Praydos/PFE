<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bsses', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('compte_id')->constrained()->onDelete('restrict');
            $table->foreignId('contact_id')->constrained()->onDelete('restrict');
            $table->enum('moyen_contact', ['telephone', 'email'])->nullable();
            $table->foreignId('delegate_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('restrict');
            $table->enum('source', ['consignation', 'magasin', 'transport'])->default('consignation');
            $table->date('date_bss');
            $table->date('date_livraison')->nullable();
            $table->date('date_recuperation')->nullable();
            $table->enum('recupere_par_type', ['contact', 'transport'])->nullable();
            $table->foreignId('recupere_par_contact_id')->nullable()->constrained('contacts');
            $table->string('numero_expedition')->nullable();
            $table->enum('statut', ['brouillon', 'en_attente', 'valide', 'livre', 'partiel', 'retourne'])->default('brouillon');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(false);
            $table->text('motif_validation')->nullable();
            $table->enum('controle', ['OK', 'absence_signature', 'absence_cachet', 'absence_document'])->nullable();
            $table->enum('feedback_statut', ['confirme', 'defavorable'])->nullable();
            $table->date('feedback_date')->nullable();
            $table->foreignId('feedback_contact_id')->nullable()->constrained('contacts');
            $table->enum('feedback_moyen', ['email', 'telephone', 'sms', 'courrier'])->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->index('compte_id');
            $table->index('delegate_id');
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bss');
    }
};