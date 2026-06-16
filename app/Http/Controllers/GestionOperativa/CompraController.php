<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Material;
use App\Models\Proyecto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::with(['proyecto', 'proveedor'])
                         ->orderByDesc('id_compra')
                         ->paginate(15);
        return view('operativa.compras.index', compact('compras'));
    }
 
    public function create()
    {
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();
        $proveedores = Proveedor::where('activo', 1)->orderBy('razon_social')->get();
        return view('operativa.compras.create', compact('proyectos', 'proveedores'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_proyecto'            => 'nullable|integer',
            'id_proveedor'           => 'required|integer',
            'numero_orden'           => 'required|string|max:30|unique:compra,numero_orden',
            'fecha_emision'          => 'required|date',
            'fecha_entrega_prevista' => 'nullable|date',
            'estado'                 => 'required|in:borrador,emitida,recibida_parcial,recibida_total,anulada',
            'observaciones'          => 'nullable|string',
        ]);

        // El monto_total lo calcula la base de datos con triggers de detalle_compra.
        $data['monto_total'] = 0;

        Compra::create($data);
        return redirect()->route('operativa.compras.index')
                         ->with('success', 'Orden de compra registrada.');
    }
 
    public function show($id)
    {
        $compra = Compra::with(['proyecto', 'proveedor', 'detalles.material'])->findOrFail($id);
        return view('operativa.compras.show', compact('compra'));
    }

    public function detalle($id)
    {
        $compra = Compra::with(['proyecto', 'proveedor', 'detalles.material'])->findOrFail($id);
        $materiales = Material::orderBy('nombre')->get();

        return view('operativa.compras.detalle', compact('compra', 'materiales'));
    }

    public function storeDetalle(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);

        $data = $request->validate([
            'id_material' => 'required|integer',
            'cantidad' => 'required|numeric|min:0.0001',
            'precio_unitario' => 'required|numeric|min:0',
            'cantidad_recibida' => 'nullable|numeric|min:0',
        ]);

        $data['id_compra'] = $compra->id_compra;
        $data['cantidad_recibida'] = $data['cantidad_recibida'] ?? 0;
        // subtotal se calcula por trigger en SQL Server.
        $data['subtotal'] = 0;

        DetalleCompra::create($data);

        return redirect()->route('operativa.compras.detalle', $compra->id_compra)
            ->with('success', 'Ítem agregado. El subtotal y total se calcularon automáticamente en la base de datos.');
    }

    public function updateDetalleLote(Request $request, $id)
    {
        Compra::findOrFail($id);
        $detalles = $request->input('detalles', []);

        foreach ($detalles as $detalleId => $row) {
            $detalle = DetalleCompra::where('id_compra', $id)->find($detalleId);
            if (! $detalle) {
                continue;
            }

            $detalle->update([
                'cantidad' => $row['cantidad'] ?? $detalle->cantidad,
                'precio_unitario' => $row['precio_unitario'] ?? $detalle->precio_unitario,
                'cantidad_recibida' => $row['cantidad_recibida'] ?? $detalle->cantidad_recibida,
            ]);
        }

        return redirect()->route('operativa.compras.detalle', $id)
            ->with('success', 'Detalle actualizado. Los subtotales y estado de compra fueron recalculados por triggers.');
    }

    public function destroyDetalle($id, $detalleId)
{
    $detalle = DetalleCompra::where('id_compra', $id)->findOrFail($detalleId);

    try {
        $detalle->delete();
    } catch (\Illuminate\Database\QueryException $e) {
        // Código 23000 = violación de constraint
        if ($e->getCode() === '23000') {
            return redirect()->route('operativa.compras.detalle', $id)
                ->with('error', 'No se puede eliminar: el inventario quedaría en negativo. Ajusta el stock primero.');
        }
        throw $e; 
    }

    return redirect()->route('operativa.compras.detalle', $id)
        ->with('success', 'Ítem eliminado del detalle de compra.');
}

    public function recibirTodo($id)
    {
        $compra = Compra::with('detalles')->findOrFail($id);

        foreach ($compra->detalles as $detalle) {
            $detalle->update([
                'cantidad_recibida' => $detalle->cantidad,
            ]);
        }

        return redirect()->route('operativa.compras.detalle', $id)
            ->with('success', 'Se marcó "Recibir Todo". El inventario/estado se ajustó por la lógica automática de la base de datos.');
    }
 
    public function edit($id)
    {
        $compra      = Compra::findOrFail($id);
        $proyectos   = Proyecto::orderBy('nombre_proyecto')->get();
        $proveedores = Proveedor::orderBy('razon_social')->get();
        return view('operativa.compras.edit', compact('compra', 'proyectos', 'proveedores'));
    }
 
    public function update(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);
        $data = $request->validate([
            'fecha_entrega_prevista' => 'nullable|date',
            'fecha_entrega_real'     => 'nullable|date',
            'estado'                 => 'required|in:borrador,emitida,recibida_parcial,recibida_total,anulada',
            'observaciones'          => 'nullable|string',
        ]);

        $compra->update($data);
        return redirect()->route('operativa.compras.index')
                         ->with('success', 'Compra actualizada.');
    }
 
    public function destroy($id)
    {
        Compra::findOrFail($id)->delete();
        return redirect()->route('operativa.compras.index')
                         ->with('success', 'Compra eliminada.');
    }
}