<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Login;

class LogUserLogin
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function handle(Login $event): void
    {
        $user = $event->user;
        
        $identificador = method_exists($user, 'nombreParaMostrar')
            ? $user->nombreParaMostrar()
            : ($user->usuario ?? $user->email ?? 'desconocido');

        $this->auditService->logSession(
            'LOGIN',
            $user->id_usuario ?? $user->id,
            $identificador
        );
    }
}
