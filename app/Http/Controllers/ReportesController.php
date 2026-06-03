<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function costos()
    {
        // Resumen de costos por proyecto calculado desde las tablas de gasto y proyecto.
        $datos = DB::table('proyecto as p')
            ->leftJoin('gasto as g', 'p.id_proyecto', '=', 'g.id_proyecto')
            ->leftJoin('contrato as con', 'p.id_contrato', '=', 'con.id_contrato')
            ->select(
                'p.id_proyecto',
                'p.nombre_proyecto',
                'p.estado',
                'p.porcentaje_avance',
                DB::raw('COALESCE(SUM(g.monto), 0) as total_gastos'),
                DB::raw('MAX(con.monto_total) as monto_contrato')
            )
            ->groupBy('p.id_proyecto', 'p.nombre_proyecto', 'p.estado', 'p.porcentaje_avance')
            ->orderBy('p.nombre_proyecto')
            ->paginate(15);

        return view('reportes.costos.index', compact('datos'));
    }
}
