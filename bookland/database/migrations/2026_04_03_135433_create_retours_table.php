<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retours', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('bss_id')->constrained()->onDelete('cascade');
            $table->date('date_retour');
            $table->foreignId('created_by')->constrained('users');
            $table->text('motif')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retours');
    }
};