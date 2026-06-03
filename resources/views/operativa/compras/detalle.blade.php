@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-light text-secondary mb-1">Detalle de Compra #{{ $compra->numero_orden }}</h3>
            <div class="small text-muted">
                Proyecto: <strong>{{ $compra->proyecto->nombre_proyecto ?? '—' }}</strong> |
                Proveedor: <strong>{{ $compra->proveedor->razon_social ?? '—' }}</strong>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('operativa.compras.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
            <form action="{{ route('operativa.compras.detalle.recibir_todo', $compra->id_compra) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-sm interactive-btn">
                    <i class="fas fa-boxes-packing me-1"></i>Recibir Todo
                </button>
            </form>
        </div>
    </div>

    <div class="alert alert-info small py-2">
        <i class="fas fa-circle-info me-1"></i>
        La columna <strong>subtotal</strong> es solo lectura y se calcula por trigger en SQL Server.
    </div>

    <div class="card shadow-sm interactive-card mb-4">
        <div class="card-header bg-light fw-semibold">Agregar ítem al detalle</div>
        <div class="card-body">
            <form action="{{ route('operativa.compras.detalle.store', $compra->id_compra) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">Material *</label>
                    <select name="id_material" class="form-select" required>
                        <option value="">— Seleccionar material —</option>
                        @foreach($materiales as $material)
                            <option value="{{ $material->id_material }}">{{ $material->nombre }} ({{ $material->unidad_medida ?? 'unidad' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cantidad *</label>
                    <input type="number" name="cantidad" step="0.0001" min="0.0001" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Precio Unitario *</label>
                    <input type="number" name="precio_unitario" step="0.0001" min="0" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Cant. Recibida</label>
                    <input type="number" name="cantidad_recibida" step="0.0001" min="0" class="form-control" value="0">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100 interactive-btn" type="submit">Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-hover table-bordered align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>#</th>
                    <th>Material</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal (RO)</th>
                    <th>Cantidad Recibida</th>
                    <th>Estado Línea</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compra->detalles as $detalle)
                    @php
                        $cantidad = (float) $detalle->cantidad;
                        $recibida = (float) ($detalle->cantidad_recibida ?? 0);
                        $estado = $recibida <= 0 ? 'pendiente' : ($recibida < $cantidad ? 'parcial' : 'completo');
                        $color = $estado === 'completo' ? 'success' : ($estado === 'parcial' ? 'warning' : 'secondary');
                    @endphp
                    <tr>
                        <form action="{{ route('operativa.compras.detalle.update', $compra->id_compra) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td>{{ $detalle->id_detalle }}</td>
                            <td>{{ $detalle->material->nombre ?? '—' }}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm cantidad-input"
                                       name="detalles[{{ $detalle->id_detalle }}][cantidad]"
                                       value="{{ $detalle->cantidad }}" step="0.0001" min="0.0001">
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm"
                                       name="detalles[{{ $detalle->id_detalle }}][precio_unitario]"
                                       value="{{ $detalle->precio_unitario }}" step="0.0001" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm" value="{{ $detalle->subtotal }}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm recibida-input"
                                       name="detalles[{{ $detalle->id_detalle }}][cantidad_recibida]"
                                       value="{{ $detalle->cantidad_recibida ?? 0 }}" step="0.0001" min="0">
                            </td>
                            <td><span class="badge bg-{{ $color }}">{{ ucfirst($estado) }}</span></td>
                            <td class="text-nowrap d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary interactive-btn" title="Guardar línea"><i class="fas fa-save"></i></button>
                        </form>
                        <form action="{{ route('operativa.compras.detalle.destroy', [$compra->id_compra, $detalle->id_detalle]) }}"
                              method="POST" onsubmit="return confirm('¿Eliminar ítem?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger interactive-btn" title="Eliminar línea"><i class="fas fa-trash"></i></button>
                        </form>
                            </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Sin ítems en esta compra.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
        <button type="button" id="btnRecibirTodoCliente" class="btn btn-outline-success interactive-btn">Completar cantidad recibida (UI)</button>
    </div>
</div>

<script>
document.getElementById('btnRecibirTodoCliente')?.addEventListener('click', function () {
    const filas = document.querySelectorAll('tr');
    filas.forEach(function (row) {
        const cantidad = row.querySelector('.cantidad-input');
        const recibida = row.querySelector('.recibida-input');
        if (cantidad && recibida) {
            recibida.value = cantidad.value;
        }
    });
});
</script>
@endsection
