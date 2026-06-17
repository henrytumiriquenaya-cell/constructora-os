<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Cliente;
use App\Models\Empleado;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $fillable = [
        'id_cliente',
        'id_empleado',
        'nombre_usuario',
        'usuario',
        'correo',
        'contrasena',
        'password',
        'rol',
        'activo',
        'nombre_completo',
    ];

    protected $hidden = [
        'contrasena',
        'password',
        'remember_token',
    ];
   
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }


    public function empleado()
    {
        return $this->belongsTo(
            Empleado::class,
            'id_empleado',
            'id_empleado'
        );
}
    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    public function getAuthPassword(): string
    {
        return (string) ($this->contrasena ?? $this->password ?? '');
    }

    public function getAuthIdentifier()
    {
        return $this->id_usuario;
    }

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LogCambio::class, 'id_usuario', 'id_usuario');
    }

    public function nombreParaMostrar(): string
    {
        return $this->nombre_completo
            ?? $this->nombre_usuario
            ?? $this->usuario
            ?? $this->correo
            ?? 'Usuario';
    }

    public function rolNormalizado(): string
    {
        return app(\App\Services\PermissionService::class)->normalizeRole((string) $this->rol);
    }

    public function estaActivo(): bool
    {
        if (! isset($this->activo)) {
            return true;
        }

        return in_array(strtolower((string) $this->activo), ['1', 'true', 's', 'si', 'sí', 'activo'], true)
            || $this->activo === 1
            || $this->activo === true;
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];
        $currentRole = mb_strtolower(trim((string) $this->rol));

        $aliases = [
            'admin' => ['admin', 'administrador', 'adm', 'adm.'],
            'gerente' => ['gerente'],
            'contab' => ['contab', 'contab.', 'contador'],
            'jefe obra' => ['jefe obra', 'jefe_obra', 'j.obra', 'jefe de obra'],
            'logist' => ['logist', 'logist.', 'logistica', 'logística'],
            'rrhh' => ['rrhh', 'recursos humanos'],
            'cliente' => ['cliente'],
            'lector' => ['lector'],
        ];

        foreach ($roles as $role) {
            $normalized = mb_strtolower(trim((string) $role));
            if ($currentRole === $normalized) {
                return true;
            }

            if (isset($aliases[$normalized]) && in_array($currentRole, $aliases[$normalized], true)) {
                return true;
            }

            foreach ($aliases as $knownRoleAliases) {
                if (in_array($normalized, $knownRoleAliases, true) && in_array($currentRole, $knownRoleAliases, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}

