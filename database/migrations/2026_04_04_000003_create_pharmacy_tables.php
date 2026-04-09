<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('categoria_medicamento')) {
            Schema::create('categoria_medicamento', function (Blueprint $table): void {
                $table->id('id_categoria');
                $table->string('nombre', 100)->unique();
                $table->string('descripcion')->nullable();
            });
        }

        if (! Schema::hasTable('medicamento')) {
            Schema::create('medicamento', function (Blueprint $table): void {
                $table->id('id_medicamento');
                $table->unsignedBigInteger('id_categoria');
                $table->string('nombre', 120);
                $table->string('descripcion')->nullable();
                $table->boolean('estado')->default(true);

                $table->foreign('id_categoria')->references('id_categoria')->on('categoria_medicamento');
            });
        }

        if (! Schema::hasTable('presentacion')) {
            Schema::create('presentacion', function (Blueprint $table): void {
                $table->id('id_presentacion');
                $table->string('nombre', 100)->unique();
                $table->string('descripcion')->nullable();
            });
        }

        if (! Schema::hasTable('medicamento_presentacion')) {
            Schema::create('medicamento_presentacion', function (Blueprint $table): void {
                $table->id('id_medicamento_presentacion');
                $table->unsignedBigInteger('id_medicamento');
                $table->unsignedBigInteger('id_presentacion');
                $table->decimal('precio_venta', 10, 2);
                $table->integer('stock_minimo');
                $table->boolean('estado')->default(true);

                $table->foreign('id_medicamento')->references('id_medicamento')->on('medicamento');
                $table->foreign('id_presentacion')->references('id_presentacion')->on('presentacion');
            });
        }

        if (! Schema::hasTable('proveedor')) {
            Schema::create('proveedor', function (Blueprint $table): void {
                $table->id('id_proveedor');
                $table->string('nombre', 120);
                $table->string('direccion', 150);
                $table->boolean('estado')->default(true);
            });
        }

        if (! Schema::hasTable('correo_proveedor')) {
            Schema::create('correo_proveedor', function (Blueprint $table): void {
                $table->id('id_correo_proveedor');
                $table->unsignedBigInteger('id_proveedor');
                $table->string('correo', 120)->unique();
                $table->boolean('estado')->default(true);

                $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedor');
            });
        }

        if (! Schema::hasTable('telefono_proveedor')) {
            Schema::create('telefono_proveedor', function (Blueprint $table): void {
                $table->id('id_telefono_proveedor');
                $table->unsignedBigInteger('id_proveedor');
                $table->string('numero', 30);
                $table->string('tipo', 40);
                $table->boolean('estado')->default(true);

                $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedor');
            });
        }

        if (! Schema::hasTable('compra')) {
            Schema::create('compra', function (Blueprint $table): void {
                $table->id('id_compra');
                $table->unsignedBigInteger('id_proveedor');
                $table->unsignedBigInteger('id_usuario');
                $table->timestamp('fecha')->useCurrent();
                $table->decimal('total', 10, 2);
                $table->boolean('estado')->default(true);

                $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedor');
                $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            });
        }

        if (! Schema::hasTable('detalle_compra')) {
            Schema::create('detalle_compra', function (Blueprint $table): void {
                $table->id('id_detalle_compra');
                $table->unsignedBigInteger('id_compra');
                $table->unsignedBigInteger('id_medicamento_presentacion');
                $table->integer('cantidad');
                $table->decimal('precio_compra', 10, 2);
                $table->decimal('subtotal', 10, 2);

                $table->foreign('id_compra')->references('id_compra')->on('compra');
                $table->foreign('id_medicamento_presentacion')->references('id_medicamento_presentacion')->on('medicamento_presentacion');
            });
        }

        if (! Schema::hasTable('lote')) {
            Schema::create('lote', function (Blueprint $table): void {
                $table->id('id_lote');
                $table->unsignedBigInteger('id_detalle_compra');
                $table->string('numero_lote', 80);
                $table->date('fecha_vencimiento');
                $table->date('fecha_ingreso');
                $table->boolean('estado')->default(true);

                $table->foreign('id_detalle_compra')->references('id_detalle_compra')->on('detalle_compra');
            });
        }

        if (! Schema::hasTable('inventario')) {
            Schema::create('inventario', function (Blueprint $table): void {
                $table->id('id_inventario');
                $table->unsignedBigInteger('id_lote');
                $table->integer('cantidad_actual');
                $table->timestamp('fecha_actualizacion')->useCurrent();

                $table->foreign('id_lote')->references('id_lote')->on('lote');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('lote');
        Schema::dropIfExists('detalle_compra');
        Schema::dropIfExists('compra');
        Schema::dropIfExists('telefono_proveedor');
        Schema::dropIfExists('correo_proveedor');
        Schema::dropIfExists('proveedor');
        Schema::dropIfExists('medicamento_presentacion');
        Schema::dropIfExists('presentacion');
        Schema::dropIfExists('medicamento');
        Schema::dropIfExists('categoria_medicamento');
    }
};
