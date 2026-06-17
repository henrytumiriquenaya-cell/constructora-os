<?php
// app/Http/Controllers/GestionOperativa/ContratoController.php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    public function index()
    {
        $contratos = Contrato::with('cliente')
                             ->orderByDesc('id_contrato')
                             ->paginate(15);
        return view('operativa.contratos.index', compact('contratos'));
    }

    public function create()
    {
        $clientes = Cliente::where('estado', 'activo')
                           ->orderBy('nombre_razon')
                           ->get();
        return view('operativa.contratos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_cliente'        => 'required|integer',
            'numero_contrato'   => 'required|string|max:30|unique:contrato,numero_contrato',
            'fecha_firma'       => 'required|date',
            'fecha_inicio'      => 'required|date',
            'fecha_fin_prevista'=> 'required|date|after:fecha_inicio',
            'monto_total'       => 'required|numeric|min:0',
            'moneda'            => 'required|in:BOB,USD,EUR',
            'tipo_contrato'     => 'required|in:llave_en_mano,administracion,mixto',
            'estado'            => 'required|in:borrador,firmado,en_ejecucion,concluido,rescindido',
            'descripcion'       => 'nullable|string',
        ]);

        Contrato::create($data);
        return redirect()->route('operativa.contratos.index')
                         ->with('success', 'Contrato registrado correctamente.');
    }

    public function show($id)
    {
        $contrato = Contrato::with(['cliente', 'proyecto', 'cuotas'])
                            ->findOrFail($id);
        return view('operativa.contratos.show', compact('contrato'));
    }

    public function edit($id)
    {
        $contrato = Contrato::findOrFail($id);
        $clientes = Cliente::orderBy('nombre_razon')->get();
        return view('operativa.contratos.edit', compact('contrato', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);
        $data = $request->validate([
            'fecha_fin_prevista'=> 'required|date',
            'monto_total'       => 'required|numeric|min:0',
            'moneda'            => 'required|in:BOB,USD,EUR',
            'tipo_contrato'     => 'required|in:llave_en_mano,administracion,mixto',
            'estado'            => 'required|in:borrador,firmado,en_ejecucion,concluido,rescindido',
            'descripcion'       => 'nullable|string',
        ]);

        $contrato->update($data);
        return redirect()->route('operativa.contratos.index')
                         ->with('success', 'Contrato actualizado.');
    }

    public function destroy($id)
    {
        Contrato::findOrFail($id)->delete();
        return redirect()->route('operativa.contratos.index')
                         ->with('success', 'Contrato eliminado.');
    }
}