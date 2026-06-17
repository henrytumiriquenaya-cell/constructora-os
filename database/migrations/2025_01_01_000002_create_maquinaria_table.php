<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maquinaria', function (Blueprint $table) {
            // 1. Campos base iniciales
            $table->id('id_maquinaria');
            $table->string('codigo_inventario', 30)->unique();
            $table->string('nombre', 100);
            $table->string('marca', 60);
            $table->string('modelo', 60);
            $table->integer('anio_fabricacion')->nullable();
            
            // NOTA: Aquí tu otra migración inyectará 'tipo', 'numero_serie', 'capacidad' y 'unidad_capacidad'
            
            // 2. Campos finales que deben existir para que la otra migración se apoye en ellos
            $table->enum('tipo_propiedad', ['propio', 'arrendado']);
            $table->enum('estado_actual', ['disponible', 'en_uso', 'en_mantenimiento', 'fuera_servicio']);
            $table->decimal('costo_hora', 10, 2);
            $table->date('fecha_adquisicion')->nullable();
            
            // NOTA: Aquí tu otra migración inyectará 'observaciones' justo después de 'costo_hora'

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maquinaria');
    }
};
