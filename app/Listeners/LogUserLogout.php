<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Logout;

class LogUserLogout
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

        public function handle(Logout $event): void
    {
        $user = $event->user;

        // Si el evento de logout no tiene usuario en sesión por caducidad, prevenimos un crash
        if (!$user) {
            return;
        }
        
        $identificador = method_exists($user, 'nombreParaMostrar')
            ? $user->nombreParaMostrar()
            : ($user->usuario ?? $user->email ?? 'desconocido');

        $this->auditService->logSession(
            'LOGOUT',
            $user->id_usuario ?? $user->id,
            $identificador
        );
    }

}
