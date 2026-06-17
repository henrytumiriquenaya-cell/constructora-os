@extends('layouts.app')

@section('title', 'Registrar Pago')
@section('page_title', 'Registrar Pago')
@section('page_subtitle', 'Gestión Operativa · Registrar pago de cuota')

@section('content')
<div class="container-fluid" style="max-width:600px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">
            Registrar Pago — Cuota #{{ $cuota->id_cuota }}
        </h5>

        <div class="p-3 mb-4 rounded" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);">
            <div class="row small">
                <div class="col-6 mb-2"><span class="text-muted">Proyecto:</span> <strong>{{ $cuota->nombre_proyecto ?? '—' }}</strong></div>
                <div class="col-6 mb-2"><span class="text-muted">Contrato:</span> <code>{{ $cuota->numero_contrato ?? '—' }}</code></div>
                <div class="col-6 mb-2"><span class="text-muted">Cliente:</span> {{ $cuota->nombre_razon ?? '—' }}</div>
                <div class="col-6 mb-2"><span class="text-muted">N° Cuota:</span> {{ $cuota->numero_cuota }}</div>
                <div class="col-6"><span class="text-muted">Monto cuota:</span> <strong>Bs {{ number_format($cuota->monto_cuota, 2) }}</strong></div>
                <div class="col-6"><span class="text-muted">Vencimiento:</span> {{ $cuota->fecha_vencimiento }}</div>
            </div>
        </div>

        <div class="alert alert-warning py-2 small">
            <i class="fas fa-bolt me-1"></i>
            Al guardar, el trigger evaluará automáticamente si corresponde <em>pagada_tiempo</em> o <em>pagada_tarde</em> según la fecha de pago ingresada.
        </div>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.cuotas.registrar_pago', $cuota->id_cuota) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Fecha de Pago Real *</label>
                    <input type="date" name="fecha_pago_real" class="form-control"
                           value="{{ old('fecha_pago_real', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monto Pagado (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_pagado" class="form-control"
                           value="{{ old('monto_pagado', $cuota->monto_cuota) }}" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-hand-holding-dollar me-1"></i> Registrar Pago
                </button>
                <a href="{{ route('operativa.cuotas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection