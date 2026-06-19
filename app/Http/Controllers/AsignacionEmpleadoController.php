<?php

namespace App\Http\Controllers;

use App\Models\AsignacionEmpleado;
use App\Models\Empleado;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class AsignacionEmpleadoController extends Controller
{
    public function index()
{
    return view('rrhh.asignaciones.index', [
        'asignaciones' => AsignacionEmpleado::with(['empleado','proyecto'])->paginate(10),
        'empleados' => Empleado::all(),
        'proyectos' => Proyecto::all(),
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'id_empleado' => 'required|exists:empleado,id_empleado',
            'id_proyecto' => 'required|exists:proyecto,id_proyecto',
            'rol_en_proyecto' => 'required|string|max:80',
            'fecha_inicio_asig' => 'required|date',
            'horas_semana' => 'nullable|numeric',
            'tarifa_hora' => 'nullable|numeric',
        ]);

        AsignacionEmpleado::create([
            'id_empleado' => $request->id_empleado,
            'id_proyecto' => $request->id_proyecto,
            'rol_en_proyecto' => $request->rol_en_proyecto,
            'fecha_inicio_asig' => $request->fecha_inicio_asig,
            'fecha_fin_asig' => $request->fecha_fin_asig,
            'horas_semana' => $request->horas_semana,
            'tarifa_hora' => $request->tarifa_hora,
            'observaciones' => $request->observaciones,
        ]);

        return back()->with('success', 'Empleado asignado correctamente');
    }
}