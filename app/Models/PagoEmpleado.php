<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoEmpleado extends Model
{
    protected $table = 'pago_empleado';
    protected $primaryKey = 'id_pago_emp';
    public $timestamps = false;

    protected $fillable = [
        'id_pago', 'id_empleado', 'tipo_haber', 'periodo_mes', 
        'dias_trabajados', 'modalidad_aplicada', 'horas_trabajadas', 
        'tarifa_aplicada', 'monto_calculado'
    ];

    // Relación con el Empleado para ver su nombre en la tabla
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado');
    }
}
