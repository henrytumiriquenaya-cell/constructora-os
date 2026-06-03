@extends('layouts.app')

@section('title', 'Editar Empleado')
@section('page_title', 'Editar Empleado')
@section('page_subtitle', 'Recursos Humanos · Modificar datos del empleado')

@section('content')
<div class="container-fluid" style="max-width:750px">
    <h3 class="fw-light text-secondary mb-4">RRHH &rsaquo; Editar Empleado</h3>
    <form action="{{ route('rrhh.empleados.update', $empleado->id_empleado) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">CI <span class="text-danger">*</span></label>
                <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror" value="{{ old('ci', $empleado->ci) }}" required maxlength="15">
                @error('ci')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Nombres <span class="text-danger">*</span></label>
                <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres', $empleado->nombres) }}" required maxlength="80">
                @error('nombres')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                <input type="text" name="apellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos', $empleado->apellidos) }}" required maxlength="80">
                @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Cargo <span class="text-danger">*</span></label>
                <input type="text" name="cargo" class="form-control @error('cargo') is-invalid @enderror" value="{{ old('cargo', $empleado->cargo) }}" required maxlength="80">
                @error('cargo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Especialidad</label>
                <input type="text" name="especialidad" class="form-control" value="{{ old('especialidad', $empleado->especialidad) }}" maxlength="100">
            </div>
            <div class="col-md-4">
                <label class="form-label">Modalidad de pago <span class="text-danger">*</span></label>
                <select name="modalidad_pago" class="form-select @error('modalidad_pago') is-invalid @enderror" required>
                    @foreach(['mensual','por_hora','jornal'] as $mp)
                        <option value="{{ $mp }}" {{ old('modalidad_pago', $empleado->modalidad_pago) == $mp ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$mp)) }}</option>
                    @endforeach
                </select>
                @error('modalidad_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Salario base</label>
                <input type="number" step="0.01" name="salario_base" class="form-control" value="{{ old('salario_base', $empleado->salario_base) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tarifa/hora</label>
                <input type="number" step="0.01" name="tarifa_hora" class="form-control" value="{{ old('tarifa_hora', $empleado->tarifa_hora) }}" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo de contrato <span class="text-danger">*</span></label>
                <select name="tipo_contrato" class="form-select @error('tipo_contrato') is-invalid @enderror" required>
                    @foreach(['indefinido','fijo','eventual','por_obra'] as $tc)
                        <option value="{{ $tc }}" {{ old('tipo_contrato', $empleado->tipo_contrato) == $tc ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$tc)) }}</option>
                    @endforeach
                </select>
                @error('tipo_contrato')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de ingreso <span class="text-danger">*</span></label>
                <input type="date" name="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso', $empleado->fecha_ingreso) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de baja</label>
                <input type="date" name="fecha_baja" class="form-control" value="{{ old('fecha_baja', $empleado->fecha_baja) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $empleado->telefono) }}" required maxlength="15">
                @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="correo" class="form-control" value="{{ old('correo', $empleado->correo) }}" maxlength="100">
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="activo" id="activo" class="form-check-input" value="1" {{ old('activo', $empleado->activo) ? 'checked' : '' }}>
                    <label for="activo" class="form-check-label">Activo</label>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Actualizar</button>
                <a href="{{ route('rrhh.empleados.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
