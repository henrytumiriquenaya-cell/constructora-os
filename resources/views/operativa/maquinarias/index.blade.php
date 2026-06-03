@extends('layouts.app')


@section('title', 'Maquinaria')
@section('page_title', 'Maquinaria')
@section('page_subtitle', 'Gestión Operativa · Catálogo de maquinaria')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="fas fa-truck-monster me-2 text-primary"></i>
            Catálogo de Maquinaria
        </h4>
        <small class="text-muted">Equipos registrados en flota propia y arrendada</small>
    </div>
    <a href="{{ route('operativa.maquinarias.catalogo_create') }}"
       class="btn btn-primary interactive-btn">
        <i class="fas fa-plus me-1"></i> Nueva maquinaria
    </a>
</div>

{{-- KPIs rápidos --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-primary">{{ $maquinarias->count() }}</div>
            <div class="text-muted small">Total equipos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-success">
                {{ $maquinarias->where('estado_actual', 'disponible')->count() }}
            </div>
            <div class="text-muted small">Disponibles</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-warning">
                {{ $maquinarias->where('tipo_propiedad', 'propio')->count() }}
            </div>
            <div class="text-muted small">Propios</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm text-center py-3">
            <div class="fs-2 fw-bold text-info">
                {{ $maquinarias->where('tipo_propiedad', 'arrendado')->count() }}
            </div>
            <div class="text-muted small">Arrendados</div>
        </div>
    </div>
</div>

{{-- Tabla de maquinaria --}}
<div class="table-wrapper"><div class="table-responsive">
    <table class="table table-hover table-bordered table-interactive align-middle">
        <thead class="table-head-premium">
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Cód. Inventario</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Año</th>
                <th>Propiedad</th>
                <th class="text-end">Costo/Hora</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($maquinarias as $m)
            <tr>
                <td class="text-muted small">{{ $m->id_maquinaria }}</td>
                <td class="fw-semibold">{{ $m->nombre }}</td>
                <td>
                    <span class="badge badge-status">
                        {{ $m->codigo_inventario ?? '—' }}
                    </span>
                </td>
                <td>{{ $m->marca ?? '—' }}</td>
                <td>{{ $m->modelo ?? '—' }}</td>
                <td>{{ $m->anio_fabricacion ?? '—' }}</td>
                <td>
                    @if($m->tipo_propiedad === 'propio')
                        <span class="badge badge-status badge-activo">Propio</span>
                    @elseif($m->tipo_propiedad === 'arrendado')
                        <span class="badge badge-status badge-en_ejecucion text-dark">Arrendado</span>
                    @else
                        <span class="badge badge-status">{{ $m->tipo_propiedad }}</span>
                    @endif
                </td>
                <td class="text-end fw-semibold">
                    Bs {{ number_format($m->costo_hora, 2) }}/h
                </td>
                <td>
                    @php
                        $estado = $m->estado_actual ?? 'desconocido';
                        $color = match($estado) {
                            'disponible'    => 'success',
                            'en uso'        => 'warning',
                            'mantenimiento' => 'danger',
                            default         => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $color }}">
                        {{ ucfirst($estado) }}
                    </span>
                </td>
                <td class="text-center">
                    <a href="{{ route('operativa.maquinarias.catalogo_edit', $m->id_maquinaria) }}"
                       class="btn btn-sm btn-outline-primary interactive-btn me-1"
                       title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('operativa.maquinarias.catalogo_destroy', $m->id_maquinaria) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('¿Eliminar {{ $m->nombre }}?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger interactive-btn" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-muted py-4">
                    <i class="fas fa-truck-monster fa-2x mb-2 d-block opacity-25"></i>
                    No hay maquinaria registrada aún.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $maquinarias->links() }}
    </div></div></div>
@endsection