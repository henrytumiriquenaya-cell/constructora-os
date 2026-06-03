<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\ParalizacionObra;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ParalizacionController extends Controller
{
    public function index()
    {
        $paralizaciones = ParalizacionObra::with('proyecto')->orderByDesc('id_paralizacion')->paginate(15);
        return view('operativa.paralizaciones.index', compact('paralizaciones'));
    }

    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.paralizaciones.create', compact('proyectos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_proyecto'    => 'required|integer',
            'motivo'         => 'required|string|max:200',
            'descripcion'    => 'nullable|string',
            'fecha_inicio_par' => 'required|date',
            'fecha_fin_par'  => 'nullable|date|after_or_equal:fecha_inicio_par',
            'registrado_por' => 'nullable|string|max:100',
            'estado'         => 'required|in:activa,levantada,en_revision',
        ]);

        ParalizacionObra::create($data);
        return redirect()->route('operativa.paralizaciones.index')
                         ->with('success', 'Paralización registrada correctamente.');
    }

    public function edit($id)
    {
        $paralizacion = ParalizacionObra::findOrFail($id);
        $proyectos    = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.paralizaciones.edit', compact('paralizacion', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $paralizacion = ParalizacionObra::findOrFail($id);
        $data = $request->validate([
            'id_proyecto'    => 'required|integer',
            'motivo'         => 'required|string|max:200',
            'descripcion'    => 'nullable|string',
            'fecha_inicio_par' => 'required|date',
            'fecha_fin_par'  => 'nullable|date|after_or_equal:fecha_inicio_par',
            'registrado_por' => 'nullable|string|max:100',
            'estado'         => 'required|in:activa,levantada,en_revision',
        ]);

        $paralizacion->update($data);
        return redirect()->route('operativa.paralizaciones.index')
                         ->with('success', 'Paralización actualizada correctamente.');
    }

    public function destroy($id)
    {
        ParalizacionObra::findOrFail($id)->delete();
        return redirect()->route('operativa.paralizaciones.index')
                         ->with('success', 'Paralización eliminada.');
    }
}