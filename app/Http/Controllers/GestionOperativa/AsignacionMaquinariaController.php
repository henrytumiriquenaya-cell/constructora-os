<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maquinaria;
use App\Models\Proyecto;
use App\Models\AsignacionMaquinaria;

class AsignacionMaquinariaController extends Controller
{
    public function index()
    {
        $asignaciones = AsignacionMaquinaria::with(['maquinaria', 'proyecto'])
            ->orderByDesc('id_asig_maq')
            ->paginate(15);
        return view('operativa.maquinarias.asignaciones', compact('asignaciones'));
    }

    public function create()
    {
        $maquinarias = Maquinaria::where('estado', 'disponible')
                        ->orderBy('nombre')
                        ->get();
        
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('operativa.maquinarias.create', compact('maquinarias', 'proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_maquinaria'       => 'required|integer|exists:maquinaria,id_maquinaria',
            'id_proyecto'         => 'required|integer|exists:proyecto,id_proyecto',
            'id_empleado'         => 'nullable|integer|exists:empleado,id_empleado',
            'fecha_inicio'        => 'required|date',
            'fecha_fin'           => 'nullable|date|after_or_equal:fecha_inicio',
            'horas_asignadas'     => 'required|numeric|min:0',
            'costo_hora_aplicado' => 'nullable|numeric|min:0',
            'operador'            => 'nullable|string|max:100',
            'observaciones'       => 'nullable|string',
        ]);

        AsignacionMaquinaria::create($data);

        return redirect()->route('operativa.maquinarias.asignaciones')
                         ->with('success', 'Maquinaria asignada correctamente.');
    }

    public function edit($id)
    {
        $asignacion = AsignacionMaquinaria::findOrFail($id);
        
        $maquinarias = Maquinaria::orderBy('nombre')->get();
        $proyectos   = Proyecto::orderBy('nombre_proyecto')->get();

        return view('operativa.maquinarias.edit', compact('asignacion', 'maquinarias', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $asignacion = AsignacionMaquinaria::findOrFail($id);

        $data = $request->validate([
            'fecha_fin'           => 'nullable|date',
            'horas_usadas'        => 'nullable|numeric|min:0',
            'costo_hora_aplicado' => 'nullable|numeric|min:0',
            'operador'            => 'nullable|string|max:100',
            'observaciones'       => 'nullable|string',
        ]);

        $asignacion->update($data);

        return redirect()->route('operativa.maquinarias.asignaciones')
                         ->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $asignacion = AsignacionMaquinaria::findOrFail($id);
        $asignacion->delete();

        return redirect()->route('operativa.maquinarias.asignaciones')
                         ->with('success', 'Asignación eliminada correctamente.');
    }
}