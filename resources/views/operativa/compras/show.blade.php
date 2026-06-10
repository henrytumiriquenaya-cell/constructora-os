@extends('layouts.app')

@section('title', 'Detalle de Compra')
@section('page_title', 'Orden de Compra')
@section('page_subtitle', 'Gestión Operativa · Detalle de orden')

@section('content')
<div class="container-fluid" style="max-width:960px;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Orden #{{ $compra->numero_orden }}</h5>
            <p style="color:var(--text-secondary); font-size:0.85rem; margin:0;">
                Proveedor: <strong style="color:var(--indigo);">{{ $compra->proveedor->razon_social ?? '—' }}</strong>
                · Proyecto: {{ $compra->proyecto->nombre_proyecto ?? 'Sin proyecto' }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('operativa.compras.detalle', $compra->id_compra) }}" class="btn btn-primary btn-sm">
                <i class="ti ti-list-details me-1"></i> Ver Detalle / Agregar Ítems
            </a>
            <a href="{{ route('operativa.compras.index') }}" class="btn btn-secondary btn-sm">Volver</a>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card accent-indigo">
                <div class="stat-label">Estado</div>
                <div class="stat-value" style="font-size:1.1rem;">
                    <span class="badge badge-status
                        @if($compra->estado === 'recibida_total') badge-concluido
                        @elseif($compra->estado === 'emitida') badge-en_ejecucion
                        @elseif($compra->estado === 'anulada') badge-cancelado
                        @else badge-pendiente @endif">
                        {{ str_replace('_',' ', ucfirst($compra->estado)) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card accent-green">
                <div class="stat-label">Monto Total</div>
                <div class="stat-value">Bs. {{ number_format($compra->monto_total, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card accent-blue">
                <div class="stat-label">Fecha Emisión</div>
                <div class="stat-value" style="font-size:1.05rem;">{{ \Carbon\Carbon::parse($compra->fecha_emision)->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card accent-yellow">
                <div class="stat-label">Entrega Prevista</div>
                <div class="stat-value" style="font-size:1.05rem;">{{ $compra->fecha_entrega_prevista ? \Carbon\Carbon::parse($compra->fecha_entrega_prevista)->format('d/m/Y') : '—' }}</div>
            </div>
        </div>
    </div>

    {{-- Detalles de ítems --}}
    <div class="page-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-semibold mb-0" style="color:var(--text-primary);">Ítems de la Orden</h6>
            <a href="{{ route('operativa.compras.detalle', $compra->id_compra) }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus me-1"></i> Gestionar Ítems
            </a>
        </div>

        @if($compra->detalles->isEmpty())
            <div class="text-center py-5" style="color:var(--text-muted);">
                <i class="ti ti-box" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.4;"></i>
                <p>Sin ítems registrados. <a href="{{ route('operativa.compras.detalle', $compra->id_compra) }}" style="color:var(--indigo);">Agregar ítem</a></p>
            </div>
        @else
            <div class="table-wrapper">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-head-premium">
                            <tr>
                                <th>Material</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Recibido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compra->detalles as $d)
                            <tr>
                                <td class="fw-medium">{{ $d->material->nombre ?? '—' }}</td>
                                <td class="text-end">{{ number_format($d->cantidad, 2) }}</td>
                                <td class="text-end">Bs. {{ number_format($d->precio_unitario, 2) }}</td>
                                <td class="text-end fw-semibold" style="color:var(--indigo);">Bs. {{ number_format($d->subtotal, 2) }}</td>
                                <td class="text-end">
                                    @if($d->cantidad_recibida >= $d->cantidad)
                                        <span class="badge badge-status badge-concluido">Completo</span>
                                    @elseif($d->cantidad_recibida > 0)
                                        <span class="badge badge-status badge-pendiente">Parcial ({{ number_format($d->cantidad_recibida, 2) }})</span>
                                    @else
                                        <span class="badge badge-status">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold" style="color:var(--text-secondary);">TOTAL</td>
                                <td class="text-end fw-bold" style="color:var(--indigo); font-size:1rem;">Bs. {{ number_format($compra->monto_total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @endif
    </div>

    @if($compra->observaciones)
    <div class="page-card mt-3">
        <h6 class="fw-semibold mb-2" style="color:var(--text-primary);">Observaciones</h6>
        <p style="color:var(--text-secondary); font-size:0.9rem;">{{ $compra->observaciones }}</p>
    </div>
    @endif

</div>
@endsection
