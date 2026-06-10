@extends('layouts.app')

@section('title', 'Editar Material')
@section('page_title', 'Editar Material')
@section('page_subtitle', 'Maestros · Modificar material')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-edit me-2" style="color:var(--indigo);"></i>
            Editar Material
        </h4>
        <small class="text-muted-dm">Modificar datos del material: <strong>{{ $material->nombre }}</strong></small>
    </div>
    <a href="{{ route('operativa.materiales.index') }}" class="btn btn-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver a la lista
    </a>
</div>

<div class="page-card">
    <form action="{{ route('operativa.materiales.update', $material->id_material) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-section-title">
            <i class="ti ti-info-circle me-2"></i>Información del Material
        </div>

        <div class="row g-3 mt-1">
            {{-- Nombre --}}
            <div class="col-md-6">
                <label class="form-label" for="nombre">
                    Nombre del Material <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       class="form-control @error('nombre') is-invalid @enderror"
                       value="{{ old('nombre', $material->nombre) }}"
                       required>
                @error('nombre')
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
                       value="{{ old('cantidad', $material->cantidad ?? 0) }}"
                       step="0.01"
                       min="0"
                       required>
                @error('cantidad')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Destino (Proyecto) --}}
            <div class="col-md-12">
                <label class="form-label" for="id_proyecto">
                    Destino — Proyecto
                </label>
                <select id="id_proyecto"
                        name="id_proyecto"
                        class="form-select @error('id_proyecto') is-invalid @enderror">
                    <option value="">Sin destino asignado</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}"
                                {{ old('id_proyecto', $material->id_proyecto) == $p->id_proyecto ? 'selected' : '' }}>
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
                          rows="3">{{ old('descripcion', $material->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="form-divider">

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('operativa.materiales.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary interactive-btn">
                <i class="ti ti-device-floppy me-1"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

@endsection
