@extends('layouts.app')

@section('title', 'Editar Cotización')
@section('page_title', 'Editar Cotización')
@section('page_subtitle', 'Gestión Operativa · Actualizar presupuesto')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Editar Cotización #{{ $cotizacion->id_presupuesto }}</h5>
        <p class="mb-3" style="color:var(--text-secondary); font-size:0.85rem;">
            Proyecto: <strong style="color:var(--indigo);">{{ $cotizacion->proyecto->nombre_proyecto ?? '—' }}</strong> · Versión: {{ $cotizacion->version }}
        </p>
        <div class="alert alert-info py-2 small mb-4"><i class="fas fa-bolt me-1"></i> El total es recalculado automáticamente por la base de datos al guardar.</div>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.cotizaciones.update', $cotizacion->id_presupuesto) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Monto Materiales (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_plan_materiales" class="form-control monto"
                        value="{{ old('monto_plan_materiales', $cotizacion->monto_plan_materiales) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monto Mano de Obra (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_plan_mano_obra" class="form-control monto"
                        value="{{ old('monto_plan_mano_obra', $cotizacion->monto_plan_mano_obra) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monto Maquinaria (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_plan_maquinaria" class="form-control monto"
                        value="{{ old('monto_plan_maquinaria', $cotizacion->monto_plan_maquinaria) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gastos Administrativos (Bs.) *</label>
                    <input type="number" step="0.01" min="0" name="monto_plan_gastos_adm" class="form-control monto"
                        value="{{ old('monto_plan_gastos_adm', $cotizacion->monto_plan_gastos_adm) }}" required>
                </div>
                <div class="col-12">
                    <div class="p-3 rounded" style="background:var(--page-bg); border:1px solid var(--card-border);">
                        <span style="color:var(--text-secondary); font-size:0.85rem;">Total referencial (antes del trigger):</span>
                        <strong id="ref_total" style="color:var(--indigo); font-size:1.05rem; display:block; margin-top:2px;">
                            Bs. {{ number_format($cotizacion->monto_plan_materiales + $cotizacion->monto_plan_mano_obra + $cotizacion->monto_plan_maquinaria + $cotizacion->monto_plan_gastos_adm, 2) }}
                        </strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['borrador','aprobado','vigente','cerrado'] as $est)
                            <option value="{{ $est }}" {{ old('estado', $cotizacion->estado) === $est ? 'selected' : '' }}>{{ ucfirst($est) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.monto').forEach(el => el.addEventListener('input', () => {
    const t = [...document.querySelectorAll('.monto')].reduce((s,e) => s+(parseFloat(e.value)||0), 0);
    document.getElementById('ref_total').textContent = 'Bs. '+t.toLocaleString('es-BO',{minimumFractionDigits:2});
}));
</script>
@endpush
