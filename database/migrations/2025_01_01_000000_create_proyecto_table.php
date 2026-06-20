<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyecto', function (Blueprint $table) {
            // Llave primaria entera e autoincremental
            $table->integer('id_proyecto')->autoIncrement();
            
            $table->integer('id_contrato');
            $table->string('nombre_proyecto', 150);
            $table->string('codigo_proyecto', 20)->unique();
            $table->string('ubicacion', 200);
            $table->string('coordenadas_gps', 50)->nullable();
            
            $table->date('fecha_inicio_real')->nullable();
            $table->date('fecha_fin_programada')->nullable();
            $table->date('fecha_fin_real')->nullable();
            
            $table->decimal('porcentaje_avance', 5, 2)->default(0.00);
            
            // Campos de tipo ENUM con sus opciones
            $estados = ['planificacion', 'en_ejecucion', 'paralizado', 'concluido', 'cancelado', 'abandonado'];
            $table->enum('estado', $estados)->default('planificacion');
            $table->enum('estado_calculado', $estados)->default('planificacion');
            
            $table->string('tipo_obra', 80);
            $table->decimal('superficie_m2', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto');
    }
};
