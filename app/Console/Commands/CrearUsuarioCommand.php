<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CrearUsuarioCommand extends Command
{
    protected $signature = 'usuarios:crear
                            {--usuario= : Nombre de login}
                            {--password= : Contraseña}
                            {--rol=administrador : Rol (administrador, gerente, contador, jefe_obra, logistica, rrhh, cliente, lector)}
                            {--nombre= : Nombre completo}
                            {--correo= : Correo opcional}';

    protected $description = 'Crea un usuario en la tabla usuario del sistema';

    public function handle(): int
    {
        $login = $this->option('usuario') ?: $this->ask('Usuario de acceso');
        $password = $this->option('password') ?: $this->secret('Contraseña');
        $rol = $this->option('rol') ?: $this->choice(
            'Rol',
            ['administrador', 'gerente', 'contador', 'jefe_obra', 'logistica', 'rrhh', 'cliente', 'lector'],
            0
        );
        $nombre = $this->option('nombre') ?: $this->ask('Nombre completo', $login);
        $correo = $this->option('correo');

        if (! $login || ! $password) {
            $this->error('Usuario y contraseña son obligatorios.');

            return self::FAILURE;
        }

        try {
            if (Usuario::where('usuario', $login)->exists()) {
                $this->error("Ya existe el usuario «{$login}».");

                return self::FAILURE;
            }

            $usuario = Usuario::create([
                'usuario' => $login,
                'nombre_usuario' => $login,
                'nombre_completo' => $nombre,
                'correo' => $correo,
                'contrasena' => Hash::make($password),
                'rol' => $rol,
                'activo' => 1,
            ]);

            $this->info("Usuario creado (ID {$usuario->id_usuario}).");
            $this->line("  Login: {$login}");
            $this->line("  Rol:   {$rol}");
            $this->line('Ingrese en: /login');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Error al crear usuario: '.$e->getMessage());
            $this->line('Si la tabla no existe, ejecute: php artisan migrate');

            return self::FAILURE;
        }
    }
}
