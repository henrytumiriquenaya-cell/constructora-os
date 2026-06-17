<?php

namespace App\Observers;

use App\Models\LogCambio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    private array $tablasAuditadas = [
        'proyecto',
        'contrato',
        'empleado',
        'pago',
        'compra',
        'maquinaria',
        'materiales',
        'usuario',
    ];

    /**
     * Campos que nunca deben quedar en texto plano (ni siquiera como hash)
     * dentro de log_cambios, sin importar la tabla.
     */
    private array $camposSensibles = [
        'contrasena',
        'password',
        'remember_token',
    ];

    private function debeAuditar(Model $model): bool
    {
        return in_array(
            strtolower($model->getTable()),
            $this->tablasAuditadas,
            true
        );
    }

    private function activarModoApp(): void
    {
        DB::statement("SET @desde_app = 1");
    }

    private function desactivarModoApp(): void
    {
        DB::statement("SET @desde_app = NULL");
    }

    private function obtenerUsuario(): string
    {
        return Auth::user()?->nombre_usuario
            ?? Auth::user()?->usuario
            ?? 'sistema';
    }

    /**
     * Quita o enmascara los campos sensibles antes de serializar a JSON.
     */
    private function sanear(array $attributes): array
    {
        foreach ($this->camposSensibles as $campo) {
            if (array_key_exists($campo, $attributes)) {
                $attributes[$campo] = '***OCULTO***';
            }
        }

        return $attributes;
    }

    public function creating(Model $model): void
    {
        if ($this->debeAuditar($model)) {
            $this->activarModoApp();
        }
    }

    public function created(Model $model): void
    {
        if (!$this->debeAuditar($model)) {
            return;
        }

        LogCambio::create([
            'tabla'            => $model->getTable(),
            'accion'           => 'I',
            'id_registro'      => $model->getKey(),
            'campo'            => 'registro_completo',
            'valor_antes'      => null,
            'valor_despues'    => 'Registro creado',
            'fecha_cambio'     => now(),
            'usuario'          => $this->obtenerUsuario(),
            'id_usuario'       => Auth::id(),
            'ip_address'       => Request::ip(),
            'datos_anteriores' => null,
            'datos_nuevos'     => json_encode(
                $this->sanear($model->getAttributes()),
                JSON_UNESCAPED_UNICODE
            ),
        ]);

        $this->desactivarModoApp();
    }

    public function updating(Model $model): void
    {
        if ($this->debeAuditar($model)) {
            $this->activarModoApp();
        }
    }

    public function updated(Model $model): void
    {
        if (!$this->debeAuditar($model)) {
            return;
        }

        LogCambio::create([
            'tabla'            => $model->getTable(),
            'accion'           => 'U',
            'id_registro'      => $model->getKey(),
            'campo'            => 'registro_completo',
            'valor_antes'      => null,
            'valor_despues'    => 'Registro actualizado',
            'fecha_cambio'     => now(),
            'usuario'          => $this->obtenerUsuario(),
            'id_usuario'       => Auth::id(),
            'ip_address'       => Request::ip(),
            'datos_anteriores' => json_encode(
                $this->sanear($model->getOriginal()),
                JSON_UNESCAPED_UNICODE
            ),
            'datos_nuevos'     => json_encode(
                $this->sanear($model->getAttributes()),
                JSON_UNESCAPED_UNICODE
            ),
        ]);

        $this->desactivarModoApp();
    }

    public function deleting(Model $model): void
    {
        if ($this->debeAuditar($model)) {
            $this->activarModoApp();
        }
    }

    public function deleted(Model $model): void
    {
        if (!$this->debeAuditar($model)) {
            return;
        }

        LogCambio::create([
            'tabla'            => $model->getTable(),
            'accion'           => 'D',
            'id_registro'      => $model->getKey(),
            'campo'            => 'registro_completo',
            'valor_antes'      => 'Registro eliminado',
            'valor_despues'    => null,
            'fecha_cambio'     => now(),
            'usuario'          => $this->obtenerUsuario(),
            'id_usuario'       => Auth::id(),
            'ip_address'       => Request::ip(),
            'datos_anteriores' => json_encode(
                $this->sanear($model->getAttributes()),
                JSON_UNESCAPED_UNICODE
            ),
            'datos_nuevos'     => null,
        ]);

        $this->desactivarModoApp();
    }
}