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
            $table->string('numero')->unique(); // e.g., BSS-2025-0001
            $table->foreignId('compte_id')->constrained()->onDelete('restrict');
            $table->foreignId('contact_id')->constrained()->onDelete('restrict'); // required
            $table->foreignId('delegate_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('restrict');

            $table->date('date_bss'); // creation date
            $table->date('date_livraison_prevue')->nullable();
            $table->enum('moyen_contact', ['telephone', 'email'])->nullable();
            $table->enum('recupere_par_type', ['contact', 'transport'])->nullable();
            $table->string('recupere_par_nom')->nullable(); // contact name or expedition number
            $table->enum('statut', ['brouillon', 'soumis', 'valide', 'livre', 'refuse','retour'])->default('brouillon');
            $table->text('motif_refus')->nullable();
            $table->boolean('is_validated_by_rbo')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Feedback fields (editable by delegate after validation)
            $table->text('feedback')->nullable();
            $table->date('date_feedback')->nullable()->after('feedback');
            $table->enum('controle_document', ['OK', 'Absence signature', 'Absence cachet', 'Absence Document'])->nullable();
            $table->timestamps();

            $table->index('compte_id');
            $table->index('delegate_id');
            $table->index('annee_scolaire_id');
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bsses');
    }
};