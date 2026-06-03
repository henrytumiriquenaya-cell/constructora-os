@extends('layouts.app')


@section('title', 'Editar Compra')
@section('page_title', 'Editar Compra')
@section('page_subtitle', 'Gestión Operativa · Modificar compra')

@section('content')
    <h3 class="mt-4 fw-light text-secondary">Editar Compra</h3>
    <hr>
    <form action="{{ route('operativa.compras.update', $compra->id_compra) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="numero_orden" class="form-label">Número de Orden</label>
            <input type="text" name="numero_orden" id="numero_orden" class="form-control" value="{{ $compra->numero_orden }}">
        </div>

        <div class="mb-3">
            <label for="fecha_emision" class="form-label">Fecha Emisión</label>
            <input type="date" name="fecha_emision" id="fecha_emision" class="form-control" value="{{ $compra->fecha_emision }}">
        </div>

        <div class="mb-3">
            <label for="monto_total" class="form-label">Monto Total</label>
            <input type="number" step="0.01" name="monto_total" id="monto_total" class="form-control" value="{{ $compra->monto_total }}" readonly>
            <small class="text-muted">Solo lectura. Se recalcula automáticamente desde el detalle de compra.</small>
        </div>

        <div class="mb-3">
            <label for="fecha_entrega_prevista" class="form-label">Fecha Entrega Prevista</label>
            <input type="date" name="fecha_entrega_prevista" id="fecha_entrega_prevista" class="form-control" value="{{ $compra->fecha_entrega_prevista }}">
        </div>

        <div class="mb-3">
            <label for="fecha_entrega_real" class="form-label">Fecha Entrega Real</label>
            <input type="date" name="fecha_entrega_real" id="fecha_entrega_real" class="form-control" value="{{ $compra->fecha_entrega_real }}">
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                @foreach(['borrador','emitida','recibida_parcial','recibida_total','anulada'] as $estado)
                    <option value="{{ $estado }}" {{ $compra->estado === $estado ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $estado)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ $compra->observaciones }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
@endsection
