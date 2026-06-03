@extends('layouts.app')

@section('title', 'Nueva Cotización')
@section('page_title', 'Nueva Cotización')
@section('page_subtitle', 'Gestión Operativa · Registrar cotización')

@section('content')
<div class="container-fluid" style="max-width:700px;">
    <h4 class="fw-light text-secondary mb-3">Nueva Cotización</h4>
    <div class="alert alert-info py-2 small"><i class="fas fa-bolt me-1"></i>El total lo calcula el trigger — no es necesario ingresarlo.</div>
    <hr>
    @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('operativa.cotizaciones.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Proyecto *</label>
                <select name="id_proyecto" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}" {{ old('id_proyecto') == $p->id_proyecto ? 'selected':'' }}>{{ $p->codigo_proyecto }} — {{ $p->nombre_proyecto }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Versión *</label>
                <input type="number" name="version" class="form-control" value="{{ old('version',1) }}" min="1" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Elaborado por</label>
                <select name="id_empleado" class="form-select">
                    <option value="">— Opcional —</option>
                    @foreach($empleados as $e)
                        <option value="{{ $e->id_empleado }}" {{ old('id_empleado') == $e->id_empleado ? 'selected':'' }}>{{ $e->nombres }} {{ $e->apellidos }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Elaboración *</label>
                <input type="date" name="fecha_elaboracion" class="form-control" value="{{ old('fecha_elaboracion', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto Materiales (Bs.) *</label>
                <input type="number" step="0.01" min="0" name="monto_plan_materiales" class="form-control monto" value="{{ old('monto_plan_materiales',0) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto Mano de Obra (Bs.) *</label>
                <input type="number" step="0.01" min="0" name="monto_plan_mano_obra" class="form-control monto" value="{{ old('monto_plan_mano_obra',0) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Monto Maquinaria (Bs.) *</label>
                <input type="number" step="0.01" min="0" name="monto_plan_maquinaria" class="form-control monto" value="{{ old('monto_plan_maquinaria',0) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gastos Administrativos (Bs.) *</label>
                <input type="number" step="0.01" min="0" name="monto_plan_gastos_adm" class="form-control monto" value="{{ old('monto_plan_gastos_adm',0) }}" required>
            </div>
            <div class="col-12">
                <div class="p-2 bg-light border rounded small">Total referencial (antes del trigger): <strong id="ref_total">Bs. 0.00</strong></div>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-select" required>
                    @foreach(['borrador','aprobado','vigente','cerrado'] as $e)
                        <option value="{{ $e }}" {{ old('estado','borrador') === $e ? 'selected':'' }}>{{ ucfirst($e) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('operativa.cotizaciones.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.monto').forEach(el => el.addEventListener('input', () => {
    const t = [...document.querySelectorAll('.monto')].reduce((s,e)=>s+(parseFloat(e.value)||0),0);
    document.getElementById('ref_total').textContent = 'Bs. '+t.toLocaleString('es-BO',{minimumFractionDigits:2});
}));
</script>
@endpush