@extends('layouts.app')

@section('title', 'Editar Cuota')
@section('page_title', 'Editar Cuota')
@section('page_subtitle', 'Gestión Operativa · Modificar cuota')

@section('content')
<div class="container-fluid" style="max-width:600px;">
    <h4 class="fw-light text-secondary mb-3">Registrar Pago — Cuota #{{ $cuota->id_cuota }}</h4>
    <div class="alert alert-warning py-2 small"><i class="fas fa-bolt me-1"></i>Al ingresar <strong>Fecha Pago Real</strong>, el trigger cambiará el estado automáticamente a <em>pagada_tiempo</em> o <em>pagada_tarde</em>.</div>
    <hr>
    <form action="{{ route('operativa.cuotas.update', $cuota->id_cuota) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Monto Cuota *</label>
                <input type="number" step="0.01" name="monto_cuota" class="form-control" value="{{ old('monto_cuota', $cuota->monto_cuota) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Vencimiento *</label>
                <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento', $cuota->fecha_vencimiento) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Pago Real <small class="text-muted">(activa trigger)</small></label>
                <input type="date" name="fecha_pago_real" class="form-control" value="{{ old('fecha_pago_real', $cuota->fecha_pago_real) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto Pagado</label>
                <input type="number" step="0.01" name="monto_pagado" class="form-control" value="{{ old('monto_pagado', $cuota->monto_pagado) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $cuota->observaciones) }}</textarea>
            </div>
            <div class="col-12">
                <div class="p-2 bg-light border rounded small">
                    Estado actual: <span class="badge badge-status badge-pendiente text-dark">{{ str_replace('_',' ',$cuota->estado_cuota) }}</span>
                    — se actualizará al guardar si ingresás fecha de pago.
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('operativa.cuotas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection