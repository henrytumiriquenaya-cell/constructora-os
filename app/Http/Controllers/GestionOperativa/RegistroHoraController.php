<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\RegistroHoraDiario;
use App\Models\Proyecto;
use Illuminate\Http\Request;


class RegistroHoraController extends Controller
{
    public function index()
    {
        // Traemos los registros cargando la relación con proyecto para ver el nombre
        $registros = RegistroHoraDiario::with('proyecto')->orderBy('fecha_trabajo', 'desc')->paginate(15);
        return view('operativa.asistencia.index', compact('registros'));
    }

    public function create()
    {
        $proyectos = Proyecto::where('estado', 'Activo')->get();
        return view('operativa.asistencia.create', compact('proyectos'));
    }
}