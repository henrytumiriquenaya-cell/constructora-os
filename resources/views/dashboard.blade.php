@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Panel de Control')
@section('page_subtitle', 'Resumen del sistema · ' . now()->format('d/m/Y'))

@section('content')
{{-- ══ STAT CARDS ══ --}}
<div class="row g-3 mb-4">

    @canAccess('cliente')
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#ede9fe;">
                <i class="fas fa-users" style="color:#7c3aed;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalClientes ?? 0) }}</div>
                <div class="stat-label">Clientes</div>
            </div>
        </div>
    </div>
    @endcanAccess

    @canAccess('proyecto')
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="fas fa-diagram-project" style="color:#2563eb;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalProyectos ?? 0) }}</div>
                <div class="stat-label">Proyectos</div>
            </div>
        </div>
    </div>
    @endcanAccess

    @canAccess('registro_horas')
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="fas fa-clock" style="color:#d97706;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalHoras ?? 0, 0) }}</div>
                <div class="stat-label">Horas registradas</div>
            </div>
        </div>
    </div>
    @endcanAccess

    @canAccess('paralizacion')
    <div class="col-6 col-md-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fee2e2;">
                <i class="fas fa-circle-pause" style="color:#dc2626;font-size:1.1rem;"></i>
            </div>
            <div>
                <div class="stat-value">{{ number_format($totalParalizaciones ?? 0) }}</div>
                <div class="stat-label">Paralizaciones</div>
            </div>
        </div>
    </div>
    @endcanAccess

</div>

{{-- ══ ÚLTIMOS PROYECTOS ══ --}}
@canAccess('proyecto')
@if(isset($ultimosProyectos) && $ultimosProyectos->count())
<div class="page-card p-0 overflow-hidden">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
        <div>
            <span class="fw-600" style="font-size:0.9rem;color:#0f172a;font-weight:600;">Últimos Proyectos</span>
            <span class="text-muted ms-2" style="font-size:0.78rem;">· registros recientes</span>
        </div>
        <a href="{{ route('operativa.proyectos.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-right me-1"></i> Ver todos
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th>
                    <th>Nombre del proyecto</th>
                    <th>Estado</th>
                    <th>Avance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ultimosProyectos as $p)
                @php
                    $estado = $p->estado ?? 'planificacion';
                    $badgeCls = match($estado) {
                        'en_ejecucion' => 'badge-en_ejecucion',
                        'concluido'    => 'badge-concluido',
                        'cancelado'    => 'badge-cancelado',
                        'paralizado'   => 'badge-paralizado',
                        default        => 'badge-activo',
                    };
                    $avance = (int)($p->porcentaje_avance ?? 0);
                    $barColor = $avance >= 75 ? '#10b981' : ($avance >= 40 ? '#6366f1' : '#f59e0b');
                @endphp
                <tr>
                    <td style="font-size:0.8rem;color:#94a3b8;">#{{ $p->id_proyecto }}</td>
                    <td><span class="fw-semibold">{{ $p->nombre_proyecto ?? '—' }}</span></td>
                    <td>
                        <span class="badge badge-status {{ $badgeCls }}">
                            {{ ucfirst(str_replace('_', ' ', $estado)) }}
                        </span>
                    </td>
                    <td style="min-width:120px;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-grow-1" style="height:6px;background:#e2e8f0;border-radius:4px;">
                                <div style="height:6px;width:{{ $avance }}%;background:{{ $barColor }};border-radius:4px;transition:width 0.5s;"></div>
                            </div>
                            <span style="font-size:0.75rem;color:#64748b;white-space:nowrap;">{{ $avance }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endcanAccess

@endsection
