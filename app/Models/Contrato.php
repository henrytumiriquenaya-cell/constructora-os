<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contrato';
    protected $primaryKey = 'id_contrato';
    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'numero_contrato',
        'fecha_firma',
        'fecha_inicio',
        'fecha_fin_prevista',
        'fecha_fin_real',
        'monto_total',
        'moneda',
        'tipo_contrato',
        'estado',
        'descripcion',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function proyecto()
    {
        return $this->hasOne(Proyecto::class, 'id_contrato');
    }

    public function cuotas()
    {
        return $this->hasMany(CuotasPago::class, 'id_contrato');
    }
}
