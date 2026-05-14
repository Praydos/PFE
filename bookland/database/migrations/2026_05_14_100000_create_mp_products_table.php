<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mp_products') && ! Schema::hasColumn('mp_products', 'code_article')) {
            Schema::dropIfExists('mp_products');
        }

        if (Schema::hasTable('mp_products')) {
            return;
        }

        Schema::create('mp_products', function (Blueprint $table) {
            $table->id();
            $table->string('code_article')->unique();
            $table->string('editeur');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mp_products');
    }
};
