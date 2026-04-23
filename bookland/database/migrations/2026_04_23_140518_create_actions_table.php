<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('objet');
            $table->foreignId('compte_id')->constrained()->onDelete('cascade');
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');
            $table->date('date_planification');
            $table->time('heure')->nullable();
            $table->integer('duree')->nullable();
            $table->string('lieu')->nullable();
            $table->boolean('rappel')->default(false);
            $table->integer('rappel_avant')->nullable();
            $table->enum('recurrence_frequence', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->integer('recurrence_intervalle')->nullable();
            $table->date('recurrence_fin')->nullable();
            $table->foreignId('parent_action_id')->nullable()->constrained('actions')->onDelete('set null');
            $table->enum('statut', ['planifie', 'realise', 'valide', 'annule', 'reporte'])->default('planifie');
            $table->datetime('date_realisation')->nullable();
            $table->datetime('date_validation')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null');
            $table->string('type')->default('commercial'); // 'commercial' or 'tache'
            $table->string('module_lie')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();

            // $table->foreignId('bss_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->index('date_planification');
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actions');
    }
};