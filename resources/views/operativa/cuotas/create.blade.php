@extends('layouts.app')

@section('title', 'Nueva Cuota de Pago')
@section('page_title', 'Nueva Cuota')
@section('page_subtitle', 'Gestión Operativa · Registrar cuota de pago')

@section('content')
<div class="container-fluid" style="max-width:700px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Nueva Cuota de Pago</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">Registre una cuota asociada a un contrato existente.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.cuotas.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Contrato *</label>
                    <select name="id_contrato" class="form-select" required>
                        <option value="">— Seleccionar contrato —</option>
                        @foreach($contratos as $c)
                            <option value="{{ $c->id_contrato }}" {{ old('id_contrato') == $c->id_contrato ? 'selected' : '' }}>
                                {{ $c->numero_contrato }} — {{ $c->cliente->nombre_razon ?? 'Sin cliente' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Número de Cuota *</label>
                    <input type="number" name="numero_cuota" class="form-control" value="{{ old('numero_cuota', 1) }}" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monto (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_cuota" class="form-control" value="{{ old('monto_cuota') }}" required placeholder="0.00">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha de Vencimiento *</label>
                    <input type="date" name="fecha_vencimiento" class="form-control" value="{{ old('fecha_vencimiento') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado *</label>
                    <select name="estado_cuota" class="form-select" required>
                        @foreach(['pendiente','pagada_tiempo','pagada_tarde','vencida'] as $est)
                            <option value="{{ $est }}" {{ old('estado_cuota','pendiente') === $est ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($est)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar</button>
                <a href="{{ route('operativa.cuotas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
