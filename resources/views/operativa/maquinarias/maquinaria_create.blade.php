@extends('layouts.app')

@section('title', 'Nueva Maquinaria')
@section('page_title', 'Nueva Maquinaria')
@section('page_subtitle', 'Catálogo · Registrar maquinaria')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Registrar Maquinaria</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">Complete los datos del equipo o maquinaria para el catálogo.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('operativa.maquinarias.catalogo_store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código *</label>
                    <input type="text" name="codigo_inventario" class="form-control" value="{{ old('codigo_inventario') }}" required placeholder="MAQ-001">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required placeholder="Ej: Excavadora Caterpillar">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <input type="text" name="tipo" class="form-control" value="{{ old('tipo') }}" required placeholder="Ej: Excavadora, Grúa, Bulldozer">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca</label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" placeholder="Ej: Caterpillar, Komatsu">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Modelo</label>
                    <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" placeholder="Ej: CAT 320">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Año Fabricación</label>
                    <input type="number" name="anio_fabricacion" class="form-control" value="{{ old('anio_fabricacion') }}" min="1900" max="{{ date('Y') + 1 }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Número de Serie</label>
                    <input type="text" name="numero_serie" class="form-control" value="{{ old('numero_serie') }}" placeholder="SN-XXXXXXX">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Capacidad</label>
                    <input type="number" step="0.01" name="capacidad" class="form-control" value="{{ old('capacidad') }}" placeholder="Ej: 20">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Unidad Capacidad</label>
                    <input type="text" name="unidad_capacidad" class="form-control" value="{{ old('unidad_capacidad') }}" placeholder="Ej: Tn, m3, HP">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado_actual" class="form-select" required>
                        @foreach(['disponible','en_uso','en_mantenimiento','fuera_servicio'] as $est)
                            <option value="{{ $est }}" {{ old('estado','disponible') === $est ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($est)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Costo/Hora (Bs.)</label>
                    <input type="number" step="0.01" min="0" name="costo_hora" class="form-control" value="{{ old('costo_hora') }}" placeholder="0.00">
                </div>
                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2" placeholder="Notas o comentarios adicionales...">{{ old('observaciones') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar</button>
                <a href="{{ route('operativa.maquinarias.catalogo') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
