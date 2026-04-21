<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('paciente')) {
            Schema::create('paciente', function (Blueprint $table): void {
                $table->id('id_paciente');
                $table->string('nombres', 120);
                $table->string('apellidos', 120);
                $table->date('fecha_nacimiento');
                $table->string('sexo', 20);
                $table->string('direccion', 180);
                $table->boolean('estado')->default(true);
            });
        }

        if (! Schema::hasTable('venta')) {
            Schema::create('venta', function (Blueprint $table): void {
                $table->id('id_venta');
                $table->unsignedBigInteger('id_paciente')->nullable();
                $table->unsignedBigInteger('id_usuario');
                $table->timestamp('fecha')->useCurrent();
                $table->decimal('total', 10, 2);
                $table->boolean('estado')->default(true);

                $table->foreign('id_paciente')->references('id_paciente')->on('paciente');
                $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            });
        }

        if (! Schema::hasTable('detalle_venta')) {
            Schema::create('detalle_venta', function (Blueprint $table): void {
                $table->id('id_detalle_venta');
                $table->unsignedBigInteger('id_venta');
                $table->unsignedBigInteger('id_medicamento_presentacion');
                $table->integer('cantidad');
                $table->decimal('precio_unitario', 10, 2);
                $table->decimal('subtotal', 10, 2);

                $table->foreign('id_venta')->references('id_venta')->on('venta');
                $table->foreign('id_medicamento_presentacion')->references('id_medicamento_presentacion')->on('medicamento_presentacion');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
        Schema::dropIfExists('venta');
        Schema::dropIfExists('paciente');
    }
};
