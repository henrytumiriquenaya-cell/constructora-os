<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado;

class AsignacionMaquinaria extends Model
{
    protected $table = 'asignacion_maquinaria';
    protected $primaryKey = 'id_asig_maq';
    public $timestamps = false;
 
    protected $fillable = [
        'id_maquinaria',
        'id_proyecto',
        'id_empleado',
        'fecha_inicio',
        'fecha_fin',
        'horas_usadas',
        'costo_total',
    ];
 
    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'id_maquinaria');
    }
 
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }
}


