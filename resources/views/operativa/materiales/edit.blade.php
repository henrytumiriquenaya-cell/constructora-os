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
                    <label class="form-label">Nombre del Material <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $material->nombre) }}" required>
             </div>

            
            <div class="col-md-6">
                    <label class="form-label">Categoria <span class="text-danger">*</span></label>
                    <input type="text" name="categoria" class="form-control" value="{{ old('categoria', $material->categoria) }}" required>
             </div>
            <div class="col-md-6">
                    <label class="form-label">Unidad de medida <span class="text-danger">*</span></label>
                    <select name="unidad_medida" class="form-select" required>
                        @foreach(['kg','ton','m','m2','m3','lt','pieza'] as $est)
                            <option value="{{ $est }}" {{ old('unidad_medida','kg') === $est ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($est)) }}</option>
                        @endforeach
                    </select>
            </div>
             <div class="col-md-4">
                    <label class="form-label">Precio Unitario <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="precio_unitario_ref" class="form-control" value="{{ old('precio_unitario_ref', $material->precio_unitario_ref) }}" required>
                </div>
             <div class="col-md-4">
                    <label class="form-label">Stock mininmo <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $material->stock_minimo) }}" required>
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
        </di

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
