<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'id_rol';

    public $timestamps = false;

    protected $fillable = [
        'nombre'
    ];

    // RELACIÓN: un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuario_roles',
            'id_rol',
            'id_usuario'
        );
    }
}