@extends('layouts.app')
@section('title', 'Movimientos de Inventario')
@section('page_title', 'Movimientos')
@section('page_subtitle', 'Gestión Operativa · Movimientos de materiales')
@section('content')

{{-- ── Encabezado ─────────────────────────────────────────────────────────── --}}
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

{{-- ── Alertas de sesión ───────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Filtros ─────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('operativa.movimientos.index') }}" class="row g-2 mb-4 align-items-end">
    <div class="col-md-5">
        <label class="form-label text-muted small fw-bold">Filtrar por Proyecto</label>
        <select name="id_proyecto" class="form-select bg-dark text-white border-secondary">
            <option value="">— Todos los Proyectos —</option>
            @foreach($proyectosList as $p)
                <option value="{{ $p->id_proyecto }}"
                    {{ $idProyecto == $p->id_proyecto ? 'selected' : '' }}>
                    {{ $p->nombre_proyecto }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label text-muted small fw-bold">Tipo de Movimiento</label>
        <select name="tipo" class="form-select bg-dark text-white border-secondary">
            <option value="">— Todos los Tipos —</option>
            <option value="ENTRADA" {{ $tipoMovimiento == 'ENTRADA' ? 'selected' : '' }}>Entradas</option>
            <option value="SALIDA"  {{ $tipoMovimiento == 'SALIDA'  ? 'selected' : '' }}>Salidas</option>
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary w-100">
            <i class="ti ti-filter"></i> Filtrar
        </button>
        <a href="{{ route('operativa.movimientos.index') }}" class="btn btn-outline-secondary w-100">
            Limpiar
        </a>
    </div>
</form>

{{-- ── KPIs ────────────────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card accent-indigo">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:rgba(99,102,241,.15);">
                    <i class="ti ti-arrows-exchange" style="color:var(--indigo);font-size:1.4rem;"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalMovimientos }}</div>
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
                    <div class="stat-value">{{ $totalEntradas }}</div>
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
                    <div class="stat-value">{{ $totalSalidas }}</div>
                    <div class="stat-label">Salidas</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabla ───────────────────────────────────────────────────────────────── --}}
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
                        <div class="fw-semibold">{{ $mov->material?->nombre ?? '—' }}</div>
                    </td>

                    <td class="text-end fw-semibold">
                        {{ number_format((float)$mov->cantidad, 2) }}
                    </td>

                    <td>
                        @if(strtoupper($mov->tipo) === 'ENTRADA')
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
                        {{--
                            Indicador visual de origen del movimiento.
                            id_uso_material con valor → generado por trigger de uso_material.
                            id_uso_material NULL → movimiento manual o de compra.
                        --}}
                        @if(!empty($mov->id_uso_material))
                            <br>
                            <span class="badge bg-secondary" style="font-size:0.65rem;">
                                <i class="ti ti-cpu me-1"></i>Trigger #{{ $mov->id_uso_material }}
                            </span>
                        @endif
                    </td>

                    <td>
                        <span class="small text-muted-dm">
                            {{ $mov->fecha
                                ? \Carbon\Carbon::parse($mov->fecha)->format('d/m/Y H:i')
                                : '—' }}
                        </span>
                    </td>

                    <td class="text-center">
                        {{--
                            LÓGICA DE ACCESO CORREGIDA:
                            ──────────────────────────────────────────────────────
                            ANTES: se detectaba si era automático mirando el texto
                                   de la descripción (frágil, podía fallar).
                            AHORA: se revisa id_uso_material directamente.
                                   - Con id_uso_material: generado por trigger → solo admin edita
                                   - Sin id_uso_material: movimiento manual → todos pueden editar
                            ──────────────────────────────────────────────────────
                        --}}
                        @php
                            $esAutomatico = !empty($mov->id_uso_material);
                            $puedeEditar  = !$esAutomatico || Auth::user()->hasRole('admin');
                        @endphp

                        @if($puedeEditar)
                            <a href="{{ route('operativa.movimientos.edit', $mov->id_movimiento) }}"
                               class="btn btn-sm btn-warning interactive-btn me-1"
                               title="{{ $esAutomatico ? 'Editar descripción (admin)' : 'Editar' }}">
                                <i class="ti ti-edit"></i>
                            </a>

                            <form action="{{ route('operativa.movimientos.destroy', $mov->id_movimiento) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este movimiento?\n{{ $esAutomatico ? 'Advertencia: es un movimiento automático.' : 'El inventario se corregirá.' }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger interactive-btn" title="Eliminar">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>

                        @else
                            {{-- Movimiento automático y el usuario no es admin --}}
                            <span class="badge bg-dark text-muted-dm"
                                  title="Movimiento generado por trigger. Solo un Administrador puede editarlo.">
                                <i class="ti ti-lock"></i> Sistema
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ti ti-arrows-exchange"
                           style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:10px;"></i>
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