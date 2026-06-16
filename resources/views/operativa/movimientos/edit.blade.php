@extends('layouts.app')

@section('title', 'Editar Movimiento')
@section('page_title', 'Editar Movimiento')
@section('page_subtitle', 'Gestión Operativa · Modificar movimiento de material')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-edit me-2" style="color:var(--indigo);"></i>
            Editar Movimiento #{{ $movimiento->id_movimiento }}
        </h4>
        <small class="text-muted-dm">
            @if($esAutomatico)
                <span class="badge bg-warning text-dark me-1">
                    <i class="ti ti-lock me-1"></i>Movimiento automático
                </span>
                Solo puedes editar la descripción. La cantidad la controla un trigger de la BD.
            @else
                Movimiento manual — puedes editar todos los campos.
            @endif
        </small>
    </div>
    <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver
    </a>
</div>

{{-- Alerta explicativa para movimientos automáticos --}}
@if($esAutomatico)
<div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">
    <i class="ti ti-info-circle fs-4 mt-1"></i>
    <div>
        <strong>¿Por qué no puedo cambiar la cantidad?</strong><br>
        Este movimiento fue generado automáticamente por un trigger de la base de datos
        (proviene de un registro en <code>uso_material</code> o <code>detalle_compra</code>).
        Si necesitas corregir la cantidad, debes editar el registro original de uso de material
        en la sección <strong>Inventario → Registrar Uso</strong>, y el inventario se ajustará solo.
    </div>
</div>
@endif

<div class="page-card">
    <form action="{{ route('operativa.movimientos.update', $movimiento->id_movimiento) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-section-title">
            <i class="ti ti-info-circle me-2"></i>Datos del Movimiento
        </div>

        <div class="row g-3 mt-1">

            {{-- Material --}}
            <div class="col-md-6">
                <label class="form-label">Material <span class="text-danger">*</span></label>
                @if($esAutomatico)
                    {{-- Solo lectura para movimientos automáticos --}}
                    <input type="hidden" name="id_material" value="{{ $movimiento->id_material }}">
                    <input type="text" class="form-control bg-dark text-muted-dm" readonly
                           value="{{ $movimiento->material?->nombre ?? '—' }}">
                @else
                    <select name="id_material" class="form-select @error('id_material') is-invalid @enderror" required>
                        <option value="">— Seleccione un material —</option>
                        @foreach($materiales as $mat)
                            <option value="{{ $mat->id_material }}"
                                    {{ old('id_material', $movimiento->id_material) == $mat->id_material ? 'selected' : '' }}>
                                {{ $mat->nombre }}{{ $mat->unidad_medida ? ' (' . $mat->unidad_medida . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @endif
            </div>

            {{-- Cantidad --}}
            <div class="col-md-6">
                <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                @if($esAutomatico)
                    <input type="hidden" name="cantidad" value="{{ $movimiento->cantidad }}">
                    <div class="input-group">
                        <input type="text" class="form-control bg-dark text-muted-dm" readonly
                               value="{{ number_format($movimiento->cantidad, 4) }}">
                        <span class="input-group-text bg-dark text-warning border-secondary">
                            <i class="ti ti-lock" title="Bloqueado — movimiento automático"></i>
                        </span>
                    </div>
                    <div class="form-text text-warning">Controlado por trigger. No editable.</div>
                @else
                    <input type="number" name="cantidad"
                           class="form-control @error('cantidad') is-invalid @enderror"
                           value="{{ old('cantidad', $movimiento->cantidad) }}"
                           step="0.0001" min="0.0001" required>
                    @error('cantidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @endif
            </div>

            {{-- Tipo --}}
            <div class="col-md-6">
                <label class="form-label">Tipo de Movimiento <span class="text-danger">*</span></label>
                @if($esAutomatico)
                    <input type="hidden" name="tipo" value="{{ $movimiento->tipo }}">
                    <input type="text" class="form-control bg-dark text-muted-dm" readonly
                           value="{{ strtoupper($movimiento->tipo) === 'ENTRADA' ? '📥 Entrada' : '📤 Salida' }}">
                @else
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        <option value="ENTRADA" {{ old('tipo', strtoupper($movimiento->tipo)) === 'ENTRADA' ? 'selected' : '' }}>
                            📥 Entrada (Ingreso al stock)
                        </option>
                        <option value="SALIDA" {{ old('tipo', strtoupper($movimiento->tipo)) === 'SALIDA' ? 'selected' : '' }}>
                            📤 Salida (Consumo / Uso)
                        </option>
                    </select>
                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @endif
            </div>

            {{-- Proyecto --}}
            <div class="col-md-6">
                <label class="form-label">Destino — Proyecto</label>
                @if($esAutomatico)
                    <input type="hidden" name="id_proyecto" value="{{ $movimiento->id_proyecto }}">
                    <input type="text" class="form-control bg-dark text-muted-dm" readonly
                           value="{{ $movimiento->proyecto?->nombre_proyecto ?? 'Sin proyecto' }}">
                @else
                    <select name="id_proyecto" class="form-select @error('id_proyecto') is-invalid @enderror">
                        <option value="">Sin proyecto asignado</option>
                        @foreach($proyectos as $p)
                            <option value="{{ $p->id_proyecto }}"
                                    {{ old('id_proyecto', $movimiento->id_proyecto) == $p->id_proyecto ? 'selected' : '' }}>
                                {{ $p->nombre_proyecto }}{{ $p->codigo_proyecto ? ' — ' . $p->codigo_proyecto : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_proyecto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @endif
            </div>

            {{-- Descripción — siempre editable --}}
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          rows="3">{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if($esAutomatico)
                    <div class="form-text">
                        <i class="ti ti-pencil me-1"></i>Este es el único campo que puedes modificar en un movimiento automático.
                    </div>
                @endif
            </div>

        </div>

        <hr class="form-divider">

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary interactive-btn">
                <i class="ti ti-device-floppy me-1"></i>
                {{ $esAutomatico ? 'Guardar Descripción' : 'Guardar Cambios' }}
            </button>
        </div>
    </form>
</div>

@endsection