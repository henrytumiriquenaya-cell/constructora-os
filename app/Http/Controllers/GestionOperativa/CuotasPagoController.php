<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\CuotasPago;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuotasPagoController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('cuotas_pago as cp')
            ->join('contrato as con', 'cp.id_contrato', '=', 'con.id_contrato')
            ->join('cliente as cl', 'con.id_cliente', '=', 'cl.id_cliente')
            ->leftJoin('proyecto as pr', 'pr.id_contrato', '=', 'con.id_contrato') // ← corregido: FK está en proyecto
            ->select(
                'cp.id_cuota',
                'cp.id_contrato',
                'con.numero_contrato',
                'cl.nombre_razon',
                'pr.id_proyecto',
                'pr.nombre_proyecto',
                'cp.numero_cuota',
                'cp.monto_cuota',
                'cp.monto_pagado',
                'cp.fecha_vencimiento',
                'cp.fecha_pago_real',
                'cp.estado_cuota',
                'cp.dias_alerta',
                'cp.fecha_suspension',
                'cp.cuota_origen',
                DB::raw('DATEDIFF(CURDATE(), cp.fecha_vencimiento) as dias_retraso')
            );

        if ($request->filled('id_proyecto')) {
            $query->where('pr.id_proyecto', $request->id_proyecto);
        }

        if ($request->filled('estado_cuota')) {
            $query->where('cp.estado_cuota', $request->estado_cuota);
        }

        $cuotas = $query->orderBy('cp.fecha_vencimiento')
            ->paginate(15)
            ->withQueryString()
            ->through(fn ($row) => (array) $row);

        $proyectos = DB::table('proyecto')
            ->orderBy('nombre_proyecto')
            ->get(['id_proyecto', 'nombre_proyecto']);

        return view('operativa.cuotas.index', compact('cuotas', 'proyectos'));
    }

    public function create()
    {
        $contratos = Contrato::with('cliente', 'proyecto')->orderByDesc('id_contrato')->get();

        // Cuotas disponibles para usar como origen en una reprogramación
        $cuotasDisponibles = DB::table('cuotas_pago as cp')
            ->join('contrato as con', 'cp.id_contrato', '=', 'con.id_contrato')
            ->select('cp.id_cuota', 'cp.numero_cuota', 'con.numero_contrato')
            ->orderByDesc('cp.id_cuota')
            ->limit(200)
            ->get();

        return view('operativa.cuotas.create', compact('contratos', 'cuotasDisponibles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_contrato'       => 'required|integer|exists:contrato,id_contrato',
            'numero_cuota'      => 'required|integer|min:1',
            'monto_cuota'       => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'estado_cuota'      => 'required|in:pendiente,pagada_tiempo,pagada_tarde,vencida,suspendida,reprogramada',
            'dias_alerta'       => 'nullable|integer|min:0',
            'cuota_origen'      => 'nullable|integer|exists:cuotas_pago,id_cuota'
        ]);

        CuotasPago::create($data);

        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota registrada correctamente.');
    }

    public function edit($id)
    {
        $cuota     = CuotasPago::findOrFail($id);
        $contratos = Contrato::with('cliente', 'proyecto')->orderByDesc('id_contrato')->get();

        return view('operativa.cuotas.edit', compact('cuota', 'contratos'));
    }

    public function update(Request $request, $id)
    {
        $cuota = CuotasPago::findOrFail($id);

        $data = $request->validate([
            'monto_cuota'       => 'required|numeric|min:0',
            'fecha_vencimiento' => 'required|date',
            'dias_alerta'       => 'nullable|integer|min:0'
        ]);

        $cuota->update($data);

        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota actualizada correctamente.');
    }

    public function destroy($id)
    {
        CuotasPago::findOrFail($id)->delete();

        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota eliminada.');
    }

    /**
     * Vista dedicada para registrar el pago de una cuota puntual.
     */
    public function registrarPagoForm($id)
    {
        $cuota = DB::table('cuotas_pago as cp')
            ->join('contrato as con', 'cp.id_contrato', '=', 'con.id_contrato')
            ->join('cliente as cl', 'con.id_cliente', '=', 'cl.id_cliente')
            ->leftJoin('proyecto as pr', 'pr.id_contrato', '=', 'con.id_contrato') // ← corregido
            ->select(
                'cp.*',
                'con.numero_contrato',
                'cl.nombre_razon',
                'pr.nombre_proyecto'
            )
            ->where('cp.id_cuota', $id)
            ->first();

        if (!$cuota) {
            abort(404);
        }

        return view('operativa.cuotas.registrar-pago', compact('cuota'));
    }

    public function registrarPago(Request $request, $id)
    {
        $data = $request->validate([
            'fecha_pago_real' => 'required|date',
            'monto_pagado'    => 'required|numeric|min:0',
        ]);

        // No forzamos estado_cuota: el trigger trg_cuota_estado_al_pagar
        // decide pagada_tiempo / pagada_tarde comparando fecha_pago_real
        // contra fecha_vencimiento.
        DB::table('cuotas_pago')
            ->where('id_cuota', $id)
            ->update([
                'fecha_pago_real' => $data['fecha_pago_real'],
                'monto_pagado'    => $data['monto_pagado'],
            ]);

        return redirect()->route('operativa.cuotas.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    public function reanudarObra($id)
    {
        CuotasPago::where('id_cuota', $id)
            ->whereIn('estado_cuota', ['suspendida', 'vencida'])
            ->update(['estado_cuota' => 'pendiente', 'fecha_suspension' => null]);

        return redirect()->route('operativa.cuotas.index')
            ->with('success', "Cuota {$id} reactivada correctamente.");
    }
}