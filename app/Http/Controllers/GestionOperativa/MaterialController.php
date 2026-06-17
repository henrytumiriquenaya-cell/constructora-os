<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materiales = Material::orderBy('nombre')->paginate(15);
        return view('operativa.materiales.index', compact('materiales'));
    }

    public function create()
    {
        return view('operativa.materiales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'               => 'required|string|max:255',
            'codigo_interno'       => 'required|string|max:50|unique:material,codigo_interno',
            'categoria'            => 'required|string|max:100',
            'unidad_medida'        => 'required|string|max:20',
            'precio_unitario_ref'  => 'required|numeric|min:0',
            'stock_minimo'         => 'required|numeric|min:0',
            'descripcion'          => 'nullable|string',
        ]);

        Material::create($data);

        return redirect()->route('operativa.materiales.index')
                         ->with('success', 'Material creado exitosamente.');
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('operativa.materiales.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $data = $request->validate([
            'nombre'               => 'required|string|max:255',
            'codigo_interno'       => 'required|string|max:50|unique:material,codigo_interno,' . $id . ',id_material',
            'categoria'            => 'required|string|max:100',
            'unidad_medida'        => 'required|string|max:20',
            'precio_unitario_ref'  => 'required|numeric|min:0',
            'stock_minimo'         => 'required|numeric|min:0',
            'descripcion'          => 'nullable|string',
        ]);

        $material->update($data);

        return redirect()->route('operativa.materiales.index')
                         ->with('success', 'Material actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Material::findOrFail($id)->delete();

        return redirect()->route('operativa.materiales.index')
                         ->with('success', 'Material eliminado.');
    }
}