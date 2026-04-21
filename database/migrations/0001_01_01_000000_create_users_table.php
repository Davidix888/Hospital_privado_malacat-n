<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('cargo')) {
            Schema::create('cargo', function (Blueprint $table) {
                $table->id('id_cargo');
                $table->string('nombre', 80)->unique();
                $table->string('descripcion')->nullable();
            });
        }

        if (! Schema::hasTable('empleado')) {
            Schema::create('empleado', function (Blueprint $table) {
                $table->id('id_empleado');
                $table->unsignedBigInteger('id_cargo');
                $table->string('nombres', 80);
                $table->string('apellidos', 80);
                $table->char('dpi', 13)->unique();
                $table->string('direccion', 150);
                $table->boolean('estado')->default(true);

                $table->foreign('id_cargo')->references('id_cargo')->on('cargo');
            });
        }

        if (! Schema::hasTable('rol')) {
            Schema::create('rol', function (Blueprint $table) {
                $table->id('id_rol');
                $table->string('nombre', 50)->unique();
                $table->string('descripcion')->nullable();
            });
        }

        if (! Schema::hasTable('usuario')) {
            Schema::create('usuario', function (Blueprint $table) {
                $table->id('id_usuario');
                $table->unsignedInteger('id_empleado')->unique();
                $table->unsignedInteger('id_rol');
                $table->string('username', 50)->unique();
                $table->string('password');
                $table->boolean('estado')->default(true);
                $table->timestamp('fecha_creacion')->useCurrent();

                $table->foreign('id_empleado')->references('id_empleado')->on('empleado');
                $table->foreign('id_rol')->references('id_rol')->on('rol');
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('username')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('rol');
        Schema::dropIfExists('empleado');
        Schema::dropIfExists('cargo');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
