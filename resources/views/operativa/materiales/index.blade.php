@extends('layouts.app')

@section('title', 'Materiales')
@section('page_title', 'Materiales')
@section('page_subtitle', 'Maestros · Gestión de materiales de construcción')

@section('content')

{{-- Header ─────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-box-seam me-2" style="color:var(--indigo);"></i>
            Materiales
        </h4>
        <small class="text-muted-dm">Catálogo de materiales de construcción</small>
    </div>
    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist']))
    <a href="{{ route('operativa.materiales.create') }}" class="btn btn-primary interactive-btn">
        <i class="ti ti-plus"></i> Nuevo Material
    </a>
    @endif
</div>

{{-- KPI Cards ───────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card accent-indigo">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(99,102,241,.15);">
                    <i class="ti ti-box-seam" style="color:var(--indigo);font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $materiales->total() }}</div>
                    <div class="stat-label">Total materiales</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card accent-green">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(16,185,129,.15);">
                    <i class="ti ti-packages" style="color:#10b981;font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $materiales->where('cantidad', '>', 0)->count() }}</div>
                    <div class="stat-label">Con stock</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card accent-red">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(239,68,68,.15);">
                    <i class="ti ti-alert-triangle" style="color:#ef4444;font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $materiales->where('cantidad', 0)->count() }}</div>
                    <div class="stat-label">Sin stock</div>
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
                    <th>Nombre del Material</th>
                    <th class="text-end">Cantidad</th>
                    <th>Descripción</th>
                    <th>Destino (Proyecto)</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materiales as $m)
                <tr>
                    <td class="text-muted-dm small">{{ $m->id_material }}</td>
                    <td>
                        <div class="fw-semibold">{{ $m->nombre }}</div>
                        @if($m->codigo_interno)
                        <small class="text-muted-dm">{{ $m->codigo_interno }}</small>
                        @endif
                    </td>
                    <td class="text-end">
                        @php $qty = (float)($m->cantidad ?? 0); @endphp
                        <span class="badge {{ $qty > 0 ? 'badge-activo' : 'badge-cancelado' }}">
                            {{ number_format($qty, 2) }}
                            {{ $m->unidad_medida ?? '' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted-dm small">
                            {{ Str::limit($m->descripcion ?? '—', 60) }}
                        </span>
                    </td>
                    <td>
                        @if($m->proyecto)
                            <span class="badge badge-en_ejecucion">
                                <i class="ti ti-building me-1"></i>
                                {{ Str::limit($m->proyecto->nombre_proyecto, 30) }}
                            </span>
                        @else
                            <span class="text-muted-dm small">Sin destino</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('operativa.materiales.edit', $m->id_material) }}"
                           class="btn btn-sm btn-warning interactive-btn me-1"
                           title="Editar">
                            <i class="ti ti-edit"></i>
                        </a>
                        <form action="{{ route('operativa.materiales.destroy', $m->id_material) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar el material {{ addslashes($m->nombre) }}?')">
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
                    <td colspan="6" class="text-center py-5">
                        <i class="ti ti-box-seam" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:10px;"></i>
                        <span class="text-muted-dm">No hay materiales registrados aún.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center py-3">
        {{ $materiales->links() }}
    </div>
</div>

@endsection
