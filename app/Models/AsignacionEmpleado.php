<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionEmpleado extends Model
{
    protected $table = 'asignacion_empleado';
    protected $primaryKey = 'id_asignacion';
    public $timestamps = false;

    protected $fillable = [
        'id_empleado', 'id_proyecto', 'rol_en_proyecto', 
        'fecha_inicio_asig', 'fecha_fin_asig', 'horas_semana', 
        'tarifa_hora', 'observaciones'
    ];

    // Relación con Empleado
    public function empleado() {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }

    // Relación con Proyecto
    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
