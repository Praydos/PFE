<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->string('rapport_titre')->nullable()->after('valide_par');
            $table->text('rapport_description')->nullable()->after('rapport_titre');
            $table->date('rapport_date')->nullable()->after('rapport_description');
        });
    }

    public function down(): void
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn(['rapport_titre', 'rapport_description', 'rapport_date']);
        });
    }
};
