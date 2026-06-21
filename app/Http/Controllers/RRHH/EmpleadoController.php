<?php

namespace App\Http\Controllers\RRHH;

use App\Models\AsignacionEmpleado;

use App\Models\Proyecto;
use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::orderByDesc('id_empleado')->paginate(15);
        return view('rrhh.empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('rrhh.empleados.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ci'             => 'required|string|max:15|unique:empleado,ci',
            'nombres'        => 'required|string|max:80',
            'apellidos'      => 'required|string|max:80',
            'cargo'          => 'required|string|max:80',
            'especialidad'   => 'nullable|string|max:100',
            'modalidad_pago' => 'required|in:mensual,por_hora,jornal',
            'salario_base'   => 'nullable|numeric|min:0',
            'tarifa_hora'    => 'nullable|numeric|min:0',
            'tarifa_jornal'  => 'nullable|numeric|min:0',
            'tipo_contrato'  => 'required|in:indefinido,fijo,eventual,por_obra',
            'fecha_ingreso'  => 'required|date',
            'telefono'       => 'required|string|max:15',
            'correo'         => 'nullable|email|max:100',
            'activo'         => 'boolean',
        ]);

        $data['activo'] = $request->boolean('activo', true);
        Empleado::create($data);

        return redirect()->route('rrhh.empleados.index')
                         ->with('success', 'Empleado registrado correctamente.');
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('rrhh.empleados.edit', compact('empleado'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $data = $request->validate([
            'ci'             => 'required|string|max:15|unique:empleado,ci,'.$id.',id_empleado',
            'nombres'        => 'required|string|max:80',
            'apellidos'      => 'required|string|max:80',
            'cargo'          => 'required|string|max:80',
            'especialidad'   => 'nullable|string|max:100',
            'modalidad_pago' => 'required|in:mensual,por_hora,jornal',
            'salario_base'   => 'nullable|numeric|min:0',
            'tarifa_hora'    => 'nullable|numeric|min:0',
            'tarifa_jornal'  => 'nullable|numeric|min:0',
            'tipo_contrato'  => 'required|in:indefinido,fijo,eventual,por_obra',
            'fecha_ingreso'  => 'required|date',
            'fecha_baja'     => 'nullable|date',
            'telefono'       => 'required|string|max:15',
            'correo'         => 'nullable|email|max:100',
            'activo'         => 'boolean',
        ]);

        $data['activo'] = $request->boolean('activo');
        $empleado->update($data);

        return redirect()->route('rrhh.empleados.index')
                         ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update(['activo' => 0, 'fecha_baja' => now()->toDateString()]);

        return redirect()->route('rrhh.empleados.index')
                         ->with('success', 'Empleado dado de baja correctamente.');
    }

    public function asignaciones()
    {
        return view('rrhh.asignaciones.index', [
        'asignaciones' => AsignacionEmpleado::with(['empleado','proyecto'])->paginate(10),
        'empleados' => Empleado::all(),
        'proyectos' => Proyecto::all(),
    ]);
}

    public function pagos(Request $request)
    {
        $query = \App\Models\PagoEmpleado::with('empleado');

        // Filtro por cargo
        if ($request->filled('cargo')) {
            $query->whereHas('empleado', function ($q) use ($request) {
                $q->where('cargo', $request->cargo);
            });
        }

        // Filtro por periodo
        if ($request->filled('periodo_mes')) {
            $query->where('periodo_mes', $request->periodo_mes);
        }

        $pagos = $query
            ->orderByDesc('id_pago_emp')
            ->paginate(15)
            ->withQueryString();

        $cargos = Empleado::select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        $periodos = \App\Models\PagoEmpleado::select('periodo_mes')
            ->distinct()
            ->orderByDesc('periodo_mes')
            ->pluck('periodo_mes');

        return view('rrhh.pagos.index', compact(
            'pagos',
            'cargos',
            'periodos'
        ));
    }

    public function permisos()
    {
        $permisos = \App\Models\Permiso::with('proyecto')->paginate(15);
        return view('rrhh.permisos.index', compact('permisos'));
    }

    public function feriados()
    {
        $feriados = \App\Models\Feriado::paginate(15);
        return view('rrhh.feriados.index', compact('feriados'));
    }
}