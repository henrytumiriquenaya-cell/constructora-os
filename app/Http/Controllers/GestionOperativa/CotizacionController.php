<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;     // ← Esta línea es obligatoria
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Empleado;
use App\Models\Cotizacion;



class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = \App\Models\Cotizacion::with(['proyecto', 'empleado'])
                          ->orderByDesc('id_presupuesto')
                          ->paginate(15);
        return view('operativa.cotizaciones.index', compact('cotizaciones'));
    }
 
    public function create()
    {
        $proyectos = Proyecto::orderBy('nombre_proyecto')->get();
        $empleados = \App\Models\Empleado::orderBy('nombres')->get();
        return view('operativa.cotizaciones.create', compact('proyectos', 'empleados'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_proyecto'           => 'required|integer',
            'id_empleado'           => 'nullable|integer',
            'version'               => 'required|integer|min:1',
            'fecha_elaboracion'     => 'required|date',
            'monto_plan_materiales' => 'required|numeric|min:0',
            'monto_plan_mano_obra'  => 'required|numeric|min:0',
            'monto_plan_maquinaria' => 'required|numeric|min:0',
            'monto_plan_gastos_adm' => 'required|numeric|min:0',
            'estado'                => 'required|in:borrador,aprobado,vigente,cerrado',
        ]);
 
        // monto_total_planificado lo calcula el trigger trg_cotizacion_total_before_insert
        \App\Models\Cotizacion::create($data);
        return redirect()->route('operativa.cotizaciones.index')
                         ->with('success', 'Cotización registrada. El total fue calculado por el trigger.');
    }
 
    public function edit($id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();
        $empleados  = \App\Models\Empleado::orderBy('nombres')->get();
        return view('operativa.cotizaciones.edit', compact('cotizacion', 'proyectos', 'empleados'));
    }
 
    public function update(Request $request, $id)
    {
        $cotizacion = \App\Models\Cotizacion::findOrFail($id);
        $data = $request->validate([
            'monto_plan_materiales' => 'required|numeric|min:0',
            'monto_plan_mano_obra'  => 'required|numeric|min:0',
            'monto_plan_maquinaria' => 'required|numeric|min:0',
            'monto_plan_gastos_adm' => 'required|numeric|min:0',
            'estado'                => 'required|in:borrador,aprobado,vigente,cerrado',
        ]);
 
        // trg_cotizacion_total_before_update recalcula el total automáticamente
        $cotizacion->update($data);
        return redirect()->route('operativa.cotizaciones.index')
                         ->with('success', 'Cotización actualizada. Total recalculado por trigger.');
    }
 
    public function destroy($id)
    {
        \App\Models\Cotizacion::findOrFail($id)->delete();
        return redirect()->route('operativa.cotizaciones.index')
                         ->with('success', 'Cotización eliminada.');
    }
}
