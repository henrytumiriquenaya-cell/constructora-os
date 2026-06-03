@extends('layouts.app')

@section('title', 'Nuevo Usuario')
@section('page_title', 'Nuevo Usuario')
@section('page_subtitle', 'Configuración · Registrar usuario')

@section('content')
<div class="container-fluid" style="max-width:600px">
    <h3 class="fw-light text-secondary mb-4">Configuración &rsaquo; Nuevo Usuario</h3>
    <form action="{{ route('configuracion.usuarios.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Usuario (Login) <span class="text-danger">*</span></label>
                <input type="text" name="usuario" class="form-control @error('usuario') is-invalid @enderror" value="{{ old('usuario') }}" required maxlength="100">
                @error('usuario')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" name="nombre_completo" class="form-control @error('nombre_completo') is-invalid @enderror" value="{{ old('nombre_completo') }}" required maxlength="200">
                @error('nombre_completo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required maxlength="120">
                @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="6">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <div class="col-12">
                <label class="form-label">Rol <span class="text-danger">*</span></label>
                <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach(array_keys(config('permissions.roles', [])) as $rol)
                        <option value="{{ $rol }}" {{ old('rol') == $rol ? 'selected' : '' }}>{{ ucfirst($rol) }}</option>
                    @endforeach
                </select>
                @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Guardar</button>
                <a href="{{ route('configuracion.usuarios.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
