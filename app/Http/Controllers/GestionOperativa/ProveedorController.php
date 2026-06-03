<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Proyecto;
use App\Models\Empleado;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = \App\Models\Proveedor::with('ciudad')
                         ->orderBy('razon_social')
                         ->paginate(15);
        return view('operativa.proveedores.index', compact('proveedores'));
    }
 
    public function create()
    {
        $ciudades = \App\Models\Ciudad::orderBy('nombre')->get();
        return view('operativa.proveedores.create', compact('ciudades'));
    }
 
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_ciudad'       => 'nullable|integer',
            'razon_social'    => 'required|string|max:150',
            'nit'             => 'nullable|string|max:20',
            'contacto_nombre' => 'required|string|max:100',
            'telefono'        => 'required|string|max:15',
            'correo'          => 'required|email|max:100',
            'direccion'       => 'required|string|max:200',
            'categoria'       => 'required|in:materiales,maquinaria,servicios,mixto',
            'calificacion'    => 'nullable|numeric|min:0|max:5',
            'activo'          => 'boolean',
        ]);
        $data['activo'] = $request->boolean('activo', true);
 
        \App\Models\Proveedor::create($data);
        return redirect()->route('operativa.proveedores.index')
                         ->with('success', 'Proveedor registrado.');
    }
 
    public function edit($id)
    {
        $proveedor = \App\Models\Proveedor::findOrFail($id);
        $ciudades  = \App\Models\Ciudad::orderBy('nombre')->get();
        return view('operativa.proveedores.edit', compact('proveedor', 'ciudades'));
    }
 
    public function update(Request $request, $id)
    {
        $proveedor = \App\Models\Proveedor::findOrFail($id);
        $data = $request->validate([
            'id_ciudad'       => 'nullable|integer',
            'razon_social'    => 'required|string|max:150',
            'nit'             => 'nullable|string|max:20',
            'contacto_nombre' => 'required|string|max:100',
            'telefono'        => 'required|string|max:15',
            'correo'          => 'required|email|max:100',
            'direccion'       => 'required|string|max:200',
            'categoria'       => 'required|in:materiales,maquinaria,servicios,mixto',
            'calificacion'    => 'nullable|numeric|min:0|max:5',
            'activo'          => 'boolean',
        ]);
        $data['activo'] = $request->boolean('activo');
 
        $proveedor->update($data);
        return redirect()->route('operativa.proveedores.index')
                         ->with('success', 'Proveedor actualizado.');
    }
 
    public function destroy($id)
    {
        \App\Models\Proveedor::findOrFail($id)->delete();
        return redirect()->route('operativa.proveedores.index')
                         ->with('success', 'Proveedor eliminado.');
    }
}
