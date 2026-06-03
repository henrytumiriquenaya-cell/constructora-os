@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page_title', 'Editar Cliente')
@section('page_subtitle', 'Gestión Operativa · Modificar datos del cliente')

@section('content')
<div class="container-fluid" style="max-width:700px">
    <h3 class="fw-light text-secondary mb-4">Gestión Operativa &rsaquo; Editar Cliente</h3>
    <form action="{{ route('operativa.clientes.update', $cliente->id_cliente) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tipo de cliente <span class="text-danger">*</span></label>
                <select name="tipo_cliente" class="form-select @error('tipo_cliente') is-invalid @enderror" required>
                    <option value="natural"  {{ old('tipo_cliente', $cliente->tipo_cliente) == 'natural'  ? 'selected' : '' }}>Persona Natural</option>
                    <option value="juridica" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'juridica' ? 'selected' : '' }}>Persona Jurídica</option>
                </select>
                @error('tipo_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Ciudad</label>
                <select name="id_ciudad" class="form-select">
                    <option value="">— Sin ciudad —</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->id_ciudad }}" {{ old('id_ciudad', $cliente->id_ciudad) == $ciudad->id_ciudad ? 'selected' : '' }}>
                            {{ $ciudad->nombre_ciudad }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Nombre / Razón Social <span class="text-danger">*</span></label>
                <input type="text" name="nombre_razon" class="form-control @error('nombre_razon') is-invalid @enderror"
                       value="{{ old('nombre_razon', $cliente->nombre_razon) }}" required maxlength="150">
                @error('nombre_razon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Documento de identidad / NIT <span class="text-danger">*</span></label>
                <input type="text" name="documento_identidad" class="form-control @error('documento_identidad') is-invalid @enderror"
                       value="{{ old('documento_identidad', $cliente->documento_identidad) }}" required maxlength="20">
                @error('documento_identidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado <span class="text-danger">*</span></label>
                <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                    <option value="activo"   {{ old('estado', $cliente->estado) == 'activo'   ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ old('estado', $cliente->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="moroso"   {{ old('estado', $cliente->estado) == 'moroso'   ? 'selected' : '' }}>Moroso</option>
                </select>
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono principal <span class="text-danger">*</span></label>
                <input type="text" name="telefono_principal" class="form-control @error('telefono_principal') is-invalid @enderror"
                       value="{{ old('telefono_principal', $cliente->telefono_principal) }}" required maxlength="15">
                @error('telefono_principal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Teléfono secundario</label>
                <input type="text" name="telefono_secundario" class="form-control"
                       value="{{ old('telefono_secundario', $cliente->telefono_secundario) }}" maxlength="15">
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo <span class="text-danger">*</span></label>
                <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                       value="{{ old('correo', $cliente->correo) }}" required maxlength="100">
                @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Dirección <span class="text-danger">*</span></label>
                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                       value="{{ old('direccion', $cliente->direccion) }}" required maxlength="200">
                @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary interactive-btn"><i class="fas fa-save me-1"></i> Actualizar</button>
                <a href="{{ route('operativa.clientes.index') }}" class="btn btn-secondary interactive-btn">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
