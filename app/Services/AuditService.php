<?php

namespace App\Services;

use App\Models\LogCambio;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditService
{
    /**
     * Método base de inserción — usa las columnas reales de log_cambios.
     */
    public function log(
        string  $tabla,
        string  $campo,
        string  $valorDespues,
        ?int    $idUsuario    = null,
        ?string $valorAntes   = null,
        ?array  $datosAnteriores = null,
        ?array  $datosNuevos     = null,
        ?int    $idRegistro   = null
    ): void {
        try {
            /** @var Usuario|null $user */
            $user = Auth::user();

            $usuarioStr = $user?->usuario
                ?? $user?->nombre_usuario
                ?? $user?->correo
                ?? 'sistema';

            LogCambio::create([
                'tabla'            => $tabla,
                'id_registro'      => $idRegistro,
                'campo'            => strtoupper($campo),
                'valor_antes'      => $valorAntes   ? mb_substr($valorAntes,   0, 255) : null,
                'valor_despues'    => mb_substr($valorDespues, 0, 255),
                'fecha_cambio'     => now(),
                'usuario'          => $usuarioStr,
                'id_usuario'       => $idUsuario ?? $user?->id_usuario,
                'datos_anteriores' => $datosAnteriores
                    ? json_encode($datosAnteriores, JSON_UNESCAPED_UNICODE)
                    : null,
                'datos_nuevos'     => $datosNuevos
                    ? json_encode($datosNuevos, JSON_UNESCAPED_UNICODE)
                    : null,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Registra inicio o cierre de sesión.
     */
    public function logSession(string $evento, ?int $idUsuario = null, ?string $detalle = null): void
    {
        $tipo      = strtoupper($evento) === 'LOGOUT' ? 'LOGOUT' : 'LOGIN';
        $descripcion = $tipo === 'LOGIN'
            ? 'Inicio de sesión' . ($detalle ? ": {$detalle}" : '')
            : 'Cierre de sesión'  . ($detalle ? ": {$detalle}" : '');

        $this->log(
            tabla:         'sesion',
            campo:         $tipo,
            valorDespues:  $descripcion,
            idUsuario:     $idUsuario,
            idRegistro:    null
        );
    }

    /**
     * Registra un evento CRUD de un modelo Eloquent.
     */
    public function logModelEvent(Model $model, string $operacion): void
    {
        $tabla = Str::afterLast($model->getTable(), '.');
        $tabla = app(PermissionService::class)->normalizeTable($tabla);

        $pk          = $model->getKey();
        $descripcion = match ($operacion) {
            'I' => "Registro creado (ID: {$pk})",
            'U' => "Registro actualizado (ID: {$pk})",
            'D' => "Registro eliminado (ID: {$pk})",
            default => "Operación {$operacion} (ID: {$pk})",
        };

        $datosAnteriores = match ($operacion) {
            'U'     => $model->getOriginal() ?: null,
            'D'     => $model->getAttributes() ?: null,
            default => null,
        };

        $datosNuevos = in_array($operacion, ['I', 'U'], true)
            ? ($model->getAttributes() ?: null)
            : null;

        $this->log(
            tabla:           $tabla,
            campo:           $operacion,
            valorDespues:    $descripcion,
            idUsuario:       null,
            valorAntes:      null,
            datosAnteriores: $datosAnteriores,
            datosNuevos:     $datosNuevos,
            idRegistro:      is_int($pk) ? $pk : null
        );
    }
}
