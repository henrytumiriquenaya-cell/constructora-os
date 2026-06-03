<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = [
        'id_proyecto', 'id_documento', 'tipo_permiso', 'numero_permiso', 
        'entidad_emisora', 'fecha_solicitud', 'fecha_emision', 
        'fecha_vencimiento', 'estado', 'costo_tramite'
    ];

    // Relación con Proyecto
    public function proyecto() {
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
