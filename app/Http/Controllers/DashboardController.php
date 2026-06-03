<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ParalizacionObra;
use App\Models\Proyecto;
use App\Models\RegistroHoraDiario;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(PermissionService $permissions)
    {
        $user = Auth::user();

        $totalClientes = $permissions->canSelect($user, 'cliente') ? Cliente::count() : null;
        $totalProyectos = $permissions->canSelect($user, 'proyecto') ? Proyecto::count() : null;
        $totalHoras = $permissions->canSelect($user, 'registro_horas')
            ? RegistroHoraDiario::sum('horas_normales')
            : null;
        $totalParalizaciones = $permissions->canSelect($user, 'paralizacion')
            ? ParalizacionObra::count()
            : null;

        $ultimosProyectos = $permissions->canSelect($user, 'proyecto')
            ? Proyecto::latest('id_proyecto')->take(5)->get()
            : collect();

        return view('dashboard', compact(
            'totalClientes',
            'totalProyectos',
            'totalHoras',
            'totalParalizaciones',
            'ultimosProyectos'
        ));
    }
}