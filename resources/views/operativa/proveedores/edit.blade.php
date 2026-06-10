@extends('layouts.app')

@section('title', 'Editar Proveedor')
@section('page_title', 'Editar Proveedor')
@section('page_subtitle', 'Gestión Operativa · Actualizar proveedor')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Editar Proveedor</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">Modifique los datos del proveedor <strong style="color:var(--indigo);">{{ $proveedor->razon_social }}</strong>.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('operativa.proveedores.update', $proveedor->id_proveedor) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Razón Social *</label>
                    <input type="text" name="razon_social" class="form-control" value="{{ old('razon_social', $proveedor->razon_social) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIT / RUC</label>
                    <input type="text" name="nit" class="form-control" value="{{ old('nit', $proveedor->nit) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ciudad</label>
                    <select name="id_ciudad" class="form-select">
                        <option value="">— Sin ciudad —</option>
                        @foreach($ciudades as $c)
                            <option value="{{ $c->id_ciudad }}" {{ old('id_ciudad', $proveedor->id_ciudad) == $c->id_ciudad ? 'selected' : '' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre Contacto *</label>
                    <input type="text" name="contacto_nombre" class="form-control" value="{{ old('contacto_nombre', $proveedor->contacto_nombre) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo *</label>
                    <input type="email" name="correo" class="form-control" value="{{ old('correo', $proveedor->correo) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dirección *</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria" class="form-select" required>
                        @foreach(['materiales','maquinaria','servicios','mixto'] as $cat)
                            <option value="{{ $cat }}" {{ old('categoria', $proveedor->categoria) === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Calificación (0-5)</label>
                    <input type="number" name="calificacion" class="form-control" value="{{ old('calificacion', $proveedor->calificacion) }}" min="0" max="5" step="0.1">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch mt-1">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" id="sw_activo" {{ old('activo', $proveedor->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sw_activo" style="color:var(--text-secondary);">Proveedor activo</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
