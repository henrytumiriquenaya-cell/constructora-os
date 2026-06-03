<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('log_cambios')) {
            Schema::table('log_cambios', function (Blueprint $table) {
                if (! Schema::hasColumn('log_cambios', 'datos_anteriores')) {
                    $table->text('datos_anteriores')->nullable();
                }
                if (! Schema::hasColumn('log_cambios', 'datos_nuevos')) {
                    $table->text('datos_nuevos')->nullable();
                }
            });
            return;
        }

        Schema::create('log_cambios', function (Blueprint $table) {
            $table->integer('id_log')->autoIncrement();
            $table->integer('id_usuario')->nullable();
            $table->string('tabla', 80);
            $table->integer('id_registro')->nullable();
            $table->string('campo', 80);
            $table->string('valor_antes', 255)->nullable();
            $table->string('valor_despues', 255)->nullable();
            $table->dateTime('fecha_cambio')->useCurrent();
            $table->string('usuario', 80)->nullable();
            $table->text('datos_anteriores')->nullable();
            $table->text('datos_nuevos')->nullable();

            $table->foreign('id_usuario', 'FK_log_cambios_usuario')
                ->references('id_usuario')
                ->on('usuario')
                ->onDelete('set null');

            $table->index('id_usuario', 'IX_log_cambios_usuario');
            $table->index('fecha_cambio', 'IX_log_cambios_fecha');
            $table->index('tabla', 'IX_log_cambios_tabla');
            $table->index('campo', 'IX_log_cambios_campo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_cambios');
    }
};

