<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;

class ListarUsuariosCommand extends Command
{
    protected $signature = 'usuarios:listar';

    protected $description = 'Lista los usuarios del sistema (tabla usuario)';

    public function handle(): int
    {
        try {
            $usuarios = Usuario::orderBy('id_usuario')->get();
        } catch (\Throwable $e) {
            $this->error('No se pudo consultar la tabla usuario: '.$e->getMessage());
            $this->line('Ejecute primero: php artisan migrate');

            return self::FAILURE;
        }

        if ($usuarios->isEmpty()) {
            $this->warn('No hay usuarios registrados.');
            $this->line('Cree uno con: php artisan usuarios:crear');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Usuario', 'Nombre', 'Correo', 'Rol', 'Activo'],
            $usuarios->map(fn (Usuario $u) => [
                $u->id_usuario,
                $u->usuario ?? $u->nombre_usuario ?? '—',
                $u->nombre_completo ?? '—',
                $u->correo ?? '—',
                $u->rol,
                isset($u->activo) ? ($u->activo ? 'Sí' : 'No') : 'Sí',
            ])
        );

        return self::SUCCESS;
    }
}
