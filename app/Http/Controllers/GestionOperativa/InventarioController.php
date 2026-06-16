<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // INDEX — Muestra el stock actual calculado desde movimientos reales
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
{
    $idProyecto = $request->query('id_proyecto');

    $query = DB::table('inventario as i')
        ->join('material as m', 'i.id_material', '=', 'm.id_material')
        ->join('proyecto as p', 'i.id_proyecto', '=', 'p.id_proyecto')
        ->select(
            'i.id_proyecto',
            'p.nombre_proyecto',
            'i.id_material',
            'm.nombre as material',
            'm.unidad_medida',
            'i.cantidad_disponible',
            'i.cantidad_reservada',
            'm.stock_minimo',
            'i.ubicacion_fisica',
            'i.fecha_ultima_actualizacion'
        );

    if (!empty($idProyecto)) {
        $query->where('i.id_proyecto', $idProyecto);
    }

    $inventarioPaginator = $query
        ->orderBy('p.nombre_proyecto')
        ->orderBy('m.nombre')
        ->paginate(15);

    // Transformar manteniendo stdClass (no convertir a array)
    $inventarioPaginator->getCollection()->transform(function ($row) {
        $disponible = (float) ($row->cantidad_disponible ?? 0);
        $minimo     = (float) ($row->stock_minimo ?? 0);

        $row->semaforo = ($disponible <= 0)
            ? 'rojo'
            : (($disponible < $minimo) ? 'amarillo' : 'verde');

        return $row;
    });

    $inventario = $inventarioPaginator;

    // Sin paginate — es solo un dropdown
    $proyectos = DB::table('proyecto')
        ->select('id_proyecto', 'nombre_proyecto')
        ->orderBy('nombre_proyecto')
        ->get();

    return view('operativa.inventario.index', compact('inventario', 'proyectos', 'idProyecto'));
}

    // ─────────────────────────────────────────────────────────────────────────
    // REGISTRAR USO — Inserta en uso_material y deja que el TRIGGER haga el resto
    // ─────────────────────────────────────────────────────────────────────────
    public function registrarUso(Request $request)
    {
        $data = $request->validate([
            'id_proyecto'    => 'required|integer|exists:proyecto,id_proyecto',
            'id_material'    => 'required|integer|exists:material,id_material',
            'cantidad_usada' => 'required|numeric|min:0.0001',
            'descripcion_uso'=> 'nullable|string|max:255',
        ]);

        // 1. Buscar el registro de inventario que une proyecto + material
        $inventario = DB::table('inventario')
            ->where('id_proyecto', $data['id_proyecto'])
            ->where('id_material', $data['id_material'])
            ->first();

        if (!$inventario) {
            return redirect()->back()->withInput()
                ->with('error', 'El material no está registrado en el inventario de este proyecto.');
        }

        // 2. Validar stock suficiente ANTES de insertar
        if ((float)$data['cantidad_usada'] > (float)$inventario->cantidad_disponible) {
            return redirect()->back()->withInput()
                ->with('error',
                    'Stock insuficiente. Disponible: '
                    . number_format($inventario->cantidad_disponible, 2)
                    . ' ' . ($inventario->unidad_medida ?? '')
                );
        }

        // 3. Resolver el id_empleado del usuario autenticado
        $empleado = DB::table('empleado')
            ->where('correo', auth()->user()->email)
            ->first();
        $idEmpleado = $empleado?->id_empleado ?? 1;

        // 4. Insertar en uso_material → el TRIGGER trg_inventario_uso se encarga de:
        //    a) Descontar cantidad_disponible en inventario
        //    b) Insertar el movimiento de SALIDA en movimiento_inventario
        //    c) Generar notificación si baja del stock mínimo
        DB::table('uso_material')->insert([
            'id_inventario'   => $inventario->id_inventario,
            'id_proyecto'     => $data['id_proyecto'],
            'id_material'     => $data['id_material'],
            'fecha_uso'       => now(),
            'cantidad_usada'  => $data['cantidad_usada'],
            'descripcion_uso' => $data['descripcion_uso'] ?? 'Registro desde inventario',
            'id_empleado'     => $idEmpleado,
        ]);

        return redirect()
            ->route('operativa.inventario.index', ['id_proyecto' => $data['id_proyecto']])
            ->with('success', 'Uso registrado. El inventario se actualizó automáticamente.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RECALCULAR — Sincroniza inventario desde los movimientos reales
    // Útil cuando hay desincronización. Solo accesible para admin.
    // ─────────────────────────────────────────────────────────────────────────
    public function recalcular(Request $request)
    {
        $idProyecto = $request->input('id_proyecto');

        // Para cada material en el inventario del proyecto, recalcula el stock
        // sumando entradas y restando salidas desde movimiento_inventario
        $query = DB::table('inventario as i')
            ->select('i.id_inventario', 'i.id_proyecto', 'i.id_material');

        if ($idProyecto) {
            $query->where('i.id_proyecto', $idProyecto);
        }

        $registros = $query->get();
        $actualizados = 0;

        foreach ($registros as $reg) {
            // Suma de ENTRADAS para este proyecto+material
            $entradas = DB::table('movimiento_inventario')
                ->where('id_proyecto', $reg->id_proyecto)
                ->where('id_material', $reg->id_material)
                ->where('tipo', 'ENTRADA')
                ->sum('cantidad');

            // Suma de SALIDAS para este proyecto+material
            $salidas = DB::table('movimiento_inventario')
                ->where('id_proyecto', $reg->id_proyecto)
                ->where('id_material', $reg->id_material)
                ->where('tipo', 'SALIDA')
                ->sum('cantidad');

            $stockReal = (float)$entradas - (float)$salidas;

            DB::table('inventario')
                ->where('id_inventario', $reg->id_inventario)
                ->update([
                    'cantidad_disponible'        => max(0, $stockReal),
                    'fecha_ultima_actualizacion' => now(),
                ]);

            $actualizados++;
        }

        return redirect()
            ->route('operativa.inventario.index', $idProyecto ? ['id_proyecto' => $idProyecto] : [])
            ->with('success', "Inventario recalculado. {$actualizados} registros sincronizados.");
    }
}