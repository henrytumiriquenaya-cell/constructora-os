<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Añadir columnas a la tabla material si no existen ──
        $materialCols = DB::select("
            SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = 'material'
        ");
        $existingCols = array_column($materialCols, 'COLUMN_NAME');

        if (!in_array('cantidad', $existingCols)) {
            Schema::table('material', function (Blueprint $table) {
                $table->decimal('cantidad', 12, 2)->default(0)->after('descripcion');
            });
        }

        if (!in_array('id_proyecto', $existingCols)) {
            Schema::table('material', function (Blueprint $table) {
                $table->unsignedBigInteger('id_proyecto')->nullable()->after('cantidad');
            });
        }

        // ── Crear tabla movimiento_inventario ──
        if (!Schema::hasTable('movimiento_inventario')) {
            Schema::create('movimiento_inventario', function (Blueprint $table) {
                $table->bigIncrements('id_movimiento');
                $table->unsignedBigInteger('id_material');
                $table->decimal('cantidad', 12, 2);
                $table->unsignedBigInteger('id_proyecto')->nullable();  // destino
                $table->string('descripcion', 500)->nullable();
                $table->string('tipo', 20)->default('salida');          // entrada | salida
                $table->timestamp('fecha')->useCurrent();
                $table->unsignedBigInteger('id_usuario')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventario');
    }
};
