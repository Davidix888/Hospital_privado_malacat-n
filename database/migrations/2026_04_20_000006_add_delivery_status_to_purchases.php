<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('compra')) {
            return;
        }

        if (! Schema::hasColumn('compra', 'estado_entrega')) {
            Schema::table('compra', function (Blueprint $table): void {
                $table->string('estado_entrega', 20)->default('entregada')->after('total');
            });
        }

        DB::table('compra')
            ->whereNull('estado_entrega')
            ->update(['estado_entrega' => 'entregada']);

        DB::table('compra')
            ->where('estado', false)
            ->update(['estado_entrega' => 'pendiente']);
    }

    public function down(): void
    {
        if (Schema::hasTable('compra') && Schema::hasColumn('compra', 'estado_entrega')) {
            Schema::table('compra', function (Blueprint $table): void {
                $table->dropColumn('estado_entrega');
            });
        }
    }
};
