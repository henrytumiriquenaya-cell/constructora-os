@extends('layouts.app')

@section('title', 'Inventario')
@section('page_title', 'Inventario')
@section('page_subtitle', 'Gestión Operativa · Stock de materiales')

@section('content')

{{-- ── Encabezado ─────────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-package me-2" style="color:var(--indigo);"></i>
            Inventario por Proyecto
        </h4>
        <small class="text-muted-dm">Stock actual de materiales por proyecto</small>
    </div>

    {{-- Botón de sincronización — solo visible para admin --}}
    @if(Auth::user()->hasRole('admin'))
    <form action="{{ route('operativa.inventario.recalcular') }}" method="POST"
          onsubmit="return confirm('¿Recalcular el inventario desde los movimientos reales?\nEsto corregirá cualquier desincronización.')">
        @csrf
        @if($idProyecto)
            <input type="hidden" name="id_proyecto" value="{{ $idProyecto }}">
        @endif
        <button type="submit" class="btn btn-outline-warning btn-sm">
            <i class="ti ti-refresh me-1"></i> Sincronizar inventario
        </button>
    </form>
    @endif
</div>

{{-- ── Filtro por proyecto ─────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('operativa.inventario.index') }}" class="row g-2 mb-4">
    <div class="col-md-5">
        <select class="form-select bg-dark text-white border-secondary"
                name="id_proyecto"
                onchange="this.form.submit()">
            <option value="">Todos los proyectos</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id_proyecto }}"
                    {{ (string)$idProyecto === (string)$proyecto->id_proyecto ? 'selected' : '' }}>
                    {{ $proyecto->nombre_proyecto }}
                </option>
            @endforeach
        </select>
    </div>
</form>

{{-- ── Alertas de sesión ───────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Tarjetas de inventario ──────────────────────────────────────────────── --}}
<div class="row g-3">
    @forelse($inventario as $item)
        @php
            $semaforo   = $item->semaforo ?? 'verde';
            $borderColor = match($semaforo) {
                'rojo'     => 'danger',
                'amarillo' => 'warning',
                default    => 'success',
            };
            $badgeColor = match($semaforo) {
                'rojo'     => 'danger',
                'amarillo' => 'warning text-dark',
                default    => 'success',
            };
            $badgeText = match($semaforo) {
                'rojo'     => 'CRÍTICO',
                'amarillo' => 'BAJO',
                default    => 'OK',
            };
        @endphp

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 shadow-sm interactive-card border-{{ $borderColor }}">
                <div class="card-body">

                    {{-- Nombre del material y badge de estado --}}
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 fw-semibold" style="color:var(--text-primary);">
                                {{ $item->material ?? 'Material' }}
                            </h6>
                            <div class="small text-muted-dm">
                                {{ $item->nombre_proyecto ?? 'Proyecto N/D' }}
                            </div>
                        </div>
                        <span class="badge bg-{{ $badgeColor }}">{{ $badgeText }}</span>
                    </div>

                    <hr class="my-2 border-secondary">

                    {{-- Datos de stock --}}
                    <div class="small">
                        <div>
                            <strong>Disponible:</strong>
                            {{ number_format((float)($item->cantidad_disponible ?? 0), 2) }}
                            {{ $item->unidad_medida ?? '' }}
                        </div>
                        <div>
                            <strong>Reservada:</strong>
                            {{ number_format((float)($item->cantidad_reservada ?? 0), 2) }}
                        </div>
                        <div>
                            <strong>Mínimo:</strong>
                            {{ number_format((float)($item->stock_minimo ?? 0), 2) }}
                        </div>
                    </div>

                    {{-- Pie de tarjeta: fecha + botón --}}
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted-dm">
                            Actualizado:
                            @if($item->fecha_ultima_actualizacion)
                                {{ \Carbon\Carbon::parse($item->fecha_ultima_actualizacion)->format('d/m/Y H:i') }}
                            @else
                                N/D
                            @endif
                        </small>

                        <button
                            class="btn btn-sm btn-outline-primary interactive-btn"
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#usoMaterialModal"
                            data-id-proyecto="{{ $item->id_proyecto ?? '' }}"
                            data-id-material="{{ $item->id_material ?? '' }}"
                            data-material="{{ $item->material ?? 'Material' }}"
                            data-disponible="{{ number_format((float)($item->cantidad_disponible ?? 0), 2) }}"
                            data-unidad="{{ $item->unidad_medida ?? '' }}">
                            Registrar Uso
                        </button>
                    </div>

                </div>
            </div>
        </div>

    @empty
        <div class="col-12">
            <div class="alert alert-secondary d-flex align-items-center gap-2">
                <i class="ti ti-mood-empty" style="font-size:1.5rem;"></i>
                No hay datos de inventario para el filtro seleccionado.
            </div>
        </div>
    @endforelse
</div>

<div class="modal fade" id="usoMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('operativa.inventario.uso.store') }}"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-package-export me-2" style="color:var(--indigo);"></i>
                    Registrar Uso de Material
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Campos ocultos que se llenan con JS al abrir el modal --}}
                <input type="hidden" name="id_proyecto" id="modal-id-proyecto">
                <input type="hidden" name="id_material" id="modal-id-material">

                {{-- Info del material (solo lectura) --}}
                <div class="alert alert-dark p-2 mb-3 small" id="modal-material-info">
                    <strong id="modal-material-label">—</strong><br>
                    Stock disponible: <span id="modal-disponible" class="fw-semibold text-success">—</span>
                </div>

                {{-- Cantidad --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Cantidad usada <span class="text-danger">*</span>
                    </label>
                    <input type="number"
                           class="form-control"
                           name="cantidad_usada"
                           id="modal-cantidad"
                           step="0.0001"
                           min="0.0001"
                           required
                           placeholder="0.00">
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="form-label fw-semibold">Descripción de uso</label>
                    <textarea class="form-control"
                              name="descripcion_uso"
                              rows="2"
                              placeholder="Ej. Uso en vaciado de losa primer piso"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary interactive-btn">
                    <i class="ti ti-device-floppy me-1"></i> Guardar uso
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Poblar el modal con los datos del botón que lo abrió
document.getElementById('usoMaterialModal')?.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    document.getElementById('modal-id-proyecto').value   = btn?.dataset.idProyecto  || '';
    document.getElementById('modal-id-material').value   = btn?.dataset.idMaterial  || '';
    document.getElementById('modal-material-label').textContent =
        'Material: ' + (btn?.dataset.material || 'N/D');

    const disponible = btn?.dataset.disponible || '0';
    const unidad     = btn?.dataset.unidad     || '';
    document.getElementById('modal-disponible').textContent = disponible + ' ' + unidad;

    // Poner el máximo en el input de cantidad
    document.getElementById('modal-cantidad').max = disponible;
    document.getElementById('modal-cantidad').value = '';
});
</script>
@endpush