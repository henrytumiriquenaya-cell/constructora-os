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
        Schema::create('obras_terminadas', function (Blueprint $table) {
            $table->increments('id_obra_terminada');
            $table->integer('id_proyecto');
            $table->date('fecha_terminacion_real')->nullable();
            $table->date('fecha_acta_recepcion')->nullable();
            $table->string('numero_acta', 50)->nullable();
            $table->decimal('monto_final', 15, 2)->nullable();
            $table->decimal('monto_adicionales', 15, 2)->nullable();
            $table->decimal('monto_deducciones', 15, 2)->nullable();
            $table->string('usuario_cierre', 100)->nullable();
            $table->text('observaciones')->nullable();

            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyecto')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obras_terminadas');
    }
};
