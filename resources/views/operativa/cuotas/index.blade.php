@extends('layouts.app')

@section('title', 'Cuotas de Pago')
@section('page_title', 'Cuotas de Pago')
@section('page_subtitle', 'Gestión Operativa · Control de pagos y vencimientos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Cuotas de Pago</h4>
        <a href="{{ route('operativa.cuotas.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nueva Cuota
        </a>
    </div>

    {{-- Filtros --}}
    <div class="page-card mb-3">
        <form method="GET" action="{{ route('operativa.cuotas.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small mb-1">Proyecto</label>
                <select name="id_proyecto" class="form-select form-select-sm">
                    <option value="">— Todos los proyectos —</option>
                    @foreach($proyectos as $p)
                        <option value="{{ $p->id_proyecto }}" {{ request('id_proyecto') == $p->id_proyecto ? 'selected' : '' }}>
                            {{ $p->nombre_proyecto }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Estado</label>
                <select name="estado_cuota" class="form-select form-select-sm">
                    <option value="">— Todos los estados —</option>
                    @foreach(['pendiente','pagada_tiempo','pagada_tarde','vencida','suspendida','reprogramada'] as $est)
                        <option value="{{ $est }}" {{ request('estado_cuota') == $est ? 'selected' : '' }}>
                            {{ str_replace('_',' ', ucfirst($est)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                    <i class="fas fa-filter me-1"></i>Filtrar
                </button>
                @if(request('id_proyecto') || request('estado_cuota'))
                    <a href="{{ route('operativa.cuotas.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="alert alert-info py-2 small">
        <i class="fas fa-bolt me-1"></i>
        Datos calculados dinámicamente desde <code>cuotas_pago</code> con evaluación de estado y días de retraso.
    </div>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Contrato / Cliente</th>
                    <th>Nro.</th>
                    <th>Monto</th>
                    <th>Vencimiento</th>
                    <th>Días retraso</th>
                    <th>Estado</th>
                    <th>Pagado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($cuotas as $c)
                @php
                    $estado = $c['estado_cuota'] ?? 'pendiente';
                    $badgeMap = [
                        'pagada_tiempo' => 'success',
                        'pagada_tarde'  => 'warning',
                        'vencida'       => 'danger',
                        'suspendida'    => 'dark',
                        'reprogramada'  => 'info',
                        'pendiente'     => 'secondary',
                    ];
                    $badge = $badgeMap[$estado] ?? 'secondary';
                    $diasRetraso = (int) ($c['dias_retraso'] ?? 0);
                    $yaPagada = in_array($estado, ['pagada_tiempo', 'pagada_tarde']);
                @endphp
                <tr>
                    <td>{{ $c['id_cuota'] ?? '—' }}</td>
                    <td>
                        <span class="text-primary fw-semibold">
                            <i class="fas fa-diagram-project me-1 small"></i>{{ $c['nombre_proyecto'] ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <div><code>{{ $c['numero_contrato'] ?? '—' }}</code></div>
                        <div class="text-muted small">{{ $c['nombre_razon'] ?? '—' }}</div>
                    </td>
                    <td class="text-center">{{ $c['numero_cuota'] ?? '—' }}</td>
                    <td class="text-end">{{ number_format((float) ($c['monto_cuota'] ?? 0), 2) }}</td>
                    <td>{{ $c['fecha_vencimiento'] ?? '—' }}</td>
                    <td>
                        @if($diasRetraso > 0 && !$yaPagada)
                            <span class="badge bg-danger">{{ $diasRetraso }} días</span>
                        @else
                            <span class="text-muted">0</span>
                        @endif
                    </td>
                    <td><span class="badge bg-{{ $badge }}">{{ str_replace('_', ' ', $estado) }}</span></td>
                    <td class="text-end">
                        {{ isset($c['monto_pagado']) && $c['monto_pagado'] !== null ? number_format((float) $c['monto_pagado'], 2) : '—' }}
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            @if(!$yaPagada)
                                <a href="{{ route('operativa.cuotas.registrar_pago_form', $c['id_cuota']) }}"
                                   class="btn btn-sm btn-outline-success interactive-btn" title="Registrar pago">
                                    <i class="fas fa-hand-holding-dollar"></i>
                                </a>
                            @endif
                            @if(in_array($estado, ['suspendida', 'vencida']))
                                <form action="{{ route('operativa.cuotas.reanudar_obra', $c['id_cuota']) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary interactive-btn" title="Reanudar obra">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('operativa.cuotas.edit', $c['id_cuota']) }}"
                               class="btn btn-sm btn-outline-secondary interactive-btn" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('operativa.cuotas.destroy', $c['id_cuota']) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta cuota?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger interactive-btn" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Sin cuotas registradas para este filtro.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $cuotas->links() }}
        </div>
    </div></div>
@endsection