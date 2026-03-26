<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ecole', 'centre_de_langue', 'librairie', 'autre']);
            $table->string('etablissement');
            $table->foreignId('ville_id')->constrained()->onDelete('restrict');
            $table->foreignId('zone_id')->constrained()->onDelete('restrict');
            $table->foreignId('quartier_id')->after('zone_id')->nullable()->constrained()->nullOnDelete();
            $table->text('adresse');
            $table->foreignId('delegue_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['actif', 'ferme'])->default('actif');
            $table->text('motif_fermeture')->nullable();
            $table->enum('cycle', ['Maternelle', 'Primaire', 'Collège', "Lycée", 'Kids', 'Teens','Adults'])->nullable();;
            // $table->boolean('suspendre_actions')->default(false);
            // $table->text('motif_suspension')->nullable();
            $table->string('tel_bureau_1')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('ville_id');
            $table->index('zone_id');
            $table->index('delegue_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
