@extends('layouts.app')

@section('title', 'Nuevo Feriado')
@section('page_title', 'Nuevo Feriado')
@section('page_subtitle', 'Recursos Humanos · Registrar feriado')

@section('content')
<div class="container-fluid" style="max-width:680px;">
    <div class="page-card">
        <h5 class="fw-semibold mb-1" style="color:var(--text-primary);">Registrar Feriado</h5>
        <p class="mb-4" style="color:var(--text-secondary); font-size:0.85rem;">
            Define la fecha y el recargo salarial aplicable a empleados que trabajen ese día.
        </p>

        @if($errors->any())
            <div class="alert alert-danger mb-4"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form action="{{ route('rrhh.feriados.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required
                           placeholder="Ej: Día de la Madre, Carnaval...">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" id="tipo" class="form-select" required onchange="toggleDepto()">
                        @foreach(['nacional','departamental','municipal'] as $t)
                            <option value="{{ $t }}" {{ old('tipo','nacional') === $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4" id="campo_depto" style="display:none;">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control" value="{{ old('departamento') }}"
                           placeholder="Ej: Cochabamba">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Recargo (%) *</label>
                    <input type="number" step="0.01" min="0" max="999.99" name="recargo_pct" class="form-control"
                           value="{{ old('recargo_pct', 100) }}" required>
                    <small class="text-muted">100% = doble pago.</small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Guardar</button>
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