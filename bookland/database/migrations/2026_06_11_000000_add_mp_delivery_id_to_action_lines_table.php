<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('action_lines', function (Blueprint $table) {
            $table->foreignId('mp_delivery_id')
                ->nullable()
                ->after('retour_id')
                ->constrained('mp_deliveries')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('action_lines', function (Blueprint $table) {
            $table->dropForeign(['mp_delivery_id']);
            $table->dropColumn('mp_delivery_id');
        });
    }
};
