@extends('layouts.app')

@section('title', 'Asignar Maquinaria')
@section('page_title', 'Asignar Maquinaria')
@section('page_subtitle', 'Gestión Operativa · Nueva asignación')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Asignar Maquinaria a Proyecto</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">Complete los datos para registrar la asignación del equipo.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.maquinarias.asignaciones_store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Maquinaria *</label>
                    <select name="id_maquinaria" class="form-select" required>
                        <option value="">— Seleccionar maquinaria —</option>
                        @foreach($maquinarias as $m)
                            <option value="{{ $m->id_maquinaria }}" {{ old('id_maquinaria') == $m->id_maquinaria ? 'selected' : '' }}>
                                {{ $m->nombre }} ({{ $m->tipo }})
                            </option>
                        @endforeach
                    </select>
                    @if($maquinarias->isEmpty())
                        <small class="text-warning mt-1 d-block"><i class="fas fa-exclamation-triangle me-1"></i>No hay maquinaria disponible.</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Proyecto *</label>
                    <select name="id_proyecto" class="form-select" required>
                        <option value="">— Seleccionar proyecto —</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id_proyecto }}" {{ old('id_proyecto') == $p->id_proyecto ? 'selected' : '' }}>{{ $p->nombre_proyecto }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Horas Asignadas *</label>
                    <input type="number" step="0.5" min="0" name="horas_asignadas" class="form-control" value="{{ old('horas_asignadas') }}" required placeholder="Ej: 120">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Costo/Hora Aplicado (Bs.)</label>
                    <input type="number" step="0.01" min="0" name="costo_hora_aplicado" class="form-control" value="{{ old('costo_hora_aplicado') }}" placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Operador</label>
                    <input type="text" name="operador" class="form-control" value="{{ old('operador') }}" placeholder="Nombre del operador">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Asignar</button>
                <a href="{{ route('operativa.maquinarias.asignaciones') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
