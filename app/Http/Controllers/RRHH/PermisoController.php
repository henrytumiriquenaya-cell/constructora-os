<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use Illuminate\Http\Request;
use App\Models\Proyecto;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::orderByDesc('id_permiso')->paginate(15);
        return view('rrhh.permisos.index', compact('permisos'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();

        return view('rrhh.permisos.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_permiso' => 'required|string|max:100',
            'entidad_emisora' => 'required|string|max:150',
            'fecha_solicitud' => 'required|date',
            'costo_tramite' => 'nullable|numeric|min:0',
            'estado' => 'required|string',
        ]);

        $permiso = new Permiso();
        $permiso->tipo_permiso = $request->tipo_permiso;
        $permiso->entidad_emisora = $request->entidad_emisora;
        $permiso->fecha_solicitud = $request->fecha_solicitud;
        $permiso->fecha_emision = $request->fecha_emision;
        $permiso->fecha_vencimiento = $request->fecha_vencimiento;
        $permiso->costo_tramite = $request->costo_tramite ?? 0;
        $permiso->estado = $request->estado;
        $permiso->id_proyecto = $request->id_proyecto;
        $permiso->save();

        return redirect()->route('rrhh.permisos.index')->with('success', 'Permiso registrado correctamente.');
    }

    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        return view('rrhh.permisos.edit', compact('permiso'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_permiso' => 'required|string|max:100',
            'entidad_emisora' => 'required|string|max:150',
            'fecha_solicitud' => 'required|date',
            'costo_tramite' => 'nullable|numeric|min:0',
            'estado' => 'required|string',
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->tipo_permiso = $request->tipo_permiso;
        $permiso->entidad_emisora = $request->entidad_emisora;
        $permiso->fecha_solicitud = $request->fecha_solicitud;
        $permiso->fecha_emision = $request->fecha_emision;
        $permiso->fecha_vencimiento = $request->fecha_vencimiento;
        $permiso->costo_tramite = $request->costo_tramite ?? 0;
        $permiso->estado = $request->estado;
        $permiso->save();

        return redirect()->route('rrhh.permisos.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();
        return redirect()->route('rrhh.permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
