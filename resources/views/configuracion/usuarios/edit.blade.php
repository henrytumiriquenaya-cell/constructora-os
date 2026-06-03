@extends('layouts.app')

@section('title', 'Editar Usuario')
@section('page_title', 'Editar Usuario')
@section('page_subtitle', 'Configuración · Modificar usuario')

@section('content')
<div class="container-fluid" style="max-width:600px">
    <h3 class="fw-light text-secondary mb-4">Configuración &rsaquo; Editar Usuario</h3>
    <form action="{{ route('configuracion.usuarios.update', $usuario->id_usuario) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Usuario (Login) <span class="text-danger">*</span></label>
                <input type="text" name="usuario" class="form-control @error('usuario') is-invalid @enderror" value="{{ old('usuario', $usuario->usuario) }}" required maxlength="100">
                @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ old('nombre_completo', $usuario->nombre_completo ?? $usuario->nombre_usuario) }}" required maxlength="200">
                @error('nombre_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $usuario->correo) }}" required maxlength="120">
                @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="col-12">
                <div class="alert alert-info small py-2 mb-0">
                    <i class="fas fa-info-circle"></i> Deja la contraseña en blanco si no deseas cambiarla.
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="6">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="6">
            </div>
            <div class="col-12">
                <label class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach(array_keys(config('permissions.roles', [])) as $rol)
                        <option value="{{ $rol }}" {{ old('rol', strtolower($usuario->rol)) == $rol ? 'selected' : '' }}>{{ ucfirst($rol) }}</option>
                    @endforeach
                </select>
                @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Actualizar</button>
                <a href="{{ route('configuracion.usuarios.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
