<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\RegistroHoraDiario;
use App\Models\Proyecto;
use Illuminate\Http\Request;


class RegistroHoraController extends Controller
{
    public function index(Request $request)
    {
        $query = RegistroHoraDiario::with([
            'proyecto',
            'empleado'
        ]);

        if ($request->filled('cargo')) {
            $query->whereHas('empleado', function ($q) use ($request) {
                $q->where('cargo', $request->cargo);
            });
        }

        $registros = $query
            ->orderBy('fecha_trabajo', 'desc')
            ->paginate(15)
            ->withQueryString();

        $cargos = \App\Models\Empleado::query()
            ->select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        return view('operativa.asistencia.index', compact(
            'registros',
            'cargos'
        ));
    }

    public function create()
    {
        $proyectos = Proyecto::where('estado', 'Activo')->get();
        return view('operativa.asistencia.create', compact('proyectos'));
    }
}