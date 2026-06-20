<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material', function (Blueprint $table) {
            // Llave primaria personalizada según tu controlador
            $table->id('id_material'); 
            
            // Campos validados en el controlador
            $table->string('nombre', 255);
            $table->string('codigo_interno', 50)->unique();
            $table->string('categoria', 100);
            $table->string('unidad_medida', 20);
            $table->decimal('precio_unitario_ref', 12, 2);
            $table->decimal('stock_minimo', 12, 2);
            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material');
    }
};
