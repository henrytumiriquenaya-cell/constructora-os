<?php

namespace App\Http\Controllers\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado', 'activos');

        $query = Usuario::query();

        if ($estado === 'inactivos') {
            $query->where(function($q) {
                $q->where('activo', 0)
                  ->orWhere('activo', '0')
                  ->orWhere('activo', false)
                  ->orWhere('activo', 'false')
                  ->orWhere('activo', 'inactivo');
            });
        } else {
            // Activos
            $query->where(function($q) {
                $q->where('activo', 1)
                  ->orWhere('activo', '1')
                  ->orWhere('activo', true)
                  ->orWhere('activo', 'true')
                  ->orWhere('activo', 'activo')
                  ->orWhereNull('activo'); // Asumimos activos por defecto
            });
        }

        $usuarios = $query->orderBy('nombre_completo')->paginate(15);

        return view('configuracion.usuarios.index', compact('usuarios', 'estado'));
    }

    public function create()
    {
        return view('configuracion.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|max:100|unique:usuario,usuario',
            'nombre_completo' => 'required|string|max:200',
            'correo' => 'required|email|max:120|unique:usuario,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|string|max:50',
        ]);

        $usuario = new Usuario();
        $usuario->usuario = $validated['usuario'];
        // Mantener compatibilidad con nombre_usuario si es necesario
        $usuario->nombre_usuario = $validated['usuario']; 
        $usuario->nombre_completo = $validated['nombre_completo'];
        $usuario->correo = $validated['correo'];
        $usuario->password = Hash::make($validated['password']);
        $usuario->contrasena = Hash::make($validated['password']); // Para retrocompatibilidad
        $usuario->rol = $validated['rol'];
        $usuario->activo = 1;
        $usuario->save();

        return redirect()->route('configuracion.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('configuracion.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $rules = [
            'usuario' => ['required', 'string', 'max:100', Rule::unique('usuario')->ignore($usuario->id_usuario, 'id_usuario')],
            'nombre_completo' => 'required|string|max:200',
            'correo' => ['required', 'email', 'max:120', Rule::unique('usuario')->ignore($usuario->id_usuario, 'id_usuario')],
            'rol' => 'required|string|max:50',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validated = $request->validate($rules);

        $usuario->usuario = $validated['usuario'];
        $usuario->nombre_usuario = $validated['usuario'];
        $usuario->nombre_completo = $validated['nombre_completo'];
        $usuario->correo = $validated['correo'];
        $usuario->rol = $validated['rol'];

        if ($request->filled('password')) {
            $usuario->password = Hash::make($validated['password']);
            $usuario->contrasena = Hash::make($validated['password']);
        }

        $usuario->save();

        return redirect()->route('configuracion.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // Evitar que el administrador actual se desactive a sí mismo
        if (auth()->id() == $usuario->id_usuario) {
            return redirect()->route('configuracion.usuarios.index')->with('error', 'No puedes desactivar tu propio usuario.');
        }

        // Baja lógica
        $usuario->activo = 0;
        $usuario->save();

        return redirect()->route('configuracion.usuarios.index')->with('success', 'Usuario desactivado correctamente.');
    }
    
    public function restaurar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->activo = 1;
        $usuario->save();

        return redirect()->route('configuracion.usuarios.index', ['estado' => 'inactivos'])->with('success', 'Usuario reactivado correctamente.');
    }
}
