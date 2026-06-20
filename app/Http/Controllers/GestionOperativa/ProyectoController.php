<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();

        if ($usuario->rol === 'cliente') {

            $proyectos = Proyecto::whereHas('contrato', function ($query) use ($usuario) {

                $query->where('id_cliente', $usuario->id_cliente);

            })
            ->with('contrato')
            ->orderByDesc('id_proyecto')
            ->paginate(15);

        } else {

            $proyectos = Proyecto::with('contrato')
                ->orderByDesc('id_proyecto')
                ->paginate(15);
        }


        return view('operativa.proyectos.index', compact('proyectos'));
    }

    public function create()
    {
        $contratos = Contrato::with('cliente')->orderByDesc('id_contrato')->get();
        return view('operativa.proyectos.create', compact('contratos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_contrato'         => 'required|integer',
            'nombre_proyecto'     => 'required|string|max:200',
            'codigo_proyecto'     => 'nullable|string|max:30',
            'ubicacion'           => 'nullable|string|max:300',
            'fecha_inicio_real'   => 'nullable|date',
            'fecha_fin_programada'=> 'nullable|date',
            'tipo_obra'           => 'nullable|string|max:80',
            'superficie_m2'       => 'nullable|numeric|min:0',
            'estado'              => 'required|in:planificacion,en_ejecucion,paralizado,concluido,cancelado, abandonado',
            'porcentaje_avance'   => 'nullable|integer|min:0|max:100',
        ]);

        Proyecto::create($data);
        return redirect()->route('operativa.proyectos.index')
                         ->with('success', 'Proyecto registrado correctamente.');
    }

    public function show($id)
    {
        $proyecto = Proyecto::with('contrato.cliente')->findOrFail($id);
        return view('operativa.proyectos.show', compact('proyecto'));
    }

    public function edit($id)
    {
        $proyecto  = Proyecto::findOrFail($id);
        $contratos = Contrato::with('cliente')->orderByDesc('id_contrato')->get();
        return view('operativa.proyectos.edit', compact('proyecto', 'contratos'));
    }

    public function update(Request $request, $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $data = $request->validate([
            'id_contrato'         => 'required|integer',
            'nombre_proyecto'     => 'required|string|max:200',
            'codigo_proyecto'     => 'nullable|string|max:30',
            'ubicacion'           => 'nullable|string|max:300',
            'fecha_inicio_real'   => 'nullable|date',
            'fecha_fin_programada'=> 'nullable|date',
            'fecha_fin_real'      => 'nullable|date',
            'tipo_obra'           => 'nullable|string|max:80',
            'superficie_m2'       => 'nullable|numeric|min:0',
            'estado'              => 'required|in:planificacion,en_ejecucion,paralizado,concluido,cancelado,abandonado',
            'porcentaje_avance'   => 'nullable|integer|min:0|max:100',
        ]);

        $proyecto->update($data);
        return redirect()->route('operativa.proyectos.index')
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy($id)
    {
        Proyecto::findOrFail($id)->delete();
        return redirect()->route('operativa.proyectos.index')
                         ->with('success', 'Proyecto eliminado.');
    }
}