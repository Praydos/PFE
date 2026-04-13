<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('effectifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->onDelete('cascade');
            $table->enum('niveau', ['CP', 'CE1', 'CE2', '1er', '2ème', '6ème', '5ème', '4ème', '3ème'])->nullable(); // e.g., CP, CE1, 6ème, etc.
            $table->enum('cycle', ['primaire', 'college', 'Lycée','Learners','Pre-teens','Teens','Adults'])->nullable();
            $table->integer('massar')->nullable(); // official number from Massar system
            

            $table->foreignId('source_1')->nullable()->constrained('contacts')->onDelete('set null'); // store contact id.
            $table->integer('nombre_classes_1')->default(0);

            $table->foreignId('source_2')->nullable()->constrained('contacts')->onDelete('set null'); // store contact id.
            $table->integer('nombre_classes_2')->default(0);

            $table->foreignId('source_3')->nullable()->constrained('contacts')->onDelete('set null'); // store contact id.
            $table->integer('nombre_classes_3')->default(0);


            $table->integer('effectif_valide')->nullable(); // validated number by BO
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_validated')->default(false);
            $table->timestamps();
            
            $table->unique(['compte_id', 'annee_scolaire_id', 'niveau'], 'effectif_unique');
            $table->index('compte_id');
            $table->index('annee_scolaire_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('effectifs');
    }
};