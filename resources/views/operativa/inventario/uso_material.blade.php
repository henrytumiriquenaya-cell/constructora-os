@extends('layouts.app')

@section('title', 'Registrar Uso de Material')
@section('page_title', 'Uso de Material')
@section('page_subtitle', 'Gestión Operativa · Salida de materiales por proyecto')

@section('content')

{{-- ── Encabezado ─────────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-package-export me-2" style="color:var(--indigo);"></i>
            Registrar Uso de Material
        </h4>
        <small class="text-muted-dm">Registra la salida de un material del almacén central hacia un proyecto. El movimiento se actualizará automáticamente.</small>
    </div>
    <a href="{{ route('operativa.inventario.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver al Inventario
    </a>
</div>

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

<div class="row g-4">

    {{-- ── Formulario de registro ─────────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="ti ti-forms me-2" style="color:var(--indigo);"></i>
                Nueva salida de material
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('operativa.inventario.uso.store') }}" id="form-uso-material">
                    @csrf

                    {{-- Material --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Material <span class="text-danger">*</span>
                        </label>
                        <select name="id_material" id="select-material" class="form-select @error('id_material') is-invalid @enderror" required>
                            <option value="">— Seleccionar material —</option>
                            @foreach($materiales as $mat)
                                <option
                                    value="{{ $mat->id_material }}"
                                    data-disponible="{{ $mat->cantidad_disponible }}"
                                    data-unidad="{{ $mat->unidad_medida }}"
                                    {{ old('id_material') == $mat->id_material ? 'selected' : '' }}>
                                    {{ $mat->nombre }}
                                    ({{ number_format((float)$mat->cantidad_disponible, 2) }} {{ $mat->unidad_medida }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_material')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stock disponible (solo lectura, se llena con JS) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stock disponible</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="info-disponible" readonly placeholder="Selecciona un material" value="{{ old('id_material') ? '' : '' }}">
                            <span class="input-group-text" id="info-unidad">—</span>
                        </div>
                        <div id="stock-alerta" class="form-text text-danger d-none">
                            <i class="ti ti-alert-triangle me-1"></i>
                            Stock por debajo del mínimo recomendado.
                        </div>
                    </div>

                    {{-- Proyecto destino --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Proyecto destino <span class="text-danger">*</span>
                        </label>
                        <select name="id_proyecto" class="form-select @error('id_proyecto') is-invalid @enderror" required>
                            <option value="">— Seleccionar proyecto —</option>
                            @foreach($proyectos as $proyecto)
                                <option value="{{ $proyecto->id_proyecto }}" {{ old('id_proyecto') == $proyecto->id_proyecto ? 'selected' : '' }}>
                                    {{ $proyecto->nombre_proyecto }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_proyecto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cantidad usada --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Cantidad usada <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control @error('cantidad_usada') is-invalid @enderror"
                               name="cantidad_usada"
                               id="input-cantidad"
                               step="0.0001"
                               min="0.0001"
                               required
                               placeholder="0.00"
                               value="{{ old('cantidad_usada') }}">
                        @error('cantidad_usada')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Descripción de uso</label>
                        <textarea class="form-control @error('descripcion_uso') is-invalid @enderror"
                                  name="descripcion_uso"
                                  rows="2"
                                  placeholder="Ej. Uso en vaciado de losa primer piso">{{ old('descripcion_uso') }}</textarea>
                        @error('descripcion_uso')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary interactive-btn">
                            <i class="ti ti-device-floppy me-1"></i> Guardar uso
                        </button>
                        <a href="{{ route('operativa.inventario.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Historial reciente de usos ──────────────────────────────────────── --}}
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                <i class="ti ti-history me-2" style="color:var(--indigo);"></i>
                Últimos usos registrados
            </div>
            <div class="card-body p-0">
                @if($ultimosUsos->isEmpty())
                    <div class="p-4 text-center text-muted-dm small">
                        <i class="ti ti-mood-empty" style="font-size:1.5rem;"></i><br>
                        Aún no hay usos registrados.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Material</th>
                                    <th>Proyecto</th>
                                    <th class="text-end">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach($ultimosUsos as $uso)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($uso->fecha_uso)->format('d/m/Y') }}</td>
                                    <td>{{ $uso->material }}</td>
                                    <td>{{ $uso->nombre_proyecto }}</td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format((float)$uso->cantidad_usada, 2) }}
                                        <span class="text-muted-dm">{{ $uso->unidad_medida }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Enlace rápido a movimientos --}}
        <div class="mt-3 text-end">
            <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="ti ti-list me-1"></i> Ver todos los movimientos
            </a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// Al cambiar el material, actualizar el stock disponible y el máximo del input
document.getElementById('select-material')?.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const disponible = parseFloat(selected.dataset.disponible || 0);
    const unidad     = selected.dataset.unidad || '—';
    const alerta     = document.getElementById('stock-alerta');

    document.getElementById('info-disponible').value = disponible > 0
        ? disponible.toFixed(2)
        : '0.00';
    document.getElementById('info-unidad').textContent = unidad;
    document.getElementById('input-cantidad').max       = disponible;

    // Mostrar alerta si el stock ya es bajo (el semáforo viene del servidor,
    // aquí usamos 0 como señal de crítico para simplicidad)
    if (disponible <= 0) {
        alerta.classList.remove('d-none');
        alerta.innerHTML = '<i class="ti ti-alert-triangle me-1"></i> Sin stock disponible en almacén central.';
    } else {
        alerta.classList.add('d-none');
    }
});

// Disparar el evento si hay un valor preseleccionado (al volver con old())
const sel = document.getElementById('select-material');
if (sel && sel.value) sel.dispatchEvent(new Event('change'));
</script>
@endpush