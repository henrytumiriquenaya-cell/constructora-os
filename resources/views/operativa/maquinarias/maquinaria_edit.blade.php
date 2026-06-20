@extends('layouts.app')

@section('title', 'Editar Maquinaria')
@section('page_title', 'Editar Maquinaria')
@section('page_subtitle', 'Gestión Operativa · Modificar catálogo de maquinaria')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Gestión Operativa &rsaquo; Editar Maquinaria</h4>
        <a href="{{ route('operativa.maquinarias.catalogo') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver al catálogo
        </a>
    </div>

    <div class="page-card">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('operativa.maquinarias.catalogo_update', $maquinaria->id_maquinaria) }}" method="POST">
            @csrf
            @method('PUT')

            <h6 class="text-muted mb-4"><i class="fas fa-tractor me-2"></i>Información de la Maquinaria</h6>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código Inventario *</label>
                    <input type="text" name="codigo_inventario" class="form-control"
                           value="{{ old('codigo_inventario', $maquinaria->codigo_inventario) }}" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nombre / Descripción *</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $maquinaria->nombre) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <input type="text" name="tipo" class="form-control"
                           value="{{ old('tipo', $maquinaria->tipo) }}" required
                           placeholder="Ej: Excavadora, Grúa, Bulldozer">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca *</label>
                    <input type="text" name="marca" class="form-control"
                           value="{{ old('marca', $maquinaria->marca) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Modelo *</label>
                    <input type="text" name="modelo" class="form-control"
                           value="{{ old('modelo', $maquinaria->modelo) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año Fabricación</label>
                    <input type="number" name="anio_fabricacion" class="form-control"
                           value="{{ old('anio_fabricacion', $maquinaria->anio_fabricacion) }}"
                           min="1900" max="{{ date('Y')+1 }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Número de Serie</label>
                    <input type="text" name="numero_serie" class="form-control"
                           value="{{ old('numero_serie', $maquinaria->numero_serie) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Capacidad</label>
                    <input type="number" step="0.01" name="capacidad" class="form-control"
                           value="{{ old('capacidad', $maquinaria->capacidad) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Unidad Capacidad</label>
                    <input type="text" name="unidad_capacidad" class="form-control"
                           value="{{ old('unidad_capacidad', $maquinaria->unidad_capacidad) }}"
                           placeholder="Ej: Tn, m3, HP">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Costo por Hora (Bs.) *</label>
                    <input type="number" step="0.01" name="costo_hora" class="form-control"
                           value="{{ old('costo_hora', $maquinaria->costo_hora) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Propiedad *</label>
                    <select name="tipo_propiedad" class="form-select" required>
                        <option value="propio" {{ old('tipo_propiedad', $maquinaria->tipo_propiedad) == 'propio' ? 'selected' : '' }}>Propio</option>
                        <option value="arrendado" {{ old('tipo_propiedad', $maquinaria->tipo_propiedad) == 'arrendado' ? 'selected' : '' }}>Arrendado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado_actual" class="form-select" required>
                        @foreach(['disponible','en_uso','en_mantenimiento','fuera_servicio'] as $est)
                            <option value="{{ $est }}" {{ old('estado_actual', $maquinaria->estado_actual) == $est ? 'selected' : '' }}>
                                {{ str_replace('_',' ', ucfirst($est)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha Adquisición</label>
                    <input type="date" name="fecha_adquisicion" class="form-control"
                           value="{{ old('fecha_adquisicion', $maquinaria->fecha_adquisicion) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $maquinaria->observaciones) }}</textarea>
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
</div>
@endsection