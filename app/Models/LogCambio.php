<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogCambio extends Model
{
    protected $table = 'log_cambios';

    protected $primaryKey = 'id_log';

    public $timestamps = false;

    // Columnas reales de la tabla en SQL Server
    protected $fillable = [
        'tabla',            // nombre de la tabla afectada (e.g. 'proyecto', 'sesion')
        'id_registro',      // PK del registro afectado (NULL para sesiones)
        'campo',            // campo modificado, o tipo de op: LOGIN/LOGOUT/I/U/D
        'valor_antes',      // valor anterior (o descripción breve)
        'valor_despues',    // valor nuevo (o descripción breve)
        'fecha_cambio',     // datetime del evento
        'usuario',          // nombre de usuario (string, e.g. 'juan.perez')
        'id_usuario',       // FK INT a usuario (puede ser NULL en registros de trigger)
        'datos_anteriores', // JSON completo datos anteriores
        'datos_nuevos',     // JSON completo datos nuevos
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    /**
     * Relación con el modelo Usuario mediante id_usuario (FK app-level).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Devuelve un label legible del campo 'campo' para la vista.
     */
    public function tipoLabel(): string
    {
        return match (strtoupper((string) $this->campo)) {
            'LOGIN'  => 'Login',
            'LOGOUT' => 'Logout',
            'I'      => 'Inserción',
            'U'      => 'Actualización',
            'D'      => 'Eliminación',
            default  => $this->campo ?? '—',
        };
    }

    /**
     * Indica si el registro fue generado por la app (tiene tipo_operacion reconocido).
     */
    public function esEventoApp(): bool
    {
        return in_array(strtoupper((string) $this->campo), ['LOGIN', 'LOGOUT', 'I', 'U', 'D'], true);
    }

    /**
     * Accesor para tipo_operacion (mapeado a campo)
     */
    public function getTipoOperacionAttribute(): string
    {
        return $this->campo ?? '';
    }

    /**
     * Accesor para tabla_afectada (mapeado a tabla)
     */
    public function getTablaAfectadaAttribute(): string
    {
        return $this->tabla ?? '';
    }

    /**
     * Accesor para fecha_hora (mapeado a fecha_cambio)
     */
    public function getFechaHoraAttribute()
    {
        return $this->fecha_cambio;
    }

    /**
     * Accesor para descripcion (mapeado a valor_despues o auto-generado para legacy/triggers)
     */
    public function getDescripcionAttribute(): string
    {
        $tipo = strtoupper((string) $this->campo);
        if (in_array($tipo, ['LOGIN', 'LOGOUT', 'I', 'U', 'D'], true)) {
            return $this->valor_despues ?? '';
        }

        // Caso legacy/trigger: $campo es la columna modificada
        if ($this->campo) {
            $antes = $this->valor_antes ?? 'vacío';
            $despues = $this->valor_despues ?? 'vacío';
            return "Modificó el campo '{$this->campo}' de '{$antes}' a '{$despues}'";
        }

        return $this->valor_despues ?? '—';
    }
}

