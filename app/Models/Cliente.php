<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // Agregamos el esquema antes del nombre de la tabla
    protected $table = 'cliente'; 

    protected $primaryKey = 'id_cliente';
    public $timestamps = false; 

    protected $fillable = [
        'id_ciudad',
        'tipo_cliente',
        'nombre_razon',
        'documento_identidad',
        'telefono_principal',
        'telefono_secundario',
        'correo',
        'direccion',
        'fecha_registro',
        'estado'
    ];
}
