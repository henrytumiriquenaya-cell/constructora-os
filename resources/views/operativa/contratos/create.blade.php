@extends('layouts.app')

@section('title', 'Nuevo Contrato')
@section('page_title', 'Nuevo Contrato')
@section('page_subtitle', 'Gestión Operativa · Registrar contrato')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <h4 class="fw-light text-secondary mb-3">Nuevo Contrato</h4>
    <hr>
    @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form action="{{ route('operativa.contratos.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Cliente *</label>
                <select name="id_cliente" class="form-select" required>
                    <option value="">— Seleccionar —</option>
                    @foreach($clientes as $cl)
                        <option value="{{ $cl->id_cliente }}" {{ old('id_cliente') == $cl->id_cliente ? 'selected':'' }}>{{ $cl->nombre_razon }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nro. Contrato *</label>
                <input type="text" name="numero_contrato" class="form-control" value="{{ old('numero_contrato') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Firma *</label>
                <input type="date" name="fecha_firma" class="form-control" value="{{ old('fecha_firma') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Inicio *</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Fin Prevista *</label>
                <input type="date" name="fecha_fin_prevista" class="form-control" value="{{ old('fecha_fin_prevista') }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label">Monto Total *</label>
                <input type="number" step="0.01" name="monto_total" class="form-control" value="{{ old('monto_total') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Moneda *</label>
                <select name="moneda" class="form-select" required>
                    @foreach(['BOB','USD','EUR'] as $m)
                        <option value="{{ $m }}" {{ old('moneda','BOB') === $m ? 'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo Contrato *</label>
                <select name="tipo_contrato" class="form-select" required>
                    @foreach(['llave_en_mano','administracion','mixto'] as $t)
                        <option value="{{ $t }}" {{ old('tipo_contrato') === $t ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-select" required>
                    @foreach(['borrador','firmado','en_ejecucion','concluido','rescindido'] as $e)
                        <option value="{{ $e }}" {{ old('estado','borrador') === $e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('operativa.contratos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection