@extends('layouts.app')

@section('title', 'Compras')
@section('page_title', 'Compras')
@section('page_subtitle', 'Gestión Operativa · Registro de compras')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Órdenes de Compra</h4>
        <a href="{{ route('operativa.compras.create') }}" class="btn btn-primary btn-sm interactive-btn"><i class="fas fa-plus me-1"></i>Nueva Compra</a>
    </div>
    <div class="alert alert-dark border small py-2 mb-3">
        <i class="fas fa-database me-1"></i>
        El campo <strong>monto_total</strong> es de solo lectura y se calcula automáticamente en SQL Server por trigger.
    </div>
    <hr>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Nro. Orden</th><th>Proveedor</th><th>Proyecto</th><th>Monto</th><th>Estado</th><th>Emisión</th><th>Entrega Prev.</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            @forelse($compras as $c)
                <tr>
                    <td>{{ $c->id_compra }}</td>
                    <td><code>{{ $c->numero_orden }}</code></td>
                    <td>{{ $c->proveedor->razon_social ?? '—' }}</td>
                    <td>{{ $c->proyecto->nombre_proyecto ?? '—' }}</td>
                    <td class="text-end fw-bold">{{ number_format($c->monto_total,2) }}</td>
                    <td>
                        @php $col=['borrador'=>'secondary','emitida'=>'primary','recibida_parcial'=>'warning','recibida_total'=>'success','anulada'=>'danger'][$c->estado] ?? 'secondary' @endphp
                        <span class="badge bg-{{ $col }}">{{ str_replace('_',' ',$c->estado) }}</span>
                    </td>
                    <td>{{ $c->fecha_emision }}</td>
                    <td>{{ $c->fecha_entrega_prevista ?? '—' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('operativa.compras.detalle', $c->id_compra) }}" class="btn btn-sm btn-outline-dark interactive-btn"><i class="fas fa-list-check me-1"></i>Detalle</a>
                        <a href="{{ route('operativa.compras.show', $c->id_compra) }}" class="btn btn-sm btn-outline-primary interactive-btn"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('operativa.compras.edit', $c->id_compra) }}" class="btn btn-sm btn-outline-secondary interactive-btn"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('operativa.compras.destroy', $c->id_compra) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger interactive-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted">Sin órdenes de compra.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $compras->links() }}
    </div></div></div>
@endsection
