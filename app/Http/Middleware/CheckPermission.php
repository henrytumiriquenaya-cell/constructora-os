<?php

namespace App\Http\Middleware;

use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function __construct(
        protected PermissionService $permissions
    ) {}

    public function handle(Request $request, Closure $next, ?string $table = null, ?string $operation = null): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $table = $table ?? $this->permissions->tableForRoute($request->route()?->getName());

        if ($table === null) {
            return $next($request);
        }

        $operation = $operation ?? $this->permissions->operationFromRequest($request->method());

        if (! $this->permissions->can($user, $table, $operation)) {

            return redirect()
                ->back()
                ->with('error', 'No tiene permisos para realizar esta acción.');

        }

        $request->attributes->set('permission_table', $table);

        return $next($request);
    }
}
