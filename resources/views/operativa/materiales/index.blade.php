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
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>Precio Ref.</th>
                    <th>Stock Mín.</th>
                    <th>Estado Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($materiales as $m)
                @php
                    $stockBadge = match(true) {
                        is_null($m->stock_minimo)        => ['secondary', 'Sin límite'],
                        ($m->stock_actual ?? 0) <= 0     => ['danger',    'Sin stock'],
                        ($m->stock_actual ?? 0) < $m->stock_minimo => ['warning', 'Stock bajo'],
                        default                          => ['success',   'OK'],
                    };
                @endphp
                <tr>
                    <td>{{ $m->id_material }}</td>
                    <td><code>{{ $m->codigo_interno ?? '—' }}</code></td>
                    <td>{{ $m->nombre }}</td>
                    <td>{{ $m->categoria ?? '—' }}</td>
                    <td><span class="badge bg-secondary">{{ $m->unidad_medida ?? '—' }}</span></td>
                    <td>{{ $m->precio_unitario_ref !== null ? number_format($m->precio_unitario_ref, 2) : '—' }}</td>
                    <td>{{ $m->stock_minimo ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $stockBadge[0] }}">{{ $stockBadge[1] }}</span>
                    </td>
                    <td class="text-nowrap">
                        
                        <a href="{{ route('operativa.materiales.edit', $m->id_material) }}"
                           class="btn btn-edit btn-sm interactive-btn">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('operativa.materiales.destroy', $m->id_material) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('¿Eliminar material?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm interactive-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Sin materiales registrados.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $materiales->links() }}
        </div>
    </div>
</div>
@endsection

