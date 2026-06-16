<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function costos()
    {
        $datos = DB::table('v_costos_proyecto')
            ->orderBy('nombre_proyecto')
            ->paginate(15);

        return view('reportes.costos.index', compact('datos'));
    }
}
