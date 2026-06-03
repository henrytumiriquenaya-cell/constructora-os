@extends('layouts.app')

@section('title', 'Detalle Proyecto')
@section('page_title', 'Detalle Proyecto')
@section('page_subtitle', 'Gestión Operativa · Información completa')

@section('content')
<div class="container-fluid" style="max-width:800px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Detalles del Proyecto</h4>
        <div>
            <a href="{{ route('operativa.proyectos.edit', $proyecto->id_proyecto) }}" class="btn btn-edit btn-sm"><i class="fas fa-pen me-1"></i> Editar</a>
            <a href="{{ route('operativa.proyectos.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Volver</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3"><i class="fas fa-building me-2"></i> {{ $proyecto->nombre_proyecto }}</h5>
            
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Código:</div>
                <div class="col-md-8"><code>{{ $proyecto->codigo_proyecto ?? 'N/A' }}</code></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Contrato Asociado:</div>
                <div class="col-md-8">
                    @if($proyecto->contrato)
                        <code>{{ $proyecto->contrato->numero_contrato }}</code> 
                        (Cliente: {{ $proyecto->contrato->cliente->nombre_razon ?? 'Desconocido' }})
                    @else
                        —
                    @endif
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Estado:</div>
                <div class="col-md-8">
                    @php $badge = match($proyecto->estado ?? '') { 'en_ejecucion' => 'primary', 'concluido' => 'success', 'cancelado' => 'danger', 'pausado' => 'warning', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ',$proyecto->estado ?? '')) }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Avance:</div>
                <div class="col-md-8">
                    <div class="progress" style="height:20px; max-width:200px">
                        <div class="progress-bar" style="width:{{ $proyecto->porcentaje_avance ?? 0 }}%">{{ $proyecto->porcentaje_avance ?? 0 }}%</div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Ubicación:</div>
                <div class="col-md-8">{{ $proyecto->ubicacion ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Tipo de Obra:</div>
                <div class="col-md-8">{{ $proyecto->tipo_obra ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Superficie (m²):</div>
                <div class="col-md-8">{{ $proyecto->superficie_m2 ? number_format($proyecto->superficie_m2, 2) : '—' }}</div>
            </div>
            <hr>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Fecha Inicio Real:</div>
                <div class="col-md-8">{{ $proyecto->fecha_inicio_real ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Fecha Fin Programada:</div>
                <div class="col-md-8">{{ $proyecto->fecha_fin_programada ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Fecha Fin Real:</div>
                <div class="col-md-8">{{ $proyecto->fecha_fin_real ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
