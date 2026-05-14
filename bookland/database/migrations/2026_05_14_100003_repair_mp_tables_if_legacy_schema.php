<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fixes environments where an older or partial mp_products table existed
 * before the official MP module migration (wrong column set).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('mp_products')) {
            return;
        }

        if (Schema::hasColumn('mp_products', 'code_article')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        if (Schema::hasTable('actions') && Schema::hasColumn('actions', 'mp_delivery_id')) {
            Schema::table('actions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('mp_delivery_id');
            });
        }

        Schema::dropIfExists('mp_deliveries');
        Schema::dropIfExists('mp_products');

        Schema::create('mp_products', function (Blueprint $table) {
            $table->id();
            $table->string('code_article')->unique();
            $table->string('editeur');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('mp_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('compte_id')->constrained('comptes')->restrictOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->restrictOnDelete();
            $table->foreignId('delegate_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('annee_scolaire_id')->constrained('annees_scolaires')->restrictOnDelete();
            $table->foreignId('mp_product_id')->constrained('mp_products')->restrictOnDelete();
            $table->date('date_delivery');
            $table->string('statut', 20)->default('planifie');
            $table->timestamps();

            $table->index(['compte_id', 'mp_product_id', 'annee_scolaire_id']);
        });

        if (Schema::hasTable('actions')) {
            Schema::table('actions', function (Blueprint $table) {
                $table->foreignId('mp_delivery_id')
                    ->nullable()
                    ->after('module_id')
                    ->constrained('mp_deliveries')
                    ->nullOnDelete();
            });
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        //
    }
};
