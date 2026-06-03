@extends('layouts.app')

@section('title', 'Detalle Contrato')
@section('page_title', 'Detalle Contrato')
@section('page_subtitle', 'Gestión Operativa · Información del contrato')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-light text-secondary mb-0">Contrato: <strong>{{ $contrato->numero_contrato }}</strong></h4>
        <div class="d-flex gap-2">
            <a href="{{ route('operativa.contratos.edit', $contrato->id_contrato) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pencil-alt me-1"></i>Editar</a>
            <a href="{{ route('operativa.contratos.index') }}" class="btn btn-sm btn-outline-dark">Volver</a>
        </div>
    </div>
    <hr>
    <div class="row g-3">
        <div class="col-md-5">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted fw-bold mb-3">Datos del Contrato</h6>
                <table class="table table-sm table-borderless mb-0 small">
                    <tr><th>Cliente</th><td>{{ $contrato->cliente->nombre_razon ?? '—' }}</td></tr>
                    <tr><th>Monto Total</th><td>{{ number_format($contrato->monto_total,2) }} {{ $contrato->moneda }}</td></tr>
                    <tr><th>Tipo</th><td>{{ str_replace('_',' ',$contrato->tipo_contrato) }}</td></tr>
                    <tr><th>Estado</th><td><span class="badge badge-status badge-activo">{{ str_replace('_',' ',$contrato->estado) }}</span></td></tr>
                    <tr><th>Fecha Firma</th><td>{{ $contrato->fecha_firma }}</td></tr>
                    <tr><th>Inicio</th><td>{{ $contrato->fecha_inicio }}</td></tr>
                    <tr><th>Fin Previsto</th><td>{{ $contrato->fecha_fin_prevista }}</td></tr>
                    <tr><th>Fin Real</th><td>{{ $contrato->fecha_fin_real ?? '—' }}</td></tr>
                    <tr><th>Proyecto</th><td>{{ $contrato->proyecto->nombre_proyecto ?? 'Sin asignar' }}</td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="text-muted fw-bold mb-0">Cuotas de Pago</h6>
                    <a href="{{ route('operativa.cuotas.create') }}" class="btn btn-sm btn-outline-primary">+ Nueva Cuota</a>
                </div>
                <table class="table table-sm table-hover mb-0 small">
                    <thead class="table-light"><tr><th>Nro</th><th>Monto</th><th>Vencimiento</th><th>Estado</th><th>Pagado</th></tr></thead>
                    <tbody>
                    @forelse($contrato->cuotas as $cq)
                        <tr>
                            <td>{{ $cq->numero_cuota }}</td>
                            <td>{{ number_format($cq->monto_cuota,2) }}</td>
                            <td>{{ $cq->fecha_vencimiento }}</td>
                            <td>
                                @php $cs=['pendiente'=>'warning','pagada_tiempo'=>'success','pagada_tarde'=>'info','vencida'=>'danger'][$cq->estado_cuota] ?? 'secondary' @endphp
                                <span class="badge bg-{{ $cs }}">{{ str_replace('_',' ',$cq->estado_cuota) }}</span>
                            </td>
                            <td>{{ $cq->monto_pagado ? number_format($cq->monto_pagado,2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted text-center">Sin cuotas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection