<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogCambio extends Model
{
    protected $table = 'log_cambios';
    protected $primaryKey = 'id_log';
    public $timestamps = false;

    protected $fillable = [
        'tabla',
        'accion',           // ← nueva: I / U / D / LOGIN / LOGOUT
        'id_registro',
        'campo',
        'valor_antes',
        'valor_despues',
        'fecha_cambio',
        'usuario',
        'id_usuario',
        'ip_address',       // ← nueva
        'datos_anteriores',
        'datos_nuevos',
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // ── Accesor tipo_operacion ──────────────────────────────────────────
    // Prioridad: columna accion (triggers) → campo (app legacy)
    public function getTipoOperacionAttribute(): string
    {
        $accion = strtoupper((string) ($this->attributes['accion'] ?? ''));
        if ($accion !== '') return $accion;
        return strtoupper((string) ($this->campo ?? ''));
    }

    // ── Accesor tabla_afectada ──────────────────────────────────────────
    public function getTablaAfectadaAttribute(): string
    {
        return $this->tabla ?? '';
    }

    // ── Accesor fecha_hora ──────────────────────────────────────────────
    public function getFechaHoraAttribute()
    {
        return $this->fecha_cambio;
    }

    // ── Accesor descripcion ─────────────────────────────────────────────
    public function getDescripcionAttribute(): string
    {
        $tipo = $this->tipo_operacion;

        // Eventos de sesión
        if (in_array($tipo, ['LOGIN', 'LOGOUT'], true)) {
            return $this->valor_despues ?? '';
        }

        // INSERT desde trigger → valor_despues tiene el resumen
        if ($tipo === 'I') {
            return $this->valor_despues ?? 'Registro creado';
        }

        // DELETE desde trigger → valor_antes tiene el resumen
        if ($tipo === 'D') {
            return $this->valor_antes ?? 'Registro eliminado';
        }

        // UPDATE → campo es el nombre de la columna modificada
        if ($tipo === 'U') {
            if ($this->campo === 'registro_completo') {
                return $this->valor_despues ?? $this->valor_antes ?? '';
            }
            $antes   = $this->valor_antes   ?? 'vacío';
            $despues = $this->valor_despues ?? 'vacío';
            return "Modificó '{$this->campo}' de '{$antes}' a '{$despues}'";
        }

        return $this->valor_despues ?? '—';
    }

    // ── Helpers ─────────────────────────────────────────────────────────
    public function tipoLabel(): string
    {
        return match ($this->tipo_operacion) {
            'LOGIN'  => 'Login',
            'LOGOUT' => 'Logout',
            'I'      => 'Inserción',
            'U'      => 'Actualización',
            'D'      => 'Eliminación',
            default  => $this->tipo_operacion ?: '—',
        };
    }

    public function esEventoApp(): bool
    {
        return in_array($this->tipo_operacion, ['LOGIN', 'LOGOUT', 'I', 'U', 'D'], true);
    }
}