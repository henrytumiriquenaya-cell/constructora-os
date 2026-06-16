<?php

namespace App\Http\Controllers\GestionOperativa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index()
    {
        // Paginamos los materiales directamente desde la tabla 'material'
        $materiales = DB::table('material')->paginate(15);
        return view('operativa.materiales.index', compact('materiales'));
    }

    public function create()
    {
        return view('operativa.materiales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_interno' => 'required|string|max:50',
            'categoria' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'precio_unitario_ref' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        DB::table('material')->insert([
            'nombre' => $data['nombre'],
            'codigo_interno' => $data['codigo_interno'],
            'categoria' => $data['categoria'],
            'unidad_medida' => $data['unidad_medida'],
            'precio_unitario_ref' => $data['precio_unitario_ref'],
            'stock_minimo' => $data['stock_minimo'],
            'descripcion' => $data['descripcion'] ?? '',
            'cantidad' => 0.00, // Por defecto inicial
        ]);

        return redirect()->route('operativa.materiales.index')->with('success', 'Material creado exitosamente.');
    }

    public function edit($id)
    {
        $material = DB::table('material')->where('id_material', $id)->first();
        return view('operativa.materiales.edit', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_interno' => 'required|string|max:50',
            'categoria' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'precio_unitario_ref' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        DB::table('material')->where('id_material', $id)->update([
            'nombre' => $data['nombre'],
            'codigo_interno' => $data['codigo_interno'],
            'categoria' => $data['categoria'],
            'unidad_medida' => $data['unidad_medida'],
            'precio_unitario_ref' => $data['precio_unitario_ref'],
            'stock_minimo' => $data['stock_minimo'],
            'descripcion' => $data['descripcion'] ?? '',
        ]);

        return redirect()->route('operativa.materiales.index')->with('success', 'Material actualizado exitosamente.');
    }

    public function destroy($id)
    {
        DB::table('material')->where('id_material', $id)->delete();
        return redirect()->route('operativa.materiales.index')->with('success', 'Material eliminado.');
    }
}
