<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Maquinaria;
use Illuminate\Http\Request;

class MaquinariaController extends Controller
{
    public function index()
    {
        $maquinarias = Maquinaria::orderBy('nombre')->paginate(15);
        return view('operativa.maquinarias.index', compact('maquinarias'));
    }

    public function create()
    {
        return view('operativa.maquinarias.maquinaria_create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo_inventario' => 'required|string|max:30|unique:maquinaria,codigo_inventario',
            'nombre'            => 'required|string|max:100',
            'tipo'              => 'required|string|max:80',
            'marca'             => 'required|string|max:60',
            'modelo'            => 'required|string|max:60',
            'anio_fabricacion'  => 'nullable|digits:4|integer|min:1900|max:2100',
            'numero_serie'      => 'nullable|string|max:60',
            'capacidad'         => 'nullable|numeric',
            'unidad_capacidad'  => 'nullable|string|max:20',
            'tipo_propiedad'    => 'required|in:propio,arrendado',
            'estado_actual'     => 'required|in:disponible,en_uso,en_mantenimiento,fuera_servicio',
            'costo_hora'        => 'required|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'observaciones'     => 'nullable|string',
        ]);

        Maquinaria::create($data);

        return redirect()->route('operativa.maquinarias.catalogo')
                         ->with('success', 'Maquinaria registrada correctamente.');
    }

    public function edit($id)
    {
        $maquinaria = Maquinaria::findOrFail($id);
        return view('operativa.maquinarias.maquinaria_edit', compact('maquinaria'));
    }

    public function update(Request $request, $id)
    {
        $maquinaria = Maquinaria::findOrFail($id);

        $data = $request->validate([
            'codigo_inventario' => 'required|string|max:30|unique:maquinaria,codigo_inventario,' . $id . ',id_maquinaria',
            'nombre'            => 'required|string|max:100',
            'tipo'              => 'required|string|max:80',
            'marca'             => 'required|string|max:60',
            'modelo'            => 'required|string|max:60',
            'anio_fabricacion'  => 'nullable|digits:4|integer|min:1900|max:2100',
            'numero_serie'      => 'nullable|string|max:60',
            'capacidad'         => 'nullable|numeric',
            'unidad_capacidad'  => 'nullable|string|max:20',
            'tipo_propiedad'    => 'required|in:propio,arrendado',
            'estado_actual'     => 'required|in:disponible,en_uso,en_mantenimiento,fuera_servicio',
            'costo_hora'        => 'required|numeric|min:0',
            'fecha_adquisicion' => 'nullable|date',
            'observaciones'     => 'nullable|string',
        ]);

        $maquinaria->update($data);

        return redirect()->route('operativa.maquinarias.catalogo')
                         ->with('success', 'Maquinaria actualizada correctamente.');
    }

    public function destroy($id)
    {
        Maquinaria::findOrFail($id)->delete();

        return redirect()->route('operativa.maquinarias.catalogo')
                         ->with('success', 'Maquinaria eliminada.');
    }
}