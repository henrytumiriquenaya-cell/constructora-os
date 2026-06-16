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
            'codigo_inventario'           => 'required|string|max:20',
            'nombre'           => 'required|string|max:100',
            'tipo'             => 'required|string|max:80',
            'marca'            => 'nullable|string|max:80',
            'modelo'           => 'nullable|string|max:80',
            'anio_fabricacion' => 'nullable|integer|min:1900|max:2100',
            'numero_serie'     => 'nullable|string|max:60',
            'capacidad'        => 'nullable|numeric',
            'unidad_capacidad' => 'nullable|string|max:20',
            'estado_actual'    => 'required|in:disponible,en_uso,en_mantenimiento,fuera_servicio',
            'costo_hora'       => 'nullable|numeric|min:0',
            'observaciones'    => 'nullable|string',
        ]);
 
        Maquinaria::create($data);
        return redirect()->route('operativa.maquinarias.index')
                         ->with('success', 'Maquinaria registrada.');
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
            'nombre'        => 'required|string|max:100',
            'marca'         => 'nullable|string|max:80',
            'modelo'        => 'nullable|string|max:80',
            'estado_actual' => 'required|in:disponible,en_uso,en_mantenimiento,fuera_servicio',
            'costo_hora'    => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);
 
        $maquinaria->update($data);
        return redirect()->route('operativa.maquinarias.index')
                         ->with('success', 'Maquinaria actualizada.');
    }
 
    public function destroy($id)
    {
        Maquinaria::findOrFail($id)->delete();
        return redirect()->route('operativa.maquinarias.index')
                         ->with('success', 'Maquinaria eliminada.');
    }
}
