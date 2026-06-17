@extends('layouts.app')


@section('title', 'Log de Auditoría')
@section('page_title', 'Log de Auditoría')
@section('page_subtitle', 'Sistema · Registro de cambios')

@section('content')
<div class="container-fluid px-0">

    {{-- ======================== HEADER ======================== --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-0" style="color:#1a1f36;">
                <i class="fas fa-shield-halved me-2" style="color:#0d6efd;"></i>
                Log de Auditoría
            </h3>
            <small class="text-muted">Reportes &rsaquo; Log de Cambios</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge rounded-pill px-3 py-2 fs-6" style="background:#eef2ff;color:#3730a3;">
                <i class="fas fa-database me-1"></i>
                {{ number_format($totalRegistros) }} registros totales
            </span>
        </div>
    </div>

    {{-- ======================== TARJETAS RESUMEN ======================== --}}
    <div class="row g-3 mb-4">
        @php
            $tipos = [
                'LOGIN'  => ['label' => 'Inicios de sesión', 'icon' => 'fa-arrow-right-to-bracket', 'color' => '#10b981', 'bg' => '#ecfdf5'],
                'LOGOUT' => ['label' => 'Cierres de sesión',  'icon' => 'fa-arrow-right-from-bracket','color' => '#6b7280', 'bg' => '#f9fafb'],
                'I'      => ['label' => 'Inserciones',        'icon' => 'fa-plus-circle',             'color' => '#3b82f6', 'bg' => '#eff6ff'],
                'U'      => ['label' => 'Actualizaciones',    'icon' => 'fa-pen-to-square',           'color' => '#f59e0b', 'bg' => '#fffbeb'],
                'D'      => ['label' => 'Eliminaciones',      'icon' => 'fa-trash-can',               'color' => '#ef4444', 'bg' => '#fef2f2'],
            ];
        @endphp

        @foreach($tipos as $tipo => $meta)
        @php $count = \App\Models\LogCambio::where('campo', $tipo)->count(); @endphp

        <div class="col-6 col-md-4 col-lg">
            <div class="rounded-3 p-3 h-100 d-flex align-items-center gap-3 border"
                 style="background:{{ $meta['bg'] }}; border-color:{{ $meta['color'] }}22 !important; cursor:pointer;"
                 onclick="document.getElementById('filtroTipo').value='{{ $tipo }}'; document.getElementById('formFiltros').submit();">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:42px;height:42px;background:{{ $meta['color'] }}18;">
                    <i class="fas {{ $meta['icon'] }}" style="color:{{ $meta['color'] }};font-size:1rem;"></i>
                </div>
                <div>
                    <div class="fw-bold fs-5 lh-1" style="color:{{ $meta['color'] }};">{{ number_format($count) }}</div>
                    <div class="text-muted" style="font-size:0.75rem;">{{ $meta['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ======================== FILTROS ======================== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3"
             style="border-radius:12px 12px 0 0; cursor:pointer;"
             data-bs-toggle="collapse" data-bs-target="#panelFiltros" aria-expanded="{{ request()->hasAny(['id_usuario','tabla','tipo','fecha_desde','fecha_hasta','search']) ? 'true' : 'false' }}">
            <span class="fw-semibold text-dark">
                <i class="fas fa-filter me-2 text-primary"></i>Filtros
                @if(request()->hasAny(['id_usuario','tabla','tipo','fecha_desde','fecha_hasta','search']))
                    <span class="badge bg-primary rounded-pill ms-1" style="font-size:0.7rem;">Activos</span>
                @endif
            </span>
            <i class="fas fa-chevron-down text-muted small"></i>
        </div>
        <div class="collapse {{ request()->hasAny(['id_usuario','tabla','tipo','fecha_desde','fecha_hasta','search']) ? 'show' : '' }}" id="panelFiltros">
            <div class="card-body pt-0">
                <form id="formFiltros" method="GET" action="{{ route('reportes.log.index') }}">
                    <div class="row g-3 align-items-end">
                        {{-- Usuario --}}
                        <div class="col-12 col-md-6 col-lg-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Usuario</label>
                            <select name="id_usuario" class="form-select form-select-sm" style="border-radius:8px;">
                                <option value="">Todos los usuarios</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id_usuario }}" {{ request('id_usuario') == $u->id_usuario ? 'selected' : '' }}>
                                        {{ $u->nombre_completo ?? $u->nombre_usuario ?? $u->usuario }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tabla afectada --}}
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Tabla afectada</label>
                            <select name="tabla" class="form-select form-select-sm" style="border-radius:8px;">
                                <option value="">Todas</option>
                                @foreach($tablas as $t)
                                    <option value="{{ $t }}" {{ request('tabla') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tipo de operación --}}
                        <div class="col-12 col-md-6 col-lg-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Tipo de operación</label>
                            <select id="filtroTipo" name="tipo" class="form-select form-select-sm" style="border-radius:8px;">
                                <option value="">Todos</option>
                                <option value="LOGIN"  {{ request('tipo') === 'LOGIN'  ? 'selected' : '' }}>🟢 Login</option>
                                <option value="LOGOUT" {{ request('tipo') === 'LOGOUT' ? 'selected' : '' }}>⚫ Logout</option>
                                <option value="I"      {{ request('tipo') === 'I'      ? 'selected' : '' }}>🔵 Inserción</option>
                                <option value="U"      {{ request('tipo') === 'U'      ? 'selected' : '' }}>🟡 Actualización</option>
                                <option value="D"      {{ request('tipo') === 'D'      ? 'selected' : '' }}>🔴 Eliminación</option>
                            </select>
                        </div>

                        {{-- Fecha desde --}}
                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control form-control-sm" style="border-radius:8px;"
                                   value="{{ request('fecha_desde') }}">
                        </div>

                        {{-- Fecha hasta --}}
                        <div class="col-6 col-md-3 col-lg-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" style="border-radius:8px;"
                                   value="{{ request('fecha_hasta') }}">
                        </div>

                        {{-- Botones --}}
                        <div class="col-12 col-lg-1 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100 interactive-btn" style="border-radius:8px;">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('reportes.log.index') }}" class="btn btn-outline-secondary btn-sm interactive-btn" style="border-radius:8px;" title="Limpiar filtros">
                                <i class="fas fa-xmark"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Búsqueda libre --}}
                    <div class="row mt-2">
                        <div class="col-12 col-md-6">
                            <input type="text" name="search" class="form-control form-control-sm" style="border-radius:8px;"
                                   placeholder="Buscar en descripción..." value="{{ request('search') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======================== TABLA ======================== --}}
    <div class="card border-0 shadow-sm" style="border-radius:12px; overflow:hidden;">
        <div class="card-body p-0">
            @if($logs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-search fa-2x mb-3 d-block opacity-30"></i>
                    No se encontraron registros con los filtros aplicados.
                </div>
            @else
            <div class="table-wrapper"><div class="table-responsive">
                <table class="table table-hover table-interactive mb-0 align-middle" style="font-size:0.875rem;">
                    <thead style="background:#f8f9fb; border-bottom:2px solid #e9ecef;">
                        <tr>
                            <th class="px-4 py-3 fw-semibold text-muted" style="white-space:nowrap; width:160px;">Fecha / Hora</th>
                            <th class="px-3 py-3 fw-semibold text-muted">Usuario</th>
                            <th class="px-3 py-3 fw-semibold text-muted" style="width:130px;">Tipo</th>
                            <th class="px-3 py-3 fw-semibold text-muted" style="width:150px;">Tabla afectada</th>
                            <th class="px-3 py-3 fw-semibold text-muted">Descripción</th>
                            <th class="px-3 py-3 fw-semibold text-muted text-center" style="width:80px;">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            {{-- Fecha/Hora --}}
                            <td class="px-4 py-3" style="white-space:nowrap;">
                                <div class="fw-semibold text-dark" style="font-size:0.8rem;">
                                    {{ \Carbon\Carbon::parse($log->fecha_hora)->format('d/m/Y') }}
                                </div>
                                <div class="text-muted" style="font-size:0.75rem;">
                                    {{ \Carbon\Carbon::parse($log->fecha_hora)->format('H:i:s') }}
                                </div>
                            </td>

                                                        {{-- Usuario --}}
                            <td class="px-3 py-3">
                                @if($log->usuario)
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Círculo con Iniciales extraídas del texto (ej: RO de root@localhost) -->
                                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold"
                                             style="width:30px;height:30px;background:#eef2ff;color:#3730a3;font-size:0.7rem;">
                                            {{ strtoupper(substr($log->usuario, 0, 2)) }}
                                        </div>
                                        <div>
                                            <!-- Nombre de usuario o correo directo desde la base de datos -->
                                            <div class="fw-semibold text-dark" style="font-size:0.82rem;">
                                                {{ $log->usuario }}
                                            </div>
                                            <!-- Subtexto indicando que es la firma del log de auditoría -->
                                            <div class="text-muted" style="font-size:0.72rem;">
                                                Firma de auditoría
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted fst-italic" style="font-size:0.8rem;">Sistema</span>
                                @endif
                            </td>


                            {{-- Tipo de operación --}}
                            <td class="px-3 py-3">
                                @php
                                    $badgeMap = [
                                        'LOGIN'  => ['bg'=>'#ecfdf5','color'=>'#059669','icon'=>'fa-arrow-right-to-bracket','text'=>'Login'],
                                        'LOGOUT' => ['bg'=>'#f3f4f6','color'=>'#6b7280','icon'=>'fa-arrow-right-from-bracket','text'=>'Logout'],
                                        'I'      => ['bg'=>'#eff6ff','color'=>'#2563eb','icon'=>'fa-plus',          'text'=>'Inserción'],
                                        'U'      => ['bg'=>'#fffbeb','color'=>'#d97706','icon'=>'fa-pen',           'text'=>'Actualización'],
                                        'D'      => ['bg'=>'#fef2f2','color'=>'#dc2626','icon'=>'fa-trash',         'text'=>'Eliminación'],
                                    ];
                                    $badge = $badgeMap[$log->tipo_operacion] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','icon'=>'fa-circle-question','text'=>$log->tipo_operacion];
                                @endphp
                                <span class="badge d-inline-flex align-items-center gap-1 px-2 py-1"
                                      style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};border-radius:20px;font-size:0.74rem;font-weight:600;border:1px solid {{ $badge['color'] }}33;">
                                    <i class="fas {{ $badge['icon'] }}"></i>
                                    {{ $badge['text'] }}
                                </span>
                            </td>

                            {{-- Tabla afectada --}}
                            <td class="px-3 py-3">
                                <code class="px-2 py-1 rounded" style="background:#f1f5f9;color:#334155;font-size:0.78rem;">
                                    {{ $log->tabla_afectada }}
                                </code>
                            </td>

                            {{-- Descripción --}}
                            <td class="px-3 py-3 text-muted" style="max-width:300px;">
                                <span class="text-truncate d-block" style="max-width:280px;" title="{{ $log->descripcion }}">
                                    {{ $log->descripcion ?? '—' }}
                                </span>
                            </td>

                            {{-- Detalle --}}
                            <td class="px-3 py-3 text-center">
                                @if($log->datos_anteriores || $log->datos_nuevos)
                                    <button type="button"
                                            class="btn btn-sm interactive-btn"
                                            style="background:#eef2ff;color:#3730a3;border:none;border-radius:8px;padding:4px 10px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetalle"
                                            data-id="{{ $log->id_log }}"
                                            data-tipo="{{ $log->tipo_operacion }}"
                                            data-tabla="{{ $log->tabla_afectada }}"
                                            data-desc="{{ $log->descripcion }}"
                                            data-anteriores="{{ $log->datos_anteriores }}"
                                            data-nuevos="{{ $log->datos_nuevos }}">
                                        <i class="fas fa-eye" style="font-size:0.8rem;"></i>
                                    </button>
                                @else
                                    <span class="text-muted" style="font-size:0.75rem;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            {{-- Paginación --}}
            @if($logs->hasPages())
            <div class="d-flex align-items-center justify-content-between px-4 py-3 border-top bg-white">
                <div class="text-muted" style="font-size:0.82rem;">
                    Mostrando {{ $logs->firstItem() }}–{{ $logs->lastItem() }} de {{ $logs->total() }} registros
                </div>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="px-4 py-3 border-top text-muted" style="font-size:0.82rem;">
                {{ $logs->total() }} registro(s) encontrado(s)
            </div>
            @endif

            @endif
        </div>
    </div>
</div>

{{-- ======================== MODAL DETALLE ======================== --}}
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark mb-0" id="modalDetalleLabel">
                        <i class="fas fa-magnifying-glass me-2 text-primary"></i>
                        Detalle del registro
                    </h5>
                    <div class="text-muted mt-1" id="modalSubtitulo" style="font-size:0.82rem;"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="row g-3">
                    {{-- Datos anteriores --}}
                    <div class="col-12 col-md-6" id="colAnteriores">
                        <label class="fw-semibold text-muted small mb-1 d-block">
                            <i class="fas fa-clock-rotate-left me-1 text-warning"></i>Datos anteriores
                        </label>
                        <div id="preAnteriores"
                            class="rounded-3 p-2 mb-0"
                            style="
                                background:#1e293b;
                                border:1px solid #334155;
                                color:#fca5a5;
                                font-size:0.75rem;
                                line-height:1.2;
                                max-height:320px;
                                overflow-y:auto;
                                white-space:pre-wrap;
                        ">
                        </div>
                    </div>
                    {{-- Datos nuevos --}}
                    <div class="col-12 col-md-6" id="colNuevos">
                        <label class="fw-semibold text-muted small mb-1 d-block">
                            <i class="fas fa-file-circle-check me-1 text-success"></i>Datos nuevos
                        </label>
                        <div id="preNuevos"
                            class="rounded-3 p-2 mb-0"
                            style="
                                background:#1e293b;
                                border:1px solid #334155;
                                color:#86efac;
                                font-size:0.75rem;
                                line-height:1.2;
                                max-height:320px;
                                overflow-y:auto;
                                white-space:pre-wrap;
                        ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalDetalle');
    function renderJson(objActual, objComparacion, colorCambio) {

    let html = '';

    Object.keys(objActual).forEach(key => {

        const valor = objActual[key];
        const cambio =
            JSON.stringify(valor) !==
            JSON.stringify(objComparacion?.[key]);

      html += `
        <div style="
            padding:1px 4px;
            margin:0;
            line-height:1.1;
            border-left:${cambio ? '3px solid #22c55e' : '3px solid transparent'};
            background:${cambio ? colorCambio : 'transparent'};
        ">
            <span style="color:#94a3b8;font-weight:600;">${key}:</span>
            <span style="color:#e2e8f0;"> ${valor ?? '(vacío)'}</span>
        </div>
        `;
    });

    return html;
}
    modal.addEventListener('show.bs.modal', function (event) {
        const btn         = event.relatedTarget;
        const tipo        = btn.getAttribute('data-tipo');
        const tabla       = btn.getAttribute('data-tabla');
        const desc        = btn.getAttribute('data-desc');
        const anteriores  = btn.getAttribute('data-anteriores');
        const nuevos      = btn.getAttribute('data-nuevos');

        document.getElementById('modalSubtitulo').textContent =
            `[${tipo}] en tabla "${tabla}" — ${desc ?? ''}`;

        const fmt = (raw) => {
            if (!raw) return '(sin datos)';
            try { return JSON.stringify(JSON.parse(raw), null, 2); }
            catch { return raw; }
        };

        const preAnt  = document.getElementById('preAnteriores');
        const preNuev = document.getElementById('preNuevos');
        const colAnt  = document.getElementById('colAnteriores');
        const colNuev = document.getElementById('colNuevos');

    try {

        const objAnt = anteriores
            ? JSON.parse(anteriores)
            : null;

        const objNue = nuevos
            ? JSON.parse(nuevos)
            : null;

        if (objAnt && objNue) {

            preAnt.innerHTML =
                renderJson(
                    objAnt,
                    objNue,
                    'rgba(239,68,68,.18)'
                );

            preNuev.innerHTML =
                renderJson(
                    objNue,
                    objAnt,
                    'rgba(34,197,94,.18)'
                );
        }
        else {

            preAnt.innerHTML =
                objAnt
                    ? renderJson(objAnt, {}, 'rgba(239,68,68,.18)')
                    : '(sin datos)';

            preNuev.innerHTML =
                objNue
                    ? renderJson(objNue, {}, 'rgba(34,197,94,.18)')
                    : '(sin datos)';
        }

    } catch {

        preAnt.textContent = anteriores || '(sin datos)';
        preNuev.textContent = nuevos || '(sin datos)';
    }

        // Si solo hay un lado, ocupar todo el ancho
        if (!anteriores) { colAnt.classList.add('d-none'); colNuev.classList.replace('col-md-6','col-md-12'); }
        else             { colAnt.classList.remove('d-none'); colNuev.classList.replace('col-md-12','col-md-6'); }
    });
});
</script>
@endsection
