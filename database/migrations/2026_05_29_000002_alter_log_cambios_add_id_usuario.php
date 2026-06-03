<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hacer id_registro nullable (para registros de sesión sin record ID)
        if (Schema::hasColumn('log_cambios', 'id_registro')) {
            Schema::table('log_cambios', function (Blueprint $table) {
                $table->integer('id_registro')->nullable()->change();
            });
        }

        // Agregar columna id_usuario (FK a usuario) si no existe
        if (! Schema::hasColumn('log_cambios', 'id_usuario')) {
            Schema::table('log_cambios', function (Blueprint $table) {
                $table->integer('id_usuario')->nullable();
                $table->foreign('id_usuario', 'FK_log_cambios_usuario')
                    ->references('id_usuario')
                    ->on('usuario')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        // Revertir: quitar FK y columna id_usuario
        if (Schema::hasColumn('log_cambios', 'id_usuario')) {
            Schema::table('log_cambios', function (Blueprint $table) {
                $table->dropForeign('FK_log_cambios_usuario');
                $table->dropColumn('id_usuario');
            });
        }
    }
};

