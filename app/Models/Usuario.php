<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\LogCambio;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $with = ['roles'];

    protected $fillable = [
        'id_cliente',
        'id_empleado',
        'nombre_usuario',
        'usuario',
        'correo',
        'contrasena',
        'activo',
        'nombre_completo',
    ];

    protected $hidden = [
        'contrasena',
        'password',
        'remember_token',
    ];

    /* =========================
       RELACIONES
    ========================= */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,
            'usuario_roles',
            'id_usuario',
            'id_rol'
        )->select('roles.id_rol', 'roles.nombre');
    }

    /* =========================
       AUTH LARAVEL
    ========================= */

    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    public function getAuthIdentifier()
    {
        return $this->id_usuario;
    }

    public function getAuthPassword(): string
    {
        return $this->contrasena ?? '';
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    /* =========================
       ROLES
    ========================= */

   public function hasRole(string|array $roles): bool
    {
        $roles = collect(is_array($roles) ? $roles : [$roles])
            ->map(fn ($r) => strtolower($r));

        return $this->roles
            ->pluck('nombre')
            ->map(fn ($r) => strtolower($r))
            ->intersect($roles)
            ->isNotEmpty();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }

    public function roleName(): string
    {
        return $this->roles->first()?->nombre ?? 'Sin rol';
    }

    /* =========================
       UTILIDADES
    ========================= */

    public function nombreParaMostrar(): string
    {
        return $this->nombre_completo
            ?? $this->nombre_usuario
            ?? $this->usuario
            ?? $this->correo
            ?? 'Usuario';
    }

    public function estaActivo(): bool
    {
        return (bool) $this->activo;
    }
}