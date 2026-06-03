<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroHoraDiario extends Model
{
    protected $table = 'registro_horas_diario';
    protected $primaryKey = 'id_registro';
    public $timestamps = false; // Usas fecha_registro manualmente

    protected $fillable = [
        'id_empleado',
        'id_proyecto',
        'fecha_trabajo',
        'es_domingo',
        'es_feriado',
        'id_feriado',
        'hora_entrada',
        'hora_salida',
        'horas_normales',
        'horas_extra_diurnas',
        'horas_extra_nocturnas',
        'tarifa_hora_normal',
        'recargo_pct_dia',
        'recargo_pct_extra_diurna',
        'recargo_pct_extra_nocturna',
        'monto_horas_normales',
        'monto_horas_extra_diurnas',
        'monto_horas_extra_nocturnas',
        'monto_total_dia',
        'observaciones',
        'registrado_por',
        'fecha_registro'
    ];

    // Relación con el Proyecto (Gestión Operativa)
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    // Relación con el Empleado (Módulo RRHH - Lo crearemos después)
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }
}
