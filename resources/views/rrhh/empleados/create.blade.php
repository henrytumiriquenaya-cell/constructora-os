@extends('layouts.app')

@section('title', 'Nuevo Empleado')
@section('page_title', 'Nuevo Empleado')
@section('page_subtitle', 'Recursos Humanos · Registrar empleado')

@section('content')
<div class="container-fluid" style="max-width:750px">
    <h3 class="fw-light text-secondary mb-4">RRHH &rsaquo; Nuevo Empleado</h3>
    <form action="{{ route('rrhh.empleados.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">CI <span class="text-danger">*</span></label>
                <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci') }}" required maxlength="15">
                @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres') }}" required maxlength="80">
                @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos') }}" required maxlength="80">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Cargo <span class="text-danger">*</span></label>
                <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo') }}" required maxlength="80">
                @error('cargo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Especialidad</label>
                <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad') }}" maxlength="100">
            </div>
            <div class="col-md-4">
                <label class="form-label">Modalidad de pago <span class="text-danger">*</span></label>
                <select name="modalidad_pago" id="modalidad_pago" class="form-select @error('modalidad_pago') is-invalid @enderror" required>
                    <option value="mensual"   {{ old('modalidad_pago','mensual') == 'mensual'   ? 'selected' : '' }}>Mensual</option>
                    <option value="por_hora"  {{ old('modalidad_pago') == 'por_hora'  ? 'selected' : '' }}>Por hora</option>
                    <option value="jornal"    {{ old('modalidad_pago') == 'jornal'    ? 'selected' : '' }}>Jornal</option>
                </select>
                @error('modalidad_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Salario base</label>
                <input type="number" step="0.01" name="salario_base" class="form-control" value="{{ old('salario_base') }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tarifa/hora</label>
                <input type="number" step="0.01" name="tarifa_hora" class="form-control" value="{{ old('tarifa_hora') }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo de contrato <span class="text-danger">*</span></label>
                <select name="tipo_contrato" class="form-select @error('tipo_contrato') is-invalid @enderror" required>
                    @foreach(['indefinido','fijo','eventual','por_obra'] as $tc)
                        <option value="{{ $tc }}" {{ old('tipo_contrato') == $tc ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$tc)) }}</option>
                    @endforeach
                </select>
                @error('tipo_contrato')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" class="form-control @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso') }}" required>
                @error('fecha_ingreso')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}" required maxlength="15">
                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ old('correo') }}" maxlength="100">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{ old('activo', '1') ? 'checked' : '' }}>
                    <label for="activo" class="form-check-label">Activo</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Guardar</button>
                <a href="{{ route('rrhh.empleados.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
