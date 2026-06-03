<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraTerminada extends Model
{
    protected $table = 'obras_terminadas';
    protected $primaryKey = 'id_obra_terminada';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'fecha_terminacion_real',
        'fecha_acta_recepcion',
        'numero_acta',
        'monto_final',
        'monto_adicionales',
        'monto_deducciones',
        'usuario_cierre',
        'observaciones'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
