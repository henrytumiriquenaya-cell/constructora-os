<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maquinaria extends Model
{
    protected $table = 'maquinaria';
    protected $primaryKey = 'id_maquinaria';
    public $timestamps = false;
 
    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'marca',
        'modelo',
        'anio_fabricacion',
        'numero_serie',
        'capacidad',
        'unidad_capacidad',
        'estado',
        'costo_hora',
        'observaciones',
    ];
 
    public function asignaciones()
    {
        return $this->hasMany(AsignacionMaquinaria::class, 'id_maquinaria');
    }
}



