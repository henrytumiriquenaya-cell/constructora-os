@extends('layouts.app')

@section('title', 'Cotizaciones')
@section('page_title', 'Cotizaciones')
@section('page_subtitle', 'Gestión Operativa · Lista de cotizaciones')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Cotizaciones</h4>
        <a href="{{ route('operativa.cotizaciones.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Nueva Cotización</a>
    </div>
    <div class="alert alert-info py-2 small"><i class="fas fa-bolt me-1"></i>El <strong>Total Planificado</strong> es calculado automáticamente por el trigger <code>trg_cotizacion_total_before_insert</code>.</div>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Proyecto</th><th>Ver.</th><th>Elaborado por</th><th>Materiales</th><th>Mano Obra</th><th>Maquinaria</th><th>Adm.</th><th>Total Planif.</th><th>Estado</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($cotizaciones as $c)
                <tr>
                    <td>{{ $c->id_presupuesto }}</td>
                    <td>{{ $c->proyecto->nombre_proyecto ?? '—' }}</td>
                    <td class="text-center">v{{ $c->version }}</td>
                    <td>{{ optional($c->empleado)->nombres ?? '—' }}</td>
                    <td class="text-end">{{ number_format($c->monto_plan_materiales,2) }}</td>
                    <td class="text-end">{{ number_format($c->monto_plan_mano_obra,2) }}</td>
                    <td class="text-end">{{ number_format($c->monto_plan_maquinaria,2) }}</td>
                    <td class="text-end">{{ number_format($c->monto_plan_gastos_adm,2) }}</td>
                    <td class="text-end fw-bold text-primary">{{ number_format($c->monto_total_planificado,2) }}</td>
                    <td><span class="badge badge-status">{{ $c->estado }}</span></td>
                    <td>
                        <a href="{{ route('operativa.cotizaciones.edit', $c->id_presupuesto) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('operativa.cotizaciones.destroy', $c->id_presupuesto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="11" class="text-center text-muted">Sin cotizaciones.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $cotizaciones->links() }}
    </div></div></div>
@endsection