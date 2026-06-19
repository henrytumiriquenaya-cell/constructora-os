<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyRoleScope
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return $next($request);
        }

        // Guardamos el usuario globalmente para usarlo en modelos si quieres
        app()->instance('current_user', $user);

        return $next($request);
    }
}