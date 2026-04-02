<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->enum('source', ['bookland', 'esprit_du_livre'])->default('bookland');

            $table->string('isbn_13')->unique()->nullable();
            $table->string('isbn_10')->unique()->nullable();
            $table->string('reference_interne')->nullable();

            $table->string('titre');
            $table->string('sous_titre')->nullable();
            $table->string('niveau')->nullable();
            $table->string('type');
            $table->string('edition')->nullable();
            $table->string('auteur')->nullable();
            $table->text('description')->nullable();

            $table->string('langue')->nullable();
            $table->string('rayon')->nullable();
            $table->string('sous_rayon')->nullable();
            $table->string('categorie')->nullable();
            $table->string('sous_categorie')->nullable();
            $table->string('editeur')->nullable();
            $table->string('collection')->nullable();
            $table->string('support')->nullable();

            $table->integer('nbr_pages')->default(0);
            $table->decimal('prix', 10, 2)->nullable();
            $table->date('date_parution')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();

            $table->unique(['titre', 'sous_titre', 'niveau', 'type', 'edition'], 'article_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};