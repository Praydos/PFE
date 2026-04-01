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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->enum('source', ['bookland', 'esprit du livre'])->default('bookland');
            $table->enum('rayon', ['primaire', 'secondaire', 'universitaire'])->default('primaire');
            $table->enum('categorie',['categorie 1', 'categorie 2', 'categorie 3'])->default('categorie 1');
            $table->enum('langaue', ['français', 'anglais'])->default('français');
            $table->enum('editeur', ['éditeur 1', 'éditeur 2', 'éditeur 3'])->default('éditeur 1');
            $table->enum('collection', ['collection 1', 'collection 2', 'collection 3'])->default('collection 1');  

            $table->string('titre');
            $table->String('sous_titre')->nullable();
            $table->string("niveau")->nullable();
            $table->string('isbn')->unique();
            $table->decimal('prix', 8, 2)->nullable();
            $table->integer('nbr_pages')->default(0);
            $table->string('type');
            $table->string('edition')->nullable();
            $table->string('auteur')->nullable();
            $table->string('description')->nullable();
            

            $table->enum('support',['support 1', 'support 2', 'support 3'])->default('support 1');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
