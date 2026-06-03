<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\ObraTerminada;

class ObraTerminadaController extends Controller
{
    public function index()
    {
        $obras = ObraTerminada::with('proyecto')->paginate(15);
        return view('operativa.finalizadas.index', compact('obras'));
    }
}