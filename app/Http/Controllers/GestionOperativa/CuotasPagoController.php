<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\CuotasPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuotasPagoController extends Controller
{
    public function index()
    {
        $cuotas = DB::table('cuotas_pago as cp')
            ->join('contrato as con', 'cp.id_contrato', '=', 'con.id_contrato')
            ->join('cliente as cl', 'con.id_cliente', '=', 'cl.id_cliente')
            ->select(
                'cp.id_cuota',
                'cp.id_contrato',
                'con.numero_contrato',
                'cl.nombre_razon',
                'cp.numero_cuota',
                'cp.monto_cuota',
                'cp.monto_pagado',
                'cp.fecha_vencimiento',
                'cp.fecha_pago_real',
                'cp.estado_cuota',
                DB::raw('DATEDIFF(CURDATE(), cp.fecha_vencimiento) as dias_retraso'),
                'cp.estado_cuota as evaluacion_dinamica'
            )
            ->orderBy('cp.fecha_vencimiento')
            ->paginate(15)
            ->through(fn ($row) => (array) $row);

        return view('operativa.cuotas.index', compact('cuotas'));
    }
 
    public function create()
    {
        $contratos = \App\Models\Contrato::with('cliente')->orderByDesc('id_contrato')->get();
        return view('operativa.cuotas.create', compact('contratos'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_contrato'      => 'required|integer',
            'numero_cuota'     => 'required|integer|min:1',
            'monto_cuota'      => 'required|numeric|min:0',
            'fecha_vencimiento'=> 'required|date',
            'estado_cuota'     => 'required|in:pendiente,pagada_tiempo,pagada_tarde,vencida',
            'observaciones'    => 'nullable|string',
        ]);
 
        \App\Models\CuotasPago::create($data);
        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota registrada.');
    }
 
    public function edit($id)
    {
        $cuota     = \App\Models\CuotasPago::findOrFail($id);
        $contratos = \App\Models\Contrato::with('cliente')->orderByDesc('id_contrato')->get();
        return view('operativa.cuotas.edit', compact('cuota', 'contratos'));
    }
 
    public function update(Request $request, $id)
    {
        $cuota = \App\Models\CuotasPago::findOrFail($id);
        $data  = $request->validate([
            'monto_cuota'      => 'required|numeric|min:0',
            'fecha_vencimiento'=> 'required|date',
            'fecha_pago_real'  => 'nullable|date',
            'monto_pagado'     => 'nullable|numeric|min:0',
            // estado_cuota lo cambia el trigger trg_cuota_estado_al_pagar
            'observaciones'    => 'nullable|string',
        ]);
 
        $cuota->update($data);
        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota actualizada. El estado fue ajustado por el trigger.');
    }
 
    public function destroy($id)
    {
        \App\Models\CuotasPago::findOrFail($id)->delete();
        return redirect()->route('operativa.cuotas.index')
                         ->with('success', 'Cuota eliminada.');
    }

    public function registrarPago(Request $request, $id)
    {
        $data = $request->validate([
            'fecha_pago_real' => 'required|date',
            'monto_pagado' => 'required|numeric|min:0',
        ]);

        DB::table('cuotas_pago')
            ->where('id_cuota', $id)
            ->update([
                'fecha_pago_real' => $data['fecha_pago_real'],
                'monto_pagado'    => $data['monto_pagado'],
                'estado_cuota'    => 'pagada_tiempo',
            ]);

        return redirect()->route('operativa.cuotas.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    public function reanudarObra($id)
    {
        // Reactiva la cuota suspendida o vencida poniéndola en pendiente.
        CuotasPago::where('id_cuota', $id)
            ->whereIn('estado_cuota', ['suspendida', 'vencida'])
            ->update(['estado_cuota' => 'pendiente', 'fecha_suspension' => null]);

        return redirect()->route('operativa.cuotas.index')
            ->with('success', "Cuota {$id} reactivada correctamente.");
    }
}
