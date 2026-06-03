<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $idProyecto = $request->query('id_proyecto');

        // Lectura desde las tablas de inventario, material y proyecto.
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

        if (! empty($idProyecto)) {
            $query->where('i.id_proyecto', $idProyecto);
        }

        $inventario = $query
            ->orderBy('p.nombre_proyecto')
            ->orderBy('m.nombre')
            ->paginate(15)
            ->map(function ($row) {
                $item = (array) $row;
                $disponible = (float) ($item['cantidad_disponible'] ?? 0);
                $minimo = (float) ($item['stock_minimo'] ?? 0);

                $item['semaforo'] = $disponible < $minimo ? 'rojo' : 'verde';
                return $item;
            });

        $proyectos = DB::table('proyecto')
            ->select('id_proyecto', 'nombre_proyecto')
            ->orderBy('nombre_proyecto')
            ->paginate(15);

        return view('operativa.inventario.index', compact('inventario', 'proyectos', 'idProyecto'));
    }

    public function registrarUso(Request $request)
    {
        $data = $request->validate([
            'id_proyecto' => 'required|integer',
            'id_material' => 'required|integer',
            'cantidad_usada' => 'required|numeric|min:0.0001',
            'descripcion_uso' => 'nullable|string|max:255',
        ]);

        DB::table('uso_material')->insert([
            'id_proyecto' => $data['id_proyecto'],
            'id_material' => $data['id_material'],
            'fecha_uso' => now(),
            'cantidad_usada' => $data['cantidad_usada'],
            'descripcion_uso' => $data['descripcion_uso'] ?? 'Registro desde inventario',
            'id_empleado' => null,
        ]);

        return redirect()->route('operativa.inventario.index', ['id_proyecto' => $data['id_proyecto']])
            ->with('success', 'Uso de material registrado. El trigger de inventario aplicó el descuento automático.');
    }
}
