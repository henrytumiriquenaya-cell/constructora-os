<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertasController extends Controller
{
    // Tipos de notificaciones y sus roles autorizados
    private const TIPOS_NOTIFICACION = [
        'stock_bajo' => ['admin', 'logist', 'jefe obra'],
        'compra_realizada' => ['admin', 'contab', 'logist'],
        'pago_vencido' => ['admin', 'contab', 'cliente'],
        'proyecto_atrasado' => ['admin', 'gerente', 'jefe obra', 'cliente'],
        'asignacion_cambio' => ['admin', 'jefe obra', 'rrhh'],
        'permiso_solicitado' => ['admin', 'rrhh'],
        'mantenimiento_equipo' => ['admin', 'logist'],
        'obra_completada' => ['admin', 'gerente', 'cliente'],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = strtolower($user->rol ?? 'admin'); 

        // Obtener todas las notificaciones usando id_destinatario encapsulado correctamente
        $query = Notificacion::where(function($q) use ($user) {
                $q->where('id_destinatario', $user->id_usuario)
                  ->orWhereNull('id_destinatario'); // Notificaciones globales
            })
            ->orderByDesc('id_notificacion');

        // Filtrar por categoría (columna física en tu base de datos)
        if ($request->has('tipo') && $request->tipo) {
            $query->where('categoria', $request->tipo);
        }

        // Filtrar leídas/no leídas (la columna leida almacena 0 o 1)
        if ($request->has('estado')) {
            if ($request->estado === 'no_leidas') {
                $query->where('leida', 0);
            } elseif ($request->estado === 'leidas') {
                $query->where('leida', 1);
            }
        }

        $notificaciones = $query->paginate(20);

        // Obtener tipos de notificaciones permitidas para este rol
        $tiposPermitidos = collect(self::TIPOS_NOTIFICACION)
            ->filter(fn($roles) => in_array($userRole, $roles))
            ->keys()
            ->toArray();

        // Contar notificaciones no leídas usando la columna real id_destinatario
        $noLeidasCount = Notificacion::where('id_destinatario', $user->id_usuario)
            ->where('leida', 0)
            ->count();

        return view('reportes.alertas.index', compact(
            'notificaciones',
            'tiposPermitidos',
            'noLeidasCount'
        ));
    }

    public function marcarComoLeida($id)
    {
        // Se asume que el modelo Notificacion tiene definido 'id_notificacion' como PK
        $notificacion = Notificacion::findOrFail($id);

        // Verificar pertenencia con id_destinatario
        if ($notificacion->id_destinatario !== Auth::user()->id_usuario && $notificacion->id_destinatario !== null) {
            abort(403);
        }

        // Cambia el estado directo a 1 (Leída)
        $notificacion->leida = 1;
        $notificacion->save();

        return back()->with('success', 'Notificación marcada como leída.');
    }

    public function marcarTodasLeidas()
    {
        Notificacion::where('id_destinatario', Auth::user()->id_usuario)
            ->where('leida', 0)
            ->update([
                'leida' => 1
                // Se remueve la actualización de fecha_lectura porque no existe en tu tabla física
            ]);

        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function eliminar($id)
    {
        $notificacion = Notificacion::findOrFail($id);

        if ($notificacion->id_destinatario !== Auth::user()->id_usuario && $notificacion->id_destinatario !== null) {
            abort(403);
        }

        $notificacion->delete();

        return back()->with('success', 'Notificación eliminada.');
    }
}
