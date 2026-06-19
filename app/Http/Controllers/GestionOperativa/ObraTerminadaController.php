<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\ObraTerminada;
use Illuminate\Support\Facades\Auth;

class ObraTerminadaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

    $query = ObraTerminada::with('proyecto.contrato');

    if ($usuario->hasRole('cliente')) {

        $query->whereHas('proyecto.contrato', function ($q) use ($usuario) {
            $q->where('id_cliente', $usuario->id_cliente);
        });

}

$obras = $query->paginate(15);
        return view('operativa.finalizadas.index', compact('obras'));
    }
}