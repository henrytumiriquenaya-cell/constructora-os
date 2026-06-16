@extends('layouts.app')

@section('title', 'Editar Maquinaria')
@section('page_title', 'Editar Maquinaria')
@section('page_subtitle', 'Gestión Operativa · Modificar catálogo de maquinaria')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Editar Maquinaria</h4>
        <a href="{{ route('operativa.maquinarias.catalogo') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver al catálogo
        </a>
    </div>

    <div class="page-card">
        <form action="{{ route('operativa.maquinarias.catalogo_update', $maquinaria->id_maquinaria) }}" method="POST">
            @csrf
            @method('PUT')

            <h6 class="text-muted mb-4"><i class="fas fa-tractor me-2"></i>Información de la Maquinaria</h6>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre / Descripción <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $maquinaria->nombre) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca', $maquinaria->marca) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="{{ old('modelo', $maquinaria->modelo) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año</label>
                    <input type="number" name="anio" class="form-control" value="{{ old('anio', $maquinaria->anio) }}" min="1950" max="{{ date('Y')+1 }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Costo por Hora (Bs/USD) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="costo_hora" class="form-control" value="{{ old('costo_hora', $maquinaria->costo_hora) }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Propiedad <span class="text-danger">*</span></label>
                    <select name="propiedad" class="form-select" required>
                        <option value="propia" {{ old('propiedad', $maquinaria->propiedad) == 'propia' ? 'selected' : '' }}>Propia</option>
                        <option value="alquilada" {{ old('propiedad', $maquinaria->propiedad) == 'alquilada' ? 'selected' : '' }}>Alquilada</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado_actual" class="form-select" required>
                        <option value="disponible" {{ old('estado', $maquinaria->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="en_uso" {{ old('estado', $maquinaria->estado) == 'en_uso' ? 'selected' : '' }}>En uso (Asignada)</option>
                        <option value="en_mantenimiento" {{ old('estado', $maquinaria->estado) == 'en_mantenimiento' ? 'selected' : '' }}>En mantenimiento</option>
                        <option value="fuera_servicio" {{ old('estado', $maquinaria->estado) == 'fuera_servicio' ? 'selected' : '' }}>Fuera de servicio</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('operativa.maquinarias.catalogo') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
