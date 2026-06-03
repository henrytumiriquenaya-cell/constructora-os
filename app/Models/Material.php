<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    ];

    public function detallesCompra(): HasMany
    {
        return $this->hasMany(DetalleCompra::class, 'id_material', 'id_material');
    }
}

