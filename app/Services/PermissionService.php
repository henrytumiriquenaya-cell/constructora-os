<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Str;

class PermissionService
{
    public function normalizeRole(?string $rol): string
    {
        $rol = Str::lower(trim((string) $rol));

        foreach (config('permissions.roles', []) as $canonical => $meta) {
            if ($rol === $canonical) {
                return $canonical;
            }
            foreach ($meta['aliases'] ?? [] as $alias) {
                if ($rol === Str::lower($alias)) {
                    return $canonical;
                }
            }
        }

        return $rol;
    }

    public function permissionsFor(?Usuario $user, string $table): array
    {
        if (! $user) {
            return [];
        }

        $table = $this->normalizeTable($table);
        $role = $this->normalizeRole($user->rol);

        if ($role === 'administrador') {
            return ['S', 'I', 'U', 'D'];
        }

        $perms = [];

        if (in_array($role, config('permissions.global_read_roles', []), true)) {
            $perms[] = 'S';
        }

        switch ($role) {
            case 'gerente':
                if ($table === 'proyecto') {
                    $perms[] = 'U';
                }
                break;

            case 'contador':
                if (in_array($table, config('permissions.finanzas', []), true)) {
                    $perms = array_merge($perms, ['I', 'U']);
                }
                break;

            case 'jefe_obra':
                if (in_array($table, config('permissions.obra', []), true)) {
                    $perms = array_merge($perms, ['I', 'U']);
                }
                if ($table === 'uso_material') {
                    $perms = array_merge($perms, ['I', 'U']);
                }
                if ($table === 'proyecto') {
                    $perms[] = 'U';
                }
                if (in_array($table, config('permissions.jefe_obra_insert_only', []), true)) {
                    $perms[] = 'I';
                }
                break;

            case 'logistica':
                if (in_array($table, config('permissions.compras', []), true) || $table === 'mantenimiento') {
                    $perms = array_merge($perms, ['I', 'U']);
                }
                break;

            case 'rrhh':
                if (in_array($table, config('permissions.rrhh_tables', []), true)) {
                    $perms = array_merge($perms, ['I', 'U']);
                }
                if (in_array($table, ['pago', 'pago_empleado'], true)) {
                    $perms[] = 'I';
                }
                break;

            case 'cliente':

                $perms = in_array($table, config('permissions.cliente_tables', []), true)
                    ? ['S']
                    : [];

                break;

            case 'lector':
                $perms = in_array($table, config('permissions.lector_tables', []), true) ? ['S'] : [];
                break;
        }

        return array_values(array_unique($perms));
    }

    public function can(?Usuario $user, string $table, string $operation): bool
    {
        $operation = strtoupper($operation);

        return in_array($operation, $this->permissionsFor($user, $table), true);
    }

    public function canSelect(?Usuario $user, string $table): bool
    {
        return $this->can($user, $table, 'S');
    }

    public function canInsert(?Usuario $user, string $table): bool
    {
        return $this->can($user, $table, 'I');
    }

    public function canUpdate(?Usuario $user, string $table): bool
    {
        return $this->can($user, $table, 'U');
    }

    public function canDelete(?Usuario $user, string $table): bool
    {
        return $this->can($user, $table, 'D');
    }

    public function operationFromRequest(string $method): string
    {
        return match (strtoupper($method)) {
            'POST' => 'I',
            'PUT', 'PATCH' => 'U',
            'DELETE' => 'D',
            default => 'S',
        };
    }

    public function tableForRoute(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        return config("permissions.route_tables.{$routeName}");
    }

    public function normalizeTable(string $table): string
    {
        $aliases = [
            'registro_horas_diario' => 'registro_horas',
            'paralizacion_obra' => 'paralizacion',
        ];

        return $aliases[$table] ?? $table;
    }

    public function showOperativaMenu(?Usuario $user): bool
    {
        foreach (config('permissions.menu', []) as $item) {
            if ($this->canSelect($user, $item['table'])) {
                return true;
            }
        }

        return false;
    }

    public function showRrhhMenu(?Usuario $user): bool
    {
        foreach (config('permissions.menu_rrhh', []) as $item) {
            if ($this->canSelect($user, $item['table'])) {
                return true;
            }
        }

        return false;
    }
}
