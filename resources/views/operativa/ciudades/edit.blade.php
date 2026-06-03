@extends('layouts.app')

@section('title', 'Editar Ciudad')
@section('page_title', 'Editar Ciudad')
@section('page_subtitle', 'Maestros · Modificar ciudad')

@section('content')
<div class="container-fluid" style="max-width:560px;">
    <h4 class="fw-light text-secondary mb-3">Editar Ciudad</h4>
    <hr>
    <form action="{{ route('operativa.ciudades.update', $ciudad->id_ciudad) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $ciudad->nombre) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Departamento *</label>
            <input type="text" name="departamento" class="form-control" value="{{ old('departamento', $ciudad->departamento) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">País *</label>
            <input type="text" name="pais" class="form-control" value="{{ old('pais', $ciudad->pais) }}" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('operativa.ciudades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
