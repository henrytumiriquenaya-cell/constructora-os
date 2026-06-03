@extends('layouts.app')

@section('title', 'Nueva Compra')
@section('page_title', 'Nueva Compra')
@section('page_subtitle', 'Gestión Operativa · Registrar compra')

@section('content')
<div class="container-fluid" style="max-width:700px;">
    <h4 class="fw-light text-secondary mb-3">Nueva Orden de Compra</h4>
    <hr>
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('operativa.compras.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Proveedor *</label>
                <select name="id_proveedor" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id_proveedor }}" {{ old('id_proveedor') == $p->id_proveedor ? 'selected':'' }}>{{ $p->razon_social }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Proyecto</label>
                <select name="id_proyecto" class="form-select">
                    <option value="">— Opcional —</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}" {{ old('id_proyecto') == $p->id_proyecto ? 'selected':'' }}>{{ $p->codigo_proyecto }} — {{ $p->nombre_proyecto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Nro. Orden *</label>
                <input type="text" name="numero_orden" class="form-control" value="{{ old('numero_orden') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Emisión *</label>
                <input type="date" name="fecha_emision" class="form-control" value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Entrega Prevista</label>
                <input type="date" name="fecha_entrega_prevista" class="form-control" value="{{ old('fecha_entrega_prevista') }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Monto Total *</label>
                <input type="number" step="0.01" name="monto_total" class="form-control" value="{{ old('monto_total', 0) }}" readonly>
                <small class="text-muted">Solo lectura. Se calcula automáticamente por trigger al registrar el detalle.</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-select" required>
                    @foreach(['borrador','emitida','recibida_parcial','recibida_total','anulada'] as $e)
                        <option value="{{ $e }}" {{ old('estado','emitida') === $e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('operativa.compras.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
 