<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — Muestra el stock CENTRAL actual (un solo registro por material).
    // Ya no carga proyectos ni el modal de uso.
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $inventarioPaginator = DB::table('inventario as i')
            ->join('material as m', 'i.id_material', '=', 'm.id_material')
            ->select(
                'i.id_material',
                'm.nombre as material',
                'm.unidad_medida',
                'i.cantidad_disponible',
                'i.cantidad_reservada',
                'm.stock_minimo',
                'i.ubicacion_fisica',
                'i.fecha_ultima_actualizacion'
            )
            ->orderBy('m.nombre')
            ->paginate(15);

        $inventarioPaginator->getCollection()->transform(function ($row) {
            $disponible = (float) ($row->cantidad_disponible ?? 0);
            $minimo     = (float) ($row->stock_minimo ?? 0);

            $row->semaforo = ($disponible <= 0)
                ? 'rojo'
                : (($disponible < $minimo) ? 'amarillo' : 'verde');

            return $row;
        });

        return view('operativa.inventario.index', [
            'inventario' => $inventarioPaginator,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CREATE — Vista independiente para registrar uso de material.
    // Carga materiales con stock, proyectos activos y los últimos usos.
    // ─────────────────────────────────────────────────────────────────────────
    public function create()
    {
        // Materiales que tienen stock en inventario central
        $materiales = DB::table('inventario as i')
            ->join('material as m', 'i.id_material', '=', 'm.id_material')
            ->select(
                'm.id_material',
                'm.nombre',
                'm.unidad_medida',
                'i.cantidad_disponible'
            )
            ->where('i.cantidad_disponible', '>', 0)
            ->orderBy('m.nombre')
            ->get();

        // Proyectos activos
        $proyectos = DB::table('proyecto')
            ->select('id_proyecto', 'nombre_proyecto')
            ->orderBy('nombre_proyecto')
            ->get();

        // Últimos 10 usos registrados para mostrar en la vista
        $ultimosUsos = DB::table('uso_material as u')
            ->join('material as m', 'u.id_material', '=', 'm.id_material')
            ->join('proyecto as p', 'u.id_proyecto', '=', 'p.id_proyecto')
            ->select(
                'u.fecha_uso',
                'm.nombre as material',
                'm.unidad_medida',
                'p.nombre_proyecto',
                'u.cantidad_usada'
            )
            ->orderByDesc('u.fecha_uso')
            ->limit(10)
            ->get();

        return view('operativa.inventario.uso_material', compact('materiales', 'proyectos', 'ultimosUsos'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // REGISTRAR USO — Inserta en uso_material y deja que el TRIGGER haga el resto:
    //   a) Descontar cantidad_disponible en inventario (por id_material)
    //   b) Insertar el movimiento de SALIDA en movimiento_inventario (con id_proyecto)
    //   c) Generar notificación si baja del stock mínimo
    // ─────────────────────────────────────────────────────────────────────────
    public function registrarUso(Request $request)
    {
        $data = $request->validate([
            'id_proyecto'     => 'required|integer|exists:proyecto,id_proyecto',
            'id_material'     => 'required|integer|exists:material,id_material',
            'cantidad_usada'  => 'required|numeric|min:0.0001',
            'descripcion_uso' => 'nullable|string|max:255',
        ]);

        // 1. Buscar el registro de inventario CENTRAL para este material
        $inventario = DB::table('inventario')
            ->where('id_material', $data['id_material'])
            ->first();

        if (!$inventario) {
            return redirect()->back()->withInput()
                ->with('error', 'El material no está registrado en el inventario central.');
        }

        // 2. Validar stock suficiente ANTES de insertar
        if ((float) $data['cantidad_usada'] > (float) $inventario->cantidad_disponible) {
            $unidad = DB::table('material')
                ->where('id_material', $data['id_material'])
                ->value('unidad_medida');

            return redirect()->back()->withInput()
                ->with('error',
                    'Stock insuficiente en almacén central. Disponible: '
                    . number_format($inventario->cantidad_disponible, 2)
                    . ' ' . ($unidad ?? '')
                );
        }

        // 3. Resolver el id_empleado del usuario autenticado
        $empleado   = DB::table('empleado')
            ->where('correo', auth()->user()->email)
            ->first();
        $idEmpleado = $empleado?->id_empleado ?? 1;

        // 4. Insertar en uso_material → el TRIGGER trg_inventario_uso se encarga del resto
        DB::table('uso_material')->insert([
            'id_inventario'   => $inventario->id_inventario,
            'id_proyecto'     => $data['id_proyecto'],
            'id_material'     => $data['id_material'],
            'fecha_uso'       => now(),
            'cantidad_usada'  => $data['cantidad_usada'],
            'descripcion_uso' => $data['descripcion_uso'] ?? 'Registro desde módulo de uso',
            'id_empleado'     => $idEmpleado,
        ]);

        return redirect()
            ->route('operativa.inventario.uso.create')
            ->with('success', 'Uso registrado. El inventario central y los movimientos se actualizaron automáticamente.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RECALCULAR — Sincroniza inventario CENTRAL desde los movimientos reales.
    // ─────────────────────────────────────────────────────────────────────────
    public function recalcular(Request $request)
    {
        $registros    = DB::table('inventario as i')
            ->select('i.id_inventario', 'i.id_material')
            ->get();

        $actualizados = 0;

        foreach ($registros as $reg) {
            $entradas = DB::table('movimiento_inventario')
                ->where('id_material', $reg->id_material)
                ->where('tipo', 'ENTRADA')
                ->sum('cantidad');

            $salidas = DB::table('movimiento_inventario')
                ->where('id_material', $reg->id_material)
                ->where('tipo', 'SALIDA')
                ->sum('cantidad');

            $stockReal = (float) $entradas - (float) $salidas;

            DB::table('inventario')
                ->where('id_inventario', $reg->id_inventario)
                ->update([
                    'cantidad_disponible'        => max(0, $stockReal),
                    'fecha_ultima_actualizacion' => now(),
                ]);

            $actualizados++;
        }

        return redirect()
            ->route('operativa.inventario.index')
            ->with('success', "Inventario central recalculado. {$actualizados} registros sincronizados.");
    }
}