<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Proyecto extends Model
{
    protected $table = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    public $timestamps = false;

    protected $fillable = [
        'id_contrato',
        'nombre_proyecto',
        'codigo_proyecto',
        'ubicacion',
        'coordenadas_gps',
        'fecha_inicio_real',
        'fecha_fin_programada',
        'fecha_fin_real',
        'porcentaje_avance',
        'estado',
        'estado_calculated',
        'tipo_obra',
        'superficie_m2'
    ];

    // Relación: Un proyecto pertenece a un contrato
    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'id_contrato');
    }
}
