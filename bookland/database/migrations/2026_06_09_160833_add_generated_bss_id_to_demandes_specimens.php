<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demandes_specimens', function (Blueprint $table) {
            $table->unsignedBigInteger('generated_bss_id')->nullable()->after('original_bss_id');
            $table->foreign('generated_bss_id')->references('id')->on('bsses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes_specimens', function (Blueprint $table) {
            $table->dropForeign(['generated_bss_id']);
            $table->dropColumn('generated_bss_id');
        });
    }
};
