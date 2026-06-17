@extends('layouts.app')

@section('title', 'Editar Proyecto')
@section('page_title', 'Editar Proyecto')
@section('page_subtitle', 'Gestión Operativa · Modificar proyecto')

@section('content')
<div class="container-fluid" style="max-width:700px">
    <h3 class="fw-light text-secondary mb-4">Gestión Operativa &rsaquo; Editar Proyecto</h3>
    <form action="{{ route('operativa.proyectos.update', $proyecto->id_proyecto) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Contrato asociado <span class="text-danger">*</span></label>
                <select name="id_contrato" class="form-select @error('id_contrato') is-invalid @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($contratos as $con)
                        <option value="{{ $con->id_contrato }}" {{ old('id_contrato', $proyecto->id_contrato) == $con->id_contrato ? 'selected' : '' }}>
                            {{ $con->numero_contrato }} — {{ $con->cliente->nombre_razon ?? '' }}
                        </option>
                    @endforeach
                </select>
                @error('id_contrato')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-8">
                <label class="form-label">Nombre del proyecto <span class="text-danger">*</span></label>
                <input type="text" name="nombre_proyecto" class="form-control @error('nombre_proyecto') is-invalid @enderror"
                       value="{{ old('nombre_proyecto', $proyecto->nombre_proyecto) }}" required maxlength="200">
                @error('nombre_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Código</label>
                <input type="text" name="codigo_proyecto" class="form-control"
                       value="{{ old('codigo_proyecto', $proyecto->codigo_proyecto) }}" maxlength="30">
            </div>
            <div class="col-12">
                <label class="form-label">Ubicación</label>
                <input type="text" name="ubicacion" class="form-control"
                       value="{{ old('ubicacion', $proyecto->ubicacion) }}" maxlength="300">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tipo de obra</label>
                <input type="text" name="tipo_obra" class="form-control"
                       value="{{ old('tipo_obra', $proyecto->tipo_obra) }}" maxlength="80">
            </div>
            <div class="col-md-6">
                <label class="form-label">Superficie (m²)</label>
                <input type="number" step="0.01" name="superficie_m2" class="form-control"
                       value="{{ old('superficie_m2', $proyecto->superficie_m2) }}" min="0">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha inicio real</label>
                <input type="date" name="fecha_inicio_real" class="form-control"
                       value="{{ old('fecha_inicio_real', $proyecto->fecha_inicio_real) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin programada</label>
                <input type="date" name="fecha_fin_programada" class="form-control"
                       value="{{ old('fecha_fin_programada', $proyecto->fecha_fin_programada) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin real</label>
                <input type="date" name="fecha_fin_real" class="form-control"
                       value="{{ old('fecha_fin_real', $proyecto->fecha_fin_real) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    @foreach(['planificacion','en_ejecucion','paralizado','concluido','cancelado','abandonado'] as $est)
                        <option value="{{ $est }}" {{ old('estado', $proyecto->estado) == $est ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$est)) }}</option>
                    @endforeach
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">% Avance</label>
                <input type="number" name="porcentaje_avance" class="form-control"
                       value="{{ old('porcentaje_avance', $proyecto->porcentaje_avance) }}" min="0" max="100">
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.proyectos.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
