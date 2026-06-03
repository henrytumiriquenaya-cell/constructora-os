<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizacion';
    protected $primaryKey = 'id_presupuesto';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'id_empleado',
        'version',
        'fecha_elaboracion',
        'monto_plan_materiales',
        'monto_plan_mano_obra',
        'monto_plan_maquinaria',
        'monto_plan_gastos_adm',
        // monto_total_planificado es calculado por trigger, no se llena desde Laravel
        'estado',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }
}
