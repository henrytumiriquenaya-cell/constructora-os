<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $table = 'material';

    protected $primaryKey = 'id_material';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'codigo_interno',
        'unidad_medida',
        'precio_unitario_ref',
        'stock_minimo',
        'descripcion',
        'activo',
        'cantidad',
        'id_proyecto',
    ];

    public function detallesCompra(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'id_material', 'id_material');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'id_proyecto', 'id_proyecto');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class, 'id_material', 'id_material');
    }
}

