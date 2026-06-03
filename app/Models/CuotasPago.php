<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuotasPago extends Model
{
    protected $table = 'cuotas_pago';
    protected $primaryKey = 'id_cuota';
    public $timestamps = false;

    protected $fillable = [
        'id_contrato',
        'numero_cuota',
        'monto_cuota',
        'fecha_vencimiento',
        'fecha_pago_real',
        'monto_pagado',
        'estado_cuota',
        'observaciones',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'id_contrato');
    }
}
