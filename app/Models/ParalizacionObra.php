<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParalizacionObra extends Model
{
    protected $table = 'paralizacion_obra';
    protected $primaryKey = 'id_paralizacion';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto',
        'id_cuota',
        'motivo',
        'descripcion',
        'fecha_inicio_par',
        'fecha_fin_par',
        'registrado_por',
        'estado'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
