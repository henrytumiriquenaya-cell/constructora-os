@extends('layouts.app')

@section('title', 'Editar Movimiento')
@section('page_title', 'Editar Movimiento')
@section('page_subtitle', 'Gestión Operativa · Modificar movimiento de material')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-edit me-2" style="color:var(--indigo);"></i>
            Editar Movimiento
        </h4>
        <small class="text-muted-dm">Modificar movimiento #{{ $movimiento->id_movimiento }}</small>
    </div>
    <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver a la lista
    </a>
</div>

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
                <label class="form-label" for="id_material">
                    Material <span class="text-danger">*</span>
                </label>
                <select id="id_material"
                        name="id_material"
                        class="form-select @error('id_material') is-invalid @enderror"
                        required>
                    <option value="">— Seleccione un material —</option>
                    @foreach($materiales as $mat)
                        <option value="{{ $mat->id_material }}"
                                {{ old('id_material', $movimiento->id_material) == $mat->id_material ? 'selected' : '' }}>
                            {{ $mat->nombre }}
                            @if($mat->unidad_medida) ({{ $mat->unidad_medida }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('id_material')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Cantidad --}}
            <div class="col-md-6">
                <label class="form-label" for="cantidad">
                    Cantidad <span class="text-danger">*</span>
                </label>
                <input type="number"
                       id="cantidad"
                       name="cantidad"
                       class="form-control @error('cantidad') is-invalid @enderror"
                       value="{{ old('cantidad', $movimiento->cantidad) }}"
                       step="0.0001"
                       min="0.0001"
                       required>
                @error('cantidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tipo --}}
            <div class="col-md-6">
                <label class="form-label" for="tipo">
                    Tipo de Movimiento <span class="text-danger">*</span>
                </label>
                <select id="tipo"
                        name="tipo"
                        class="form-select @error('tipo') is-invalid @enderror"
                        required>
                    <option value="entrada" {{ old('tipo', $movimiento->tipo) === 'entrada' ? 'selected' : '' }}>
                        📥 Entrada (Ingreso al stock)
                    </option>
                    <option value="salida" {{ old('tipo', $movimiento->tipo) === 'salida' ? 'selected' : '' }}>
                        📤 Salida (Consumo / Uso)
                    </option>
                </select>
                @error('tipo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Destino / Proyecto --}}
            <div class="col-md-6">
                <label class="form-label" for="id_proyecto">
                    Destino — Proyecto
                </label>
                <select id="id_proyecto"
                        name="id_proyecto"
                        class="form-select @error('id_proyecto') is-invalid @enderror">
                    <option value="">Sin proyecto asignado</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}"
                                {{ old('id_proyecto', $movimiento->id_proyecto) == $p->id_proyecto ? 'selected' : '' }}>
                            {{ $p->nombre_proyecto }}
                            @if($p->codigo_proyecto) — {{ $p->codigo_proyecto }} @endif
                        </option>
                    @endforeach
                </select>
                @error('id_proyecto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          rows="3">{{ old('descripcion', $movimiento->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="form-divider">

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary interactive-btn">
                <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

@endsection
