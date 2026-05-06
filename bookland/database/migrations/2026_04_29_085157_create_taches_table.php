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
        Schema::create('taches', function (Blueprint $table) {
            $table->id();
            $table->string("objet");
            $table->text("description")->nullable();
            $table->date('date_planification')->nullable();
            //add HEURE DE L’ACTION
            $table->time('heure')->nullable()->after('date_planification');
            $table->string('lieu')->nullable();
            //multiple contacts
            $table->json('contacts')->nullable();
            $table->boolean('all_day')->default(false);
            $table->date('date_fin')->nullable();
            //date de validation
            $table->date('date_validation')->nullable();
            $table->boolean('is_validated')->default(false);
            //delegue_id
            $table->foreignId('delegue_id')->constrained('users')->onDelete('cascade');

            // add repitition
            $table->enum('recurrence_frequence', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->integer('recurrence_intervalle')->nullable();
            $table->date('recurrence_fin')->nullable();
            $table->foreignId('parent_tache_id')->nullable()->constrained('taches')->onDelete('set null');

            //rapel et notification for later
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
