<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();

        if (!$user) {
            abort(401);
        }

        if (! $user->hasRole($role)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}
