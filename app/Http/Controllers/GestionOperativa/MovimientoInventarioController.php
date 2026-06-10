<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Material;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoInventarioController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoInventario::with(['material', 'proyecto'])
            ->orderByDesc('fecha')
            ->paginate(15);

        return view('operativa.movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $materiales = Material::orderBy('nombre')->get();
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.movimientos.create', compact('materiales', 'proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.id_material'   => 'required|integer|exists:material,id_material',
            'items.*.cantidad'      => 'required|numeric|min:0.0001',
            'items.*.tipo'          => 'required|in:entrada,salida',
            'items.*.id_proyecto'   => 'nullable|integer|exists:proyecto,id_proyecto',
            'items.*.descripcion'   => 'nullable|string|max:500',
        ]);

        $now     = now();
        $usuario = Auth::id();
        $count   = 0;

        foreach ($request->items as $item) {
            // Skip empty rows (safety check)
            if (empty($item['id_material']) || empty($item['cantidad'])) continue;

            MovimientoInventario::create([
                'id_material' => $item['id_material'],
                'cantidad'    => $item['cantidad'],
                'tipo'        => $item['tipo'],
                'id_proyecto' => $item['id_proyecto'] ?? null,
                'descripcion' => $item['descripcion'] ?? null,
                'fecha'       => $now,
                'id_usuario'  => $usuario,
            ]);
            $count++;
        }

        return redirect()->route('operativa.movimientos.index')
            ->with('success', "{$count} movimiento(s) registrado(s) correctamente.");
    }

    public function edit($id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);
        $materiales = Material::orderBy('nombre')->get();
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.movimientos.edit', compact('movimiento', 'materiales', 'proyectos'));
    }

    public function update(Request $request, $id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);

        $data = $request->validate([
            'id_material' => 'required|integer|exists:material,id_material',
            'cantidad'    => 'required|numeric|min:0.0001',
            'id_proyecto' => 'nullable|integer|exists:proyecto,id_proyecto',
            'descripcion' => 'nullable|string|max:500',
            'tipo'        => 'required|in:entrada,salida',
        ]);

        $movimiento->update($data);

        return redirect()->route('operativa.movimientos.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy($id)
    {
        MovimientoInventario::findOrFail($id)->delete();

        return redirect()->route('operativa.movimientos.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
