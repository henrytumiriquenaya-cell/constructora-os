@extends('layouts.app')

@section('title', 'Editar Contrato')
@section('page_title', 'Editar Contrato')
@section('page_subtitle', 'Gestión Operativa · Modificar contrato')

@section('content')
<div class="container-fluid" style="max-width:720px;">
    <h4 class="fw-light text-secondary mb-3">Editar Contrato: {{ $contrato->numero_contrato }}</h4>
    <hr>
    <form action="{{ route('operativa.contratos.update', $contrato->id_contrato) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Fecha Fin Prevista *</label>
                <input type="date" name="fecha_fin_prevista" class="form-control" value="{{ old('fecha_fin_prevista', $contrato->fecha_fin_prevista) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Fecha Fin Real</label>
                <input type="date" name="fecha_fin_real" class="form-control" value="{{ old('fecha_fin_real', $contrato->fecha_fin_real) }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Monto Total *</label>
                <input type="number" step="0.01" name="monto_total" class="form-control" value="{{ old('monto_total', $contrato->monto_total) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Moneda *</label>
                <select name="moneda" class="form-select" required>
                    @foreach(['BOB','USD','EUR'] as $m)
                        <option value="{{ $m }}" {{ old('moneda',$contrato->moneda) === $m ? 'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tipo Contrato *</label>
                <select name="tipo_contrato" class="form-select" required>
                    @foreach(['llave_en_mano','administracion','mixto'] as $t)
                        <option value="{{ $t }}" {{ old('tipo_contrato',$contrato->tipo_contrato) === $t ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado *</label>
                <select name="estado" class="form-select" required>
                    @foreach(['borrador','firmado','en_ejecucion','concluido','rescindido'] as $e)
                        <option value="{{ $e }}" {{ old('estado',$contrato->estado) === $e ? 'selected':'' }}>{{ ucfirst(str_replace('_',' ',$e)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$contrato->descripcion) }}</textarea>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('operativa.contratos.show', $contrato->id_contrato) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection