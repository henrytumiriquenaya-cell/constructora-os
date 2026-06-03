@extends('layouts.app')

@section('title', 'Nueva Ciudad')
@section('page_title', 'Nueva Ciudad')
@section('page_subtitle', 'Maestros · Registrar ciudad')

@section('content')
<div class="container-fluid" style="max-width:560px;">
    <h4 class="fw-light text-secondary mb-3">Nueva Ciudad</h4>
    <hr>
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form action="{{ route('operativa.ciudades.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Departamento *</label>
            <input type="text" name="departamento" class="form-control" value="{{ old('departamento') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">País *</label>
            <input type="text" name="pais" class="form-control" value="{{ old('pais', 'Bolivia') }}" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('operativa.ciudades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection