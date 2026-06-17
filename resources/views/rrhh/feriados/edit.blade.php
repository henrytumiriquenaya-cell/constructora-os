@extends('layouts.app')

@section('title', 'Editar Feriado')
@section('page_title', 'Editar Feriado')
@section('page_subtitle', 'Recursos Humanos · Modificar feriado')

@section('content')
<div class="container-fluid" style="max-width:680px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Recursos Humanos &rsaquo; Editar Feriado</h4>
        <a href="{{ route('rrhh.feriados.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="page-card">
        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('rrhh.feriados.update', $feriado->id_feriado) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="fecha" class="form-control"
                           value="{{ old('fecha', \Carbon\Carbon::parse($feriado->fecha)->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $feriado->nombre) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" id="tipo" class="form-select" required onchange="toggleDepto()">
                        @foreach(['nacional','departamental','municipal'] as $t)
                            <option value="{{ $t }}" {{ old('tipo', $feriado->tipo) === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" id="campo_depto">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control"
                           value="{{ old('departamento', $feriado->departamento) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recargo (%) *</label>
                    <input type="number" step="0.01" min="0" max="999.99" name="recargo_pct" class="form-control"
                           value="{{ old('recargo_pct', $feriado->recargo_pct) }}" required>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar Cambios</button>
                <a href="{{ route('rrhh.feriados.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDepto() {
    const tipo = document.getElementById('tipo').value;
    const campo = document.getElementById('campo_depto');
    campo.style.display = (tipo === 'nacional') ? 'none' : 'block';
}
document.addEventListener('DOMContentLoaded', toggleDepto);
</script>
@endsection