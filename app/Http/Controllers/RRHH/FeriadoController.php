<?php

namespace App\Http\Controllers\RRHH;

use App\Http\Controllers\Controller;
use App\Models\Feriado;
use Illuminate\Http\Request;

class FeriadoController extends Controller
{
    public function index()
    {
        $feriados = Feriado::orderBy('fecha')->paginate(15);
        return view('rrhh.feriados.index', compact('feriados'));
    }

    public function create()
    {
        return view('rrhh.feriados.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha'         => 'required|date',
            'nombre'        => 'required|string|max:100',
            'tipo'          => 'required|in:nacional,departamental,municipal',
            'departamento'  => 'nullable|string|max:80',
            'recargo_pct'   => 'required|numeric|min:0|max:999.99',
        ]);

        Feriado::create($data);

        return redirect()->route('rrhh.feriados.index')
                         ->with('success', 'Feriado registrado correctamente.');
    }

    public function edit($id)
    {
        $feriado = Feriado::findOrFail($id);
        return view('rrhh.feriados.edit', compact('feriado'));
    }

    public function update(Request $request, $id)
    {
        $feriado = Feriado::findOrFail($id);

        $data = $request->validate([
            'fecha'         => 'required|date',
            'nombre'        => 'required|string|max:100',
            'tipo'          => 'required|in:nacional,departamental,municipal',
            'departamento'  => 'nullable|string|max:80',
            'recargo_pct'   => 'required|numeric|min:0|max:999.99',
        ]);

        $feriado->update($data);

        return redirect()->route('rrhh.feriados.index')
                         ->with('success', 'Feriado actualizado correctamente.');
    }

    public function destroy($id)
    {
        Feriado::findOrFail($id)->delete();

        return redirect()->route('rrhh.feriados.index')
                         ->with('success', 'Feriado eliminado.');
    }
}