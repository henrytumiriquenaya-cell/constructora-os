@extends('layouts.app')

@section('title', 'Editar Asignación')
@section('page_title', 'Editar Asignación')
@section('page_subtitle', 'Gestión Operativa · Actualizar asignación de maquinaria')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Editar Asignación #{{ $asignacion->id_asig_maq }}</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">
            Maquinaria: <strong style="color:var(--indigo);">{{ $asignacion->maquinaria->nombre ?? '—' }}</strong> ·
            Proyecto: <strong style="color:var(--indigo);">{{ $asignacion->proyecto->nombre_proyecto ?? '—' }}</strong>
        </p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.maquinarias.asignaciones_update', $asignacion->id_asig_maq) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Maquinaria</label>
                    <input type="text" class="form-control" value="{{ $asignacion->maquinaria->nombre ?? '—' }}" disabled style="opacity:0.6;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Proyecto</label>
                    <input type="text" class="form-control" value="{{ $asignacion->proyecto->nombre_proyecto ?? '—' }}" disabled style="opacity:0.6;">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $asignacion->fecha_fin ? \Carbon\Carbon::parse($asignacion->fecha_fin)->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Horas Usadas</label>
                    <input type="number" step="0.5" min="0" name="horas_usadas" class="form-control" value="{{ old('horas_usadas', $asignacion->horas_usadas) }}" placeholder="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Costo/Hora Aplicado (Bs.)</label>
                    <input type="number" step="0.01" min="0" name="costo_hora_aplicado" class="form-control" value="{{ old('costo_hora_aplicado', $asignacion->costo_hora_aplicado) }}" placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Operador</label>
                    <input type="text" name="operador" class="form-control" value="{{ old('operador', $asignacion->operador) }}" placeholder="Nombre del operador">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $asignacion->observaciones) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.maquinarias.asignaciones') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
