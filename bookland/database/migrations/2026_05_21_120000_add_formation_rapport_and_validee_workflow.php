<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->string('rapport_titre')->nullable()->after('cible');
            $table->text('rapport_description')->nullable()->after('rapport_titre');
            $table->datetime('date_validation')->nullable()->after('rapport_description');
            $table->foreignId('valide_par')->nullable()->after('date_validation')->constrained('users')->nullOnDelete();
        });

        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE formations MODIFY statut ENUM('demande', 'planifiee', 'annulee', 'reportee', 'realisee', 'validee') NOT NULL DEFAULT 'demande'");
        }
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('valide_par');
            $table->dropColumn(['rapport_titre', 'rapport_description', 'date_validation']);
        });

        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE formations MODIFY statut ENUM('demande', 'planifiee', 'annulee', 'reportee', 'realisee') NOT NULL DEFAULT 'demande'");
        }
    }
};
