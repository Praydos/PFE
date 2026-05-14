<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->upgradeSqlite();

            return;
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE mp_deliveries MODIFY statut VARCHAR(20) NOT NULL DEFAULT 'planifie'");
        } else {
            Schema::table('mp_deliveries', function (Blueprint $table) {
                $table->string('statut', 20)->default('planifie')->change();
            });
        }

        DB::table('mp_deliveries')->whereIn('statut', ['delivered', 'livre'])->update(['statut' => 'livre']);
        DB::table('mp_deliveries')->where('statut', 'returned')->update(['statut' => 'planifie']);
        DB::table('mp_deliveries')->whereNotIn('statut', ['planifie', 'livre'])->update(['statut' => 'planifie']);
    }

    private function upgradeSqlite(): void
    {
        if (! Schema::hasTable('mp_deliveries')) {
            return;
        }

        if (Schema::hasTable('mp_deliveries_old')) {
            Schema::dropIfExists('mp_deliveries');
            Schema::rename('mp_deliveries_old', 'mp_deliveries');
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        if (Schema::hasTable('actions') && Schema::hasColumn('actions', 'mp_delivery_id')) {
            try {
                Schema::table('actions', function (Blueprint $table) {
                    $table->dropForeign(['mp_delivery_id']);
                });
            } catch (\Throwable $e) {
                // FK name may differ; ignore if already absent
            }
        }

        Schema::rename('mp_deliveries', 'mp_deliveries_old');

        foreach (
            [
                'mp_deliveries_numero_unique',
                'mp_deliveries_scope_idx',
                'mp_deliveries_compte_id_mp_product_id_annee_scolaire_id_index',
            ] as $orphanIndex
        ) {
            try {
                DB::statement('DROP INDEX IF EXISTS "'.$orphanIndex.'"');
            } catch (\Throwable $e) {
                // ignore
            }
        }

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

            $table->index(['compte_id', 'mp_product_id', 'annee_scolaire_id'], 'mp_deliveries_scope_idx');
        });

        $rows = DB::table('mp_deliveries_old')->get();
        foreach ($rows as $r) {
            $statut = in_array($r->statut, ['delivered', 'livre'], true) ? 'livre' : 'planifie';
            DB::table('mp_deliveries')->insert([
                'id' => $r->id,
                'numero' => $r->numero,
                'compte_id' => $r->compte_id,
                'contact_id' => $r->contact_id,
                'delegate_id' => $r->delegate_id,
                'annee_scolaire_id' => $r->annee_scolaire_id,
                'mp_product_id' => $r->mp_product_id,
                'date_delivery' => $r->date_delivery,
                'statut' => $statut,
                'created_at' => $r->created_at,
                'updated_at' => $r->updated_at,
            ]);
        }

        Schema::drop('mp_deliveries_old');

        $maxId = (int) DB::table('mp_deliveries')->max('id');
        if ($maxId > 0) {
            DB::table('sqlite_sequence')->where('name', 'mp_deliveries')->delete();
            DB::table('sqlite_sequence')->insert(['name' => 'mp_deliveries', 'seq' => $maxId]);
        }

        if (Schema::hasTable('actions') && Schema::hasColumn('actions', 'mp_delivery_id')) {
            Schema::table('actions', function (Blueprint $table) {
                $table->foreign('mp_delivery_id')
                    ->references('id')
                    ->on('mp_deliveries')
                    ->nullOnDelete();
            });
        }

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        //
    }
};
