<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materiales = Material::with('proyecto')
            ->orderBy('nombre')
            ->paginate(15);

        return view('operativa.materiales.index', compact('materiales'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.materiales.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'cantidad'    => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:500',
            'id_proyecto' => 'nullable|integer|exists:proyecto,id_proyecto',
        ]);

        Material::create($data);

        return redirect()->route('operativa.materiales.index')
            ->with('success', 'Material registrado correctamente.');
    }

    public function edit($id)
    {
        $material  = Material::findOrFail($id);
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.materiales.edit', compact('material', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $request->validate([
            'nombre'      => 'required|string|max:150',
            'cantidad'    => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:500',
            'id_proyecto' => 'nullable|integer|exists:proyecto,id_proyecto',
        ]);

        $material->update($data);

        return redirect()->route('operativa.materiales.index')
            ->with('success', 'Material actualizado correctamente.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('operativa.materiales.index')
            ->with('success', 'Material eliminado correctamente.');
    }
}
