<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';
    public $timestamps = false; // Como es SQL Server, usualmente no usamos los timestamps de Laravel

    // Estos son los campos según tu imagen
    protected $fillable = [
        'ci', 'nombres', 'apellidos', 'cargo', 'especialidad', 
        'salario_base', 'modalidad_pago', 'tarifa_hora', 'tarifa_hora_extra',
        'tarifa_jornal', 'tarifa_destajo', 'tipo_contrato', 'fecha_ingreso',
        'fecha_baja', 'telefono', 'correo', 'cuenta_bancaria', 'banco', 'afp', 'activo'
    ];
}
