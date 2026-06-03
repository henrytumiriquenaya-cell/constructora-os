<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('usuario')) {
            return;
        }

        Schema::create('usuario', function (Blueprint $table) {
            $table->integer('id_usuario')->autoIncrement();
            $table->string('nombre_completo', 120)->nullable();
            $table->string('nombre_usuario', 80)->nullable();
            $table->string('usuario', 80);
            $table->string('correo', 120)->nullable();
            $table->string('contrasena', 255);
            $table->string('rol', 40);
            $table->boolean('activo')->default(true);
            $table->dateTime('fecha_creacion')->useCurrent();
            
            $table->unique('usuario', 'UX_usuario_login');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};

