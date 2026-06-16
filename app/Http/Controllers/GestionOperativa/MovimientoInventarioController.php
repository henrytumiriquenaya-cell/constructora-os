<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Material;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimientoInventarioController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — Lista movimientos con filtros y KPIs
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $idProyecto     = $request->query('id_proyecto');
        $tipoMovimiento = $request->query('tipo');

        $query = MovimientoInventario::with(['material', 'proyecto']);

        if (!empty($idProyecto)) {
            $query->where('id_proyecto', $idProyecto);
        }
        if (!empty($tipoMovimiento)) {
            $query->where('tipo', $tipoMovimiento);
        }

        // KPIs sobre el filtro actual
        $totalMovimientos = (clone $query)->count();
        $totalEntradas    = (clone $query)->where('tipo', 'ENTRADA')->count();
        $totalSalidas     = (clone $query)->where('tipo', 'SALIDA')->count();

        $movimientos   = $query->orderByDesc('fecha')->paginate(15);
        $proyectosList = Proyecto::orderBy('nombre_proyecto')->get();

        return view('operativa.movimientos.index', compact(
            'movimientos', 'proyectosList',
            'idProyecto', 'tipoMovimiento',
            'totalMovimientos', 'totalEntradas', 'totalSalidas'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CREATE / STORE — Nuevo movimiento manual (entrada o salida directa)
    // Solo para correcciones o ajustes que NO vienen de compras ni de uso_material
    // ─────────────────────────────────────────────────────────────────────────
    public function create()
    {
        $materiales = Material::orderBy('nombre')->get();
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();
        return view('operativa.movimientos.create', compact('materiales', 'proyectos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'                => 'required|array|min:1',
            'items.*.id_material'  => 'required|integer|exists:material,id_material',
            'items.*.cantidad'     => 'required|numeric|min:0.0001',
            'items.*.tipo'         => 'required|in:entrada,salida,ENTRADA,SALIDA',
            'items.*.id_proyecto'  => 'nullable|integer|exists:proyecto,id_proyecto',
            'items.*.descripcion'  => 'nullable|string|max:500',
        ]);

        $now     = now();
        $usuario = Auth::id();
        $count   = 0;

        DB::transaction(function () use ($request, $now, $usuario, &$count) {
            foreach ($request->items as $item) {
                if (empty($item['id_material']) || empty($item['cantidad'])) continue;

                $tipo = strtoupper($item['tipo']);

                // ── Crear el movimiento en la bitácora ─────────────────────
                MovimientoInventario::create([
                    'id_material' => $item['id_material'],
                    'cantidad'    => $item['cantidad'],
                    'tipo'        => $tipo,
                    'id_proyecto' => $item['id_proyecto'] ?? null,
                    'descripcion' => $item['descripcion'] ?? null,
                    'fecha'       => $now,
                    'id_usuario'  => $usuario,
                ]);

                // ── Actualizar inventario manualmente (los triggers solo se
                //    disparan en uso_material y detalle_compra, NO aquí) ─────
                if (!empty($item['id_proyecto'])) {
                    $inventario = DB::table('inventario')
                        ->where('id_proyecto', $item['id_proyecto'])
                        ->where('id_material', $item['id_material'])
                        ->first();

                    if ($inventario) {
                        $delta = ($tipo === 'ENTRADA')
                            ? (float)$item['cantidad']
                            : -(float)$item['cantidad'];

                        DB::table('inventario')
                            ->where('id_inventario', $inventario->id_inventario)
                            ->update([
                                'cantidad_disponible'        => DB::raw("cantidad_disponible + {$delta}"),
                                'fecha_ultima_actualizacion' => $now,
                            ]);
                    }
                }

                $count++;
            }
        });

        return redirect()->route('operativa.movimientos.index')
            ->with('success', "{$count} movimiento(s) registrado(s) correctamente.");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT / UPDATE — Editar movimiento
    //
    // REGLA DE ORO:
    //   - Si el movimiento tiene id_uso_material (vino de un trigger de uso):
    //     Solo se permite editar la DESCRIPCIÓN. La cantidad la controla el trigger.
    //   - Si el movimiento NO tiene id_uso_material (movimiento manual):
    //     Se puede editar todo, y se corrige el inventario manualmente.
    // ─────────────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $movimiento = MovimientoInventario::with(['material', 'proyecto'])->findOrFail($id);
        $materiales = Material::orderBy('nombre')->get();
        $proyectos  = Proyecto::orderBy('nombre_proyecto')->get();

        // Indicamos a la vista si este movimiento es automático (de trigger)
        $esAutomatico = !empty($movimiento->id_uso_material);

        return view('operativa.movimientos.edit', compact(
            'movimiento', 'materiales', 'proyectos', 'esAutomatico'
        ));
    }

    public function update(Request $request, $id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);

        $esAutomatico = !empty($movimiento->id_uso_material);

        if ($esAutomatico) {
            // ── Movimiento generado por trigger: SOLO editar descripción ────
            $data = $request->validate([
                'descripcion' => 'nullable|string|max:500',
            ]);

            $movimiento->update(['descripcion' => $data['descripcion']]);

            return redirect()->route('operativa.movimientos.index')
                ->with('success', 'Descripción actualizada. La cantidad no se modificó (movimiento automático).');
        }

        // ── Movimiento manual: se puede editar todo ─────────────────────────
        $data = $request->validate([
            'id_material' => 'required|integer|exists:material,id_material',
            'cantidad'    => 'required|numeric|min:0.0001',
            'id_proyecto' => 'nullable|integer|exists:proyecto,id_proyecto',
            'descripcion' => 'nullable|string|max:500',
            'tipo'        => 'required|in:entrada,salida,ENTRADA,SALIDA',
        ]);
        $data['tipo'] = strtoupper($data['tipo']);

        DB::transaction(function () use ($movimiento, $data) {

            // ── Paso 1: Revertir el efecto del movimiento VIEJO en inventario
            if (!empty($movimiento->id_proyecto)) {
                $inv = DB::table('inventario')
                    ->where('id_proyecto', $movimiento->id_proyecto)
                    ->where('id_material', $movimiento->id_material)
                    ->first();

                if ($inv) {
                    // Devolver la cantidad vieja (operación inversa)
                    $deltaReversa = ($movimiento->tipo === 'ENTRADA')
                        ? -(float)$movimiento->cantidad   // era entrada → restar
                        :  (float)$movimiento->cantidad;  // era salida  → sumar

                    DB::table('inventario')
                        ->where('id_inventario', $inv->id_inventario)
                        ->update([
                            'cantidad_disponible'        => DB::raw("cantidad_disponible + {$deltaReversa}"),
                            'fecha_ultima_actualizacion' => now(),
                        ]);
                }
            }

            // ── Paso 2: Aplicar el efecto del movimiento NUEVO ───────────────
            if (!empty($data['id_proyecto'])) {
                $inv = DB::table('inventario')
                    ->where('id_proyecto', $data['id_proyecto'])
                    ->where('id_material', $data['id_material'])
                    ->first();

                if ($inv) {
                    $deltaNuevo = ($data['tipo'] === 'ENTRADA')
                        ? (float)$data['cantidad']
                        : -(float)$data['cantidad'];

                    DB::table('inventario')
                        ->where('id_inventario', $inv->id_inventario)
                        ->update([
                            'cantidad_disponible'        => DB::raw("cantidad_disponible + {$deltaNuevo}"),
                            'fecha_ultima_actualizacion' => now(),
                        ]);
                }
            }

            // ── Paso 3: Actualizar la bitácora ───────────────────────────────
            $movimiento->update($data);
        });

        return redirect()->route('operativa.movimientos.index')
            ->with('success', 'Movimiento e inventario actualizados correctamente.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $movimiento = MovimientoInventario::findOrFail($id);

        // Si es manual, revertir el efecto en inventario antes de borrar
        if (empty($movimiento->id_uso_material) && !empty($movimiento->id_proyecto)) {
            $inv = DB::table('inventario')
                ->where('id_proyecto', $movimiento->id_proyecto)
                ->where('id_material', $movimiento->id_material)
                ->first();

            if ($inv) {
                $delta = ($movimiento->tipo === 'ENTRADA')
                    ? -(float)$movimiento->cantidad
                    :  (float)$movimiento->cantidad;

                DB::table('inventario')
                    ->where('id_inventario', $inv->id_inventario)
                    ->update([
                        'cantidad_disponible'        => DB::raw("cantidad_disponible + {$delta}"),
                        'fecha_ultima_actualizacion' => now(),
                    ]);
            }
        }

        $movimiento->delete();

        return redirect()->route('operativa.movimientos.index')
            ->with('success', 'Movimiento eliminado y stock corregido.');
    }
}