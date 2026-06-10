<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table      = 'movimiento_inventario';
    protected $primaryKey = 'id_movimiento';
    public    $timestamps = false;

    protected $fillable = [
        'id_material',
        'cantidad',
        'id_proyecto',
        'descripcion',
        'tipo',
        'fecha',
        'id_usuario',
    ];

    protected $casts = [
        'fecha'    => 'datetime',
        'cantidad' => 'decimal:2',
    ];

    // ── Relaciones ──────────────────────────────────────────

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
