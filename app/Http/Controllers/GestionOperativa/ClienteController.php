<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderByDesc('id_cliente')->paginate(15);
        return view('operativa.clientes.index', compact('clientes'));
    }

    public function create()
    {
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        return view('operativa.clientes.create', compact('ciudades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_ciudad'           => 'nullable|integer',
            'tipo_cliente'        => 'required|in:natural,juridica',
            'nombre_razon'        => 'required|string|max:150',
            'documento_identidad' => 'required|string|max:20|unique:cliente,documento_identidad',
            'telefono_principal'  => 'required|string|max:15',
            'telefono_secundario' => 'nullable|string|max:15',
            'correo'              => 'required|email|max:100',
            'direccion'           => 'required|string|max:200',
            'estado'              => 'required|in:activo,inactivo,moroso',
        ]);

        $data['fecha_registro'] = now();
        Cliente::create($data);

        return redirect()->route('operativa.clientes.index')
                         ->with('success', 'Cliente registrado correctamente.');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('operativa.clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente  = Cliente::findOrFail($id);
        $ciudades = Ciudad::orderBy('nombre_ciudad')->get();
        return view('operativa.clientes.edit', compact('cliente', 'ciudades'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $data = $request->validate([
            'id_ciudad'           => 'nullable|integer',
            'tipo_cliente'        => 'required|in:natural,juridica',
            'nombre_razon'        => 'required|string|max:150',
            'documento_identidad' => 'required|string|max:20|unique:cliente,documento_identidad,'.$id.',id_cliente',
            'telefono_principal'  => 'required|string|max:15',
            'telefono_secundario' => 'nullable|string|max:15',
            'correo'              => 'required|email|max:100',
            'direccion'           => 'required|string|max:200',
            'estado'              => 'required|in:activo,inactivo,moroso',
        ]);

        $cliente->update($data);
        return redirect()->route('operativa.clientes.index')
                         ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return redirect()->route('operativa.clientes.index')
                         ->with('success', 'Cliente eliminado.');
    }
}