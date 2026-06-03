<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudad';
    protected $primaryKey = 'id_ciudad';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'departamento',
        'pais',
    ];

    // Relaciones
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_ciudad');
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class, 'id_ciudad');
    }
}

