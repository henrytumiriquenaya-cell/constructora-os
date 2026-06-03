@extends('layouts.app')

@section('title', 'Editar Paralización')
@section('page_title', 'Editar Paralización')
@section('page_subtitle', 'Gestión Operativa · Modificar paralización')

@section('content')
<div class="container-fluid" style="max-width:700px">
    <h3 class="fw-light text-secondary mb-4">Gestión Operativa &rsaquo; Editar Paralización</h3>
    <form action="{{ route('operativa.paralizaciones.update', $paralizacion->id_paralizacion) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Proyecto <span class="text-danger">*</span></label>
                <select name="id_proyecto" class="form-select @error('id_proyecto') is-invalid @enderror" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}" {{ old('id_proyecto', $paralizacion->id_proyecto) == $p->id_proyecto ? 'selected' : '' }}>
                            {{ $p->nombre_proyecto }}
                        </option>
                    @endforeach
                </select>
                @error('id_proyecto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Motivo <span class="text-danger">*</span></label>
                <input type="text" name="motivo" class="form-control @error('motivo') is-invalid @enderror"
                       value="{{ old('motivo', $paralizacion->motivo) }}" required maxlength="200">
                @error('motivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $paralizacion->descripcion) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha inicio <span class="text-danger">*</span></label>
                <input type="date" name="fecha_inicio_par" class="form-control @error('fecha_inicio_par') is-invalid @enderror"
                       value="{{ old('fecha_inicio_par', $paralizacion->fecha_inicio_par) }}" required>
                @error('fecha_inicio_par')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin_par" class="form-control @error('fecha_fin_par') is-invalid @enderror"
                       value="{{ old('fecha_fin_par', $paralizacion->fecha_fin_par) }}">
                @error('fecha_fin_par')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    @foreach(['activa','levantada','en_revision'] as $est)
                        <option value="{{ $est }}" {{ old('estado', $paralizacion->estado) == $est ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$est)) }}</option>
                    @endforeach
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Registrado por</label>
                <input type="text" name="registrado_por" class="form-control" value="{{ old('registrado_por', $paralizacion->registrado_por) }}" maxlength="100">
            </div>
            <div class="col-12 d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.paralizaciones.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
