<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = false;
 
    protected $fillable = [
        'id_ciudad',
        'razon_social',
        'nit',
        'contacto_nombre',
        'telefono',
        'correo',
        'direccion',
        'categoria',
        'calificacion',
        'activo',
    ];
 
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad');
    }
 
    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_proveedor');
    }
}

