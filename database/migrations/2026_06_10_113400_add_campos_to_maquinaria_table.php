<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            $table->string('tipo', 80)->after('nombre')->nullable();
            $table->string('numero_serie', 60)->after('anio_fabricacion')->nullable();
            $table->decimal('capacidad', 10, 2)->nullable()->after('numero_serie');
            $table->string('unidad_capacidad', 20)->nullable()->after('capacidad');
            $table->text('observaciones')->nullable()->after('costo_hora');
        });
    }

    public function down(): void
    {
        Schema::table('maquinaria', function (Blueprint $table) {
            $table->dropColumn([
                'tipo',
                'numero_serie',
                'capacidad',
                'unidad_capacidad',
                'observaciones',
            ]);
        });
    }
};