<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('proveedor')) {
            Schema::table('proveedor', function (Blueprint $table): void {
                if (! Schema::hasColumn('proveedor', 'correo')) {
                    $table->string('correo', 120)->nullable()->after('direccion');
                }

                if (! Schema::hasColumn('proveedor', 'telefono')) {
                    $table->string('telefono', 30)->nullable()->after('correo');
                }
            });

            if (Schema::hasTable('correo_proveedor')) {
                $emails = DB::table('correo_proveedor')
                    ->select('id_proveedor', 'correo')
                    ->orderBy('id_correo_proveedor')
                    ->get()
                    ->unique('id_proveedor');

                foreach ($emails as $email) {
                    DB::table('proveedor')
                        ->where('id_proveedor', $email->id_proveedor)
                        ->whereNull('correo')
                        ->update(['correo' => $email->correo]);
                }
            }

            if (Schema::hasTable('telefono_proveedor')) {
                $phones = DB::table('telefono_proveedor')
                    ->select('id_proveedor', 'numero')
                    ->orderByRaw("CASE WHEN tipo = 'Principal' THEN 0 ELSE 1 END")
                    ->orderBy('id_telefono_proveedor')
                    ->get()
                    ->unique('id_proveedor');

                foreach ($phones as $phone) {
                    DB::table('proveedor')
                        ->where('id_proveedor', $phone->id_proveedor)
                        ->whereNull('telefono')
                        ->update(['telefono' => $phone->numero]);
                }
            }
        }

        if (Schema::hasTable('venta') && Schema::hasColumn('venta', 'id_paciente')) {
            $driver = DB::getDriverName();

            if ($driver === 'pgsql') {
                DB::statement('ALTER TABLE venta ALTER COLUMN id_paciente DROP NOT NULL');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('proveedor')) {
            Schema::table('proveedor', function (Blueprint $table): void {
                if (Schema::hasColumn('proveedor', 'telefono')) {
                    $table->dropColumn('telefono');
                }

                if (Schema::hasColumn('proveedor', 'correo')) {
                    $table->dropColumn('correo');
                }
            });
        }

        if (Schema::hasTable('venta') && Schema::hasColumn('venta', 'id_paciente') && DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE venta ALTER COLUMN id_paciente SET NOT NULL');
        }
    }
};
