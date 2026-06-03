<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'tipo_notificacion',
        'asunto',
        'mensaje',
        'fecha_creacion',
        'leida',
        'fecha_lectura',
        'rol_destino',
        'datos_referencia',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_lectura' => 'datetime',
        'leida' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function marcarComoLeida()
    {
        $this->update([
            'leida' => true,
            'fecha_lectura' => now(),
        ]);
    }
}

