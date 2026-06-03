<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionMaquinaria extends Model
{
    protected $table = 'asignacion_maquinaria';
    protected $primaryKey = 'id_asignacion_maq';
    public $timestamps = false;
 
    protected $fillable = [
        'id_maquinaria',
        'id_proyecto',
        'fecha_inicio',
        'fecha_fin',
        'horas_asignadas',
        'horas_usadas',
        'costo_hora_aplicado',
        'operador',
        'observaciones',
    ];
 
    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'id_maquinaria');
    }
 
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}

