@extends('layouts.app')

@section('title', 'Resumen de Costos')
@section('page_title', 'Resumen de Costos')
@section('page_subtitle', 'Reportes · Análisis financiero por proyecto')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Reportes &rsaquo; Resumen de Costos</h4>
            <small class="text-muted">Consolidado de costos por proyecto (actualizado: {{ now()->format('d/m/Y H:i') }})</small>
        </div>
    </div>

    @if($datos->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> No hay datos de costos disponibles.
        </div>
    @else
        <div class="table-wrapper"><div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-head-premium">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Proyecto</th>
                        <th class="text-end">Costo Mano de Obra</th>
                        <th class="text-end">Costo Materiales</th>
                        <th class="text-end">Costo Maquinaria</th>
                        <th class="text-end fw-bold">Costo Total Real</th>
                        <th class="text-end">Monto Planificado</th>
                        <th class="text-end">Margen Restante</th>
                        <th class="text-center small">Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $item)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $item->nombre_proyecto ?? 'N/A' }}</td>
                            <td class="text-end">Bs {{ number_format($item->costo_real_mano_obra ?? 0, 2) }}</td>
                            <td class="text-end">Bs {{ number_format($item->costo_real_materiales ?? 0, 2) }}</td>
                            <td class="text-end">Bs {{ number_format($item->costo_real_maquinaria ?? 0, 2) }}</td>
                            <td class="text-end fw-bold text-primary">Bs {{ number_format($item->costo_total_real ?? 0, 2) }}</td>
                            <td class="text-end">Bs {{ number_format($item->monto_total_planificado ?? 0, 2) }}</td>
                            <td class="text-end {{ ($item->margen_restante ?? 0) < 0 ? 'text-danger' : 'text-success' }}">
                                Bs {{ number_format($item->margen_restante ?? 0, 2) }}
                            </td>
                            <td class="text-center small text-muted">—</td>
                        </tr>
                        @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-bold">
                        <td colspan="2" class="text-end">TOTALES:</td>
                        <td class="text-end">Bs {{ number_format($datos->sum('costo_real_mano_obra'), 2) }}</td>
                        <td class="text-end">Bs {{ number_format($datos->sum('costo_real_materiales'), 2) }}</td>
                        <td class="text-end">Bs {{ number_format($datos->sum('costo_real_maquinaria'), 2) }}</td>
                        <td class="text-end text-primary">Bs {{ number_format($datos->sum('costo_total_real'), 2) }}</td>
                        <td class="text-end">Bs {{ number_format($datos->sum('monto_total_planificado'), 2) }}</td>
                        <td class="text-end">Bs {{ number_format($datos->sum('margen_restante'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $datos->links() }}
    </div></div></div>
    @endif
@endsection
