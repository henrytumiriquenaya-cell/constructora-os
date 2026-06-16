<?php
// app/Http/Controllers/GestionOperativa/CiudadController.php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use Illuminate\Http\Request;

class CiudadController extends Controller
{
    public function index()
    {
        $ciudades = Ciudad::orderBy('nombre_ciudad')->paginate(15);

        return view('operativa.ciudades.index', compact('ciudades'));
    }

    public function create()
    {
        return view('operativa.ciudades.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_ciudad'       => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'pais'         => 'required|string|max:80',
        ]);

        Ciudad::create($data);
        return redirect()->route('operativa.ciudades.index')
                         ->with('success', 'Ciudad registrada correctamente.');
    }

    public function edit($id)
    {
        $ciudad = Ciudad::findOrFail($id);
        return view('operativa.ciudades.edit', compact('ciudad'));
    }

    public function update(Request $request, $id)
    {
        $ciudad = Ciudad::findOrFail($id);
        $data = $request->validate([
            'nombre_ciudad'       => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'pais'         => 'required|string|max:80',
        ]);

        $ciudad->update($data);
        return redirect()->route('operativa.ciudades.index')
                         ->with('success', 'Ciudad actualizada.');
    }

    public function destroy($id)
{
    try {
        Ciudad::findOrFail($id)->delete();
        return redirect()->route('operativa.ciudades.index')
                         ->with('success', 'Ciudad eliminada.');
    } catch (\Illuminate\Database\QueryException $e) {
        return redirect()->route('operativa.ciudades.index')
                         ->with('error', 'No se puede eliminar esta ciudad porque tiene clientes asociados.');
    }
}
}