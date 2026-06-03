<?php

namespace App\Http\Controllers;

use App\Models\LogCambio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = LogCambio::query();

        // Filtro por usuario usando el id enviado por el select
        if ($request->has('id_usuario') && $request->id_usuario) {
            $userObj = \App\Models\Usuario::find($request->id_usuario);
            if ($userObj) {
                $query->where(function($q) use ($userObj) {
                    $q->where('id_usuario', $userObj->id_usuario)
                      ->orWhere('usuario', $userObj->usuario)
                      ->orWhere('usuario', $userObj->nombre_usuario);
                });
            }
        }

        // Filtro por tabla afectada
        if ($request->has('tabla') && $request->tabla) {
            $query->where('tabla', $request->tabla);
        }

        // Filtro por tipo de operación (campo contiene I, U, D, LOGIN, LOGOUT)
        if ($request->has('tipo') && $request->tipo) {
            $query->where('campo', $request->tipo);
        }

        // Filtro por fecha desde
        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('fecha_cambio', '>=', $request->fecha_desde);
        }

        // Filtro por fecha hasta
        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('fecha_cambio', '<=', $request->fecha_hasta);
        }

        // Búsqueda libre
        if ($request->has('search') && $request->search) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('usuario', 'like', $search)
                  ->orWhere('tabla', 'like', $search)
                  ->orWhere('campo', 'like', $search)
                  ->orWhere('valor_antes', 'like', $search)
                  ->orWhere('valor_despues', 'like', $search)
                  ->orWhere('datos_anteriores', 'like', $search)
                  ->orWhere('datos_nuevos', 'like', $search);
            });
        }

        // Contar totales por tipo
        $totalRegistros = LogCambio::count();

                //  SOLUCIÓN: Obtener lista de nombres de usuarios únicos desde el log de auditoría
        $usuariosUnicos = LogCambio::whereNotNull('usuario')
            ->distinct()
            ->pluck('usuario');

        // Buscamos los objetos de estos usuarios en la base de datos para mostrar sus nombres completos si existen
        $usuarios = \App\Models\Usuario::whereIn('usuario', $usuariosUnicos)
            ->orWhereIn('nombre_usuario', $usuariosUnicos)
            ->orderBy('nombre_usuario')
            ->get(['id_usuario', 'nombre_completo', 'nombre_usuario', 'usuario']);


        // Obtener lista de tablas afectadas
        $tablas = LogCambio::distinct()
            ->pluck('tabla')
            ->sort()
            ->values()
            ->toArray();

        // Paginar resultados
        $logs = $query->orderByDesc('fecha_cambio')->paginate(20);

        return view('reportes.log.index', compact(
            'logs',
            'usuarios',
            'tablas',
            'totalRegistros'
        ));
    }
}
