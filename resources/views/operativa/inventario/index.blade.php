@extends('layouts.app')

@section('title', 'Inventario')
@section('page_title', 'Inventario')
@section('page_subtitle', 'Gestión Operativa · Stock de materiales')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Inventario por Proyecto</h4>
    </div>

    <form method="GET" action="{{ route('operativa.inventario.index') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <select class="form-select" name="id_proyecto" onchange="this.form.submit()">
                <option value="">Todos los proyectos</option>
                @foreach($proyectos as $proyecto)
                    <option value="{{ $proyecto->id_proyecto }}" {{ (string) $idProyecto === (string) $proyecto->id_proyecto ? 'selected' : '' }}>
                        {{ $proyecto->nombre_proyecto }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="row g-3">
        @forelse($inventario as $item)
            @php
                $isLow = ($item['semaforo'] ?? 'verde') === 'rojo';
            @endphp
            <div class="col-xl-4 col-lg-6">
                <div class="card h-100 shadow-sm interactive-card border-{{ $isLow ? 'danger' : 'success' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-semibold">{{ $item['nombre_material'] ?? $item['material'] ?? 'Material' }}</h6>
                                <div class="small text-muted">{{ $item['nombre_proyecto'] ?? 'Proyecto N/D' }}</div>
                            </div>
                            <span class="badge bg-{{ $isLow ? 'danger' : 'success' }}">{{ $isLow ? 'STOCK BAJO' : 'OK' }}</span>
                        </div>
                        <hr>
                        <div class="small">
                            <div><strong>Disponible:</strong> {{ $item['cantidad_disponible'] ?? 0 }} {{ $item['unidad_medida'] ?? '' }}</div>
                            <div><strong>Reservada:</strong> {{ $item['cantidad_reservada'] ?? 0 }}</div>
                            <div><strong>Mínimo:</strong> {{ $item['stock_minimo'] ?? 0 }}</div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted">Actualizado: {{ $item['ultima_actualizacion'] ?? 'N/D' }}</small>
                            <button
                                class="btn btn-sm btn-outline-primary interactive-btn"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#usoMaterialModal"
                                data-id-proyecto="{{ $item['id_proyecto'] ?? '' }}"
                                data-id-material="{{ $item['id_material'] ?? '' }}"
                                data-material="{{ $item['nombre_material'] ?? $item['material'] ?? 'Material' }}">
                                Registrar Uso
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">No hay datos de inventario para el filtro seleccionado.</div>
            </div>
        @endforelse
    </div>
</div>

<div class="modal fade" id="usoMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('operativa.inventario.uso.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Registrar Uso de Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_proyecto" id="modal-id-proyecto">
                <input type="hidden" name="id_material" id="modal-id-material">

                <div class="mb-2 small text-muted" id="modal-material-label"></div>
                <div class="mb-3">
                    <label class="form-label">Cantidad usada</label>
                    <input type="number" class="form-control" name="cantidad_usada" step="0.0001" min="0.0001" required>
                </div>
                <div>
                    <label class="form-label">Descripción de uso</label>
                    <textarea class="form-control" name="descripcion_uso" rows="2" placeholder="Ej. Uso en vaciado de losa"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary interactive-btn">Guardar uso</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('usoMaterialModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    document.getElementById('modal-id-proyecto').value = button?.getAttribute('data-id-proyecto') || '';
    document.getElementById('modal-id-material').value = button?.getAttribute('data-id-material') || '';
    document.getElementById('modal-material-label').textContent = 'Material: ' + (button?.getAttribute('data-material') || 'N/D');
});
</script>
@endsection
