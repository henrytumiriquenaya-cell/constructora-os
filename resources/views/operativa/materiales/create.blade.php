@extends('layouts.app')

@section('title', 'Nuevo Material')
@section('page_title', 'Nuevo Material')
@section('page_subtitle', 'Maestros · Registrar nuevo material')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-box-seam me-2" style="color:var(--indigo);"></i>
            Nuevo Material
        </h4>
        <small class="text-muted-dm">Complete los datos del material de construcción</small>
    </div>
    <a href="{{ route('operativa.materiales.index') }}" class="btn btn-secondary btn-sm">
        <i class="ti ti-arrow-left me-1"></i> Volver a la lista
    </a>
</div>

<div class="page-card">
    <form action="{{ route('operativa.materiales.store') }}" method="POST">
        @csrf

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
                       value="{{ old('nombre') }}"
                       placeholder="Ej. Cemento Portland"
                       required>
                @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" for="codigo_interno">
                    Codigo Interno <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="codigo_interno"
                       name="codigo_interno"
                       class="form-control @error('codigo_interno') is-invalid @enderror"
                       value="{{ old('codigo_interno') }}"
                       placeholder="Ej. MAT-0078"
                       required>
                @error('codigo_interno')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
             <div class="col-md-6">
                <label class="form-label" for="categoria">
                    Categoria <span class="text-danger">*</span>
                </label>
                <input type="text"
                       id="categoria"
                       name="categoria"
                       class="form-control @error('categoria') is-invalid @enderror"
                       value="{{ old('categoria') }}"
                       placeholder="Ej. madera,acero"
                       required>
                @error('categoria')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                    <label class="form-label">Unidad de Medida *</label>
                    <select name="unidad_medida" class="form-select" required>
                        @foreach(['kg','ton','m','m2','m3','lt','pieza'] as $est)
                            <option value="{{ $est }}" {{ old('unidad_medida','kg') === $est ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($est)) }}</option>
                        @endforeach
                    </select>
                </div>
             <div class="col-md-6">
                    <label class="form-label">Precio unitario (Bs.)</label>
                    <input type="number" step="0.01" min="0" name="precio_unitario_ref" class="form-control" value="{{ old('precio_unitario_ref') }}" placeholder="0.00">
            </div>
             <div class="col-md-6">
                    <label class="form-label">stock_minimo</label>
                    <input type="number" step="0.01" min="0" name="stock_minimo" class="form-control" value="{{ old('stock_minimo') }}" placeholder="0.00">
            </div>

            {{-- Descripción --}}
            <div class="col-12">
                <label class="form-label" for="descripcion">Descripción</label>
                <textarea id="descripcion"
                          name="descripcion"
                          class="form-control @error('descripcion') is-invalid @enderror"
                          rows="3"
                          placeholder="Detalles sobre el material, especificaciones, usos...">{{ old('descripcion') }}</textarea>
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
                <i class="ti ti-device-floppy me-1"></i> Guardar Material
            </button>
        </div>
    </form>
</div>

@endsection
