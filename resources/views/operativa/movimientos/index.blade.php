@extends('layouts.app')

@section('title', 'Movimientos de Inventario')
@section('page_title', 'Movimientos')
@section('page_subtitle', 'Gestión Operativa · Movimientos de materiales')

@section('content')

{{-- Header ─────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-arrows-exchange me-2" style="color:var(--indigo);"></i>
            Movimientos de Inventario
        </h4>
        <small class="text-muted-dm">Entradas y salidas de materiales por proyecto</small>
    </div>
    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','logist']))
    <a href="{{ route('operativa.movimientos.create') }}" class="btn btn-primary interactive-btn">
        <i class="ti ti-plus"></i> Nuevo Movimiento
    </a>
    @endif
</div>

{{-- KPI Cards ───────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card accent-indigo">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(99,102,241,.15);">
                    <i class="ti ti-arrows-exchange" style="color:var(--indigo);font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $movimientos->total() }}</div>
                    <div class="stat-label">Total movimientos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card accent-green">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(16,185,129,.15);">
                    <i class="ti ti-arrow-big-down" style="color:#10b981;font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $movimientos->where('tipo','entrada')->count() }}</div>
                    <div class="stat-label">Entradas</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card accent-yellow">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(245,158,11,.15);">
                    <i class="ti ti-arrow-big-up" style="color:#f59e0b;font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $movimientos->where('tipo','salida')->count() }}</div>
                    <div class="stat-label">Salidas</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tabla ───────────────────────────────────────────────── --}}
<div class="table-wrapper">
    <div class="table-responsive">
        <table class="table table-hover table-interactive align-middle">
            <thead class="table-head-premium">
                <tr>
                    <th>#</th>
                    <th>Material</th>
                    <th class="text-end">Cantidad</th>
                    <th>Tipo</th>
                    <th>Destino (Proyecto)</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                <tr>
                    <td class="text-muted-dm small">{{ $mov->id_movimiento }}</td>
                    <td>
                        <div class="fw-semibold">
                            {{ $mov->material?->nombre ?? '—' }}
                        </div>
                    </td>
                    <td class="text-end fw-semibold">
                        {{ number_format($mov->cantidad, 2) }}
                    </td>
                    <td>
                        @if($mov->tipo === 'entrada')
                            <span class="badge badge-activo">
                                <i class="ti ti-arrow-big-down me-1"></i> Entrada
                            </span>
                        @else
                            <span class="badge badge-pendiente">
                                <i class="ti ti-arrow-big-up me-1"></i> Salida
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($mov->proyecto)
                            <span class="badge badge-en_ejecucion">
                                <i class="ti ti-building me-1"></i>
                                {{ Str::limit($mov->proyecto->nombre_proyecto, 28) }}
                            </span>
                        @else
                            <span class="text-muted-dm small">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted-dm small">
                            {{ Str::limit($mov->descripcion ?? '—', 55) }}
                        </span>
                    </td>
                    <td>
                        <span class="small text-muted-dm">
                            {{ $mov->fecha ? \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i') : '—' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('operativa.movimientos.edit', $mov->id_movimiento) }}"
                           class="btn btn-sm btn-warning interactive-btn me-1"
                           title="Editar">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form action="{{ route('operativa.movimientos.destroy', $mov->id_movimiento) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar este movimiento?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger interactive-btn" title="Eliminar">
                                <i class="ti ti-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ti ti-arrows-exchange" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:10px;"></i>
                        <span class="text-muted-dm">No hay movimientos registrados aún.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center py-3">
        {{ $movimientos->links() }}
    </div>
</div>

@endsection
