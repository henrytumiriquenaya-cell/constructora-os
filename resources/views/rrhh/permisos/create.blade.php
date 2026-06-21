@extends('layouts.app')

@section('title', 'Nuevo Permiso')
@section('page_title', 'Nuevo Permiso')
@section('page_subtitle', 'Recursos Humanos · Registrar nuevo permiso o trámite')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Recursos Humanos &rsaquo; Nuevo Permiso</h4>
        <a href="{{ route('rrhh.permisos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver a permisos
        </a>
    </div>

    <div class="page-card">
        <form action="{{ route('rrhh.permisos.store') }}" method="POST">
            @csrf

            <h6 class="text-muted mb-4"><i class="fas fa-file-contract me-2"></i>Información del Permiso</h6>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">
                        Proyecto <span class="text-danger">*</span>
                    </label>

                    <select name="id_proyecto" class="form-select" required>
                        <option value="">Seleccione un proyecto...</option>

                        @foreach($proyectos as $p)
                            <option value="{{ $p->id_proyecto }}">
                                {{ $p->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo de Permiso <span class="text-danger">*</span></label>
                    <select name="tipo_permiso" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="municipal" {{ old('tipo_permiso') == 'municipal' ? 'selected' : '' }}>Municipal</option>
                        <option value="ambiental" {{ old('tipo_permiso') == 'ambiental' ? 'selected' : '' }}>Ambiental</option>
                        <option value="sanitario" {{ old('tipo_permiso') == 'sanitario' ? 'selected' : '' }}>Sanitario</option>
                        <option value="bomberos" {{ old('tipo_permiso') == 'bomberos' ? 'selected' : '' }}>Bomberos</option>
                        <option value="otro" {{ old('tipo_permiso') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Entidad Emisora <span class="text-danger">*</span></label>
                    <input type="text" name="entidad_emisora" class="form-control" value="{{ old('entidad_emisora') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Solicitud <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_solicitud" class="form-control" value="{{ old('fecha_solicitud') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Emisión</label>
                    <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Costo del Trámite (Bs/USD)</label>
                    <input type="number" step="0.01" name="costo_tramite" class="form-control" value="{{ old('costo_tramite', 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                    <select name="estado" class="form-select" required>
                        <option value="aprobado" {{ old('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ old('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                        <option value="vencido" {{ old('estado') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                        <option value="renovado" {{ old('estado') == 'renovado' ? 'selected' : '' }}>Renovado</option>
                        
                    </select>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('rrhh.permisos.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Guardar Permiso
                </button>
            </div>
        </form>
    </div>
@endsection
