@extends('layouts.app')

@section('title', 'Registro de Horas')
@section('page_title', 'Nuevo Registro de Horas')
@section('page_subtitle', 'RRHH · Registrar asistencia diaria')

@section('content')
<div class="container-fluid" style="max-width:700px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Registrar Horas de Trabajo</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">Ingrese el registro diario de asistencia y horas trabajadas en el proyecto.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.asistencia.index') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Proyecto *</label>
                    <select name="id_proyecto" class="form-select" required>
                        <option value="">— Seleccionar proyecto —</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id_proyecto }}" {{ old('id_proyecto') == $p->id_proyecto ? 'selected' : '' }}>
                                {{ $p->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                    @if($proyectos->isEmpty())
                        <small class="text-warning d-block mt-1"><i class="fas fa-exclamation-triangle me-1"></i>No hay proyectos activos.</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Trabajo *</label>
                    <input type="date" name="fecha_trabajo" class="form-control" value="{{ old('fecha_trabajo', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Obreros</label>
                    <input type="number" name="total_obreros" class="form-control" value="{{ old('total_obreros', 0) }}" min="0">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Horas Normales</label>
                    <input type="number" step="0.5" min="0" name="horas_normales" class="form-control" value="{{ old('horas_normales', 8) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Horas Extra</label>
                    <input type="number" step="0.5" min="0" name="horas_extra" class="form-control" value="{{ old('horas_extra', 0) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Incidencias, condiciones climáticas, avance...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar Registro</button>
                <a href="{{ route('operativa.asistencia.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
