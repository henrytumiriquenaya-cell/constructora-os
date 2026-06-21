<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Support\Str;

class PermissionService
{
    public function getUserRoles(?Usuario $user): array
    {
        if (!$user) return [];

        return $user->roles
            ->pluck('nombre')
            ->map(fn ($r) => $this->normalizeRole($r))
            ->unique()
            ->values()
            ->toArray();
    }

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
        if (! $user) return [];

        $table = $this->normalizeTable($table);

        $roles = $this->getUserRoles($user);

        // 🔥 ADMIN GLOBAL
        if (in_array('admin', $roles, true)) {
            return ['S', 'I', 'U', 'D'];
        }

        $perms = [];

        foreach ($roles as $role) {

            if (in_array($role, config('permissions.global_read_roles', []), true)) {
                $perms[] = 'S';
            }

            switch ($role) {

                case 'gerente':
                   case 'gerente':
                        $perms = array_merge($perms, ['S','I','U']);
                        break;

                case 'contador':
                    $perms = ['S'];

                    if (in_array($table, config('permissions.finanzas', []), true)) {
                        $perms = array_merge($perms, ['I','U','S']);
                    }
                    break;

                case 'logistica':
                    if (in_array($table, config('permissions.compras', []), true)) {
                        $perms = array_merge($perms, ['S','I','U']);
                    }
                    break;

                case 'rrhh':
                    if (in_array($table, config('permissions.rrhh_tables', []), true)) {
                        $perms = array_merge($perms, ['S','I','U']);
                    }
                    break;

                case 'cliente':
                    if (in_array($table, config('permissions.cliente_tables', []), true)) {
                        $perms[] = 'S';
                    }
                    break;

                case 'lector':
                    if (in_array($table, config('permissions.lector_tables', []), true)) {
                        $perms[] = 'S';
                    }
                    break;
                case 'director':
                    $perms = array_merge($perms, ['S','I','U']);
                    break;

                case 'residente':
                    if (
                        in_array($table, config('permissions.obra', []), true)
                        || in_array($table, ['proyecto','maquinaria','asignacion_maquinaria'], true)
                    ) {
                        $perms = array_merge($perms, ['S','I','U']);
                    }
                    break;

                case 'supervisor':
                    if (
                        in_array($table, [
                            'registro_horas',
                            'paralizacion',
                            'uso_material',
                            'asignacion_empleado'
                        ], true)
                    ) {
                        $perms = array_merge($perms, ['S','I']);
                    }
                    break;

                case 'obrero':
                    if ($table === 'registro_horas') {
                        $perms[] = 'S';
                    }
                    break;
            }
        }

        return array_values(array_unique($perms));
    }

    public function can(?Usuario $user, string $table, string $operation): bool
    {
        return in_array(
            strtoupper($operation),
            $this->permissionsFor($user, $table),
            true
        );
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
        return $routeName
            ? config("permissions.route_tables.{$routeName}")
            : null;
    }

    public function normalizeTable(string $table): string
    {
        return [
            'registro_horas_diario' => 'registro_horas',
            'paralizacion_obra' => 'paralizacion',
        ][$table] ?? $table;
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