@extends('layouts.app')

@section('title', 'Alertas')
@section('page_title', 'Alertas')
@section('page_subtitle', 'Sistema · Notificaciones y alertas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-light text-secondary mb-0">
                <i class="fas fa-bell me-2"></i>Alertas y Notificaciones
            </h3>
            <small class="text-muted">
                @if($noLeidasCount > 0)
                    <span class="badge badge-status badge-cancelado">{{ $noLeidasCount }} no leída{{ $noLeidasCount !== 1 ? 's' : '' }}</span>
                @else
                    Todas leídas
                @endif
            </small>
        </div>
        @if($noLeidasCount > 0)
            <a href="{{ route('reportes.alertas.marcar-todas') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-check-double me-1"></i> Marcar todas como leídas
            </a>
        @endif
    </div>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4 p-3">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-uppercase fw-semibold text-muted">Estado</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="estado" id="todos" value=""
                        {{ !request('estado') ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="todos">Todas</label>

                    <input type="radio" class="btn-check" name="estado" id="no_leidas" value="no_leidas"
                        {{ request('estado') === 'no_leidas' ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="no_leidas">No leídas</label>

                    <input type="radio" class="btn-check" name="estado" id="leidas" value="leidas"
                        {{ request('estado') === 'leidas' ? 'checked' : '' }}>
                    <label class="btn btn-outline-secondary" for="leidas">Leídas</label>
                </div>
            </div>

            <div class="col-md-5">
                <label class="form-label small text-uppercase fw-semibold text-muted">Tipo de Notificación</label>
                <select name="tipo" class="form-select form-select-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($tiposPermitidos as $tipo)
                        <option value="{{ $tipo }}" {{ request('tipo') === $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 btn-sm">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    {{-- Lista de notificaciones --}}
    @if($notificaciones->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block opacity-50"></i>
            <h5 class="text-muted">No hay notificaciones</h5>
            <p class="text-muted small">Aquí aparecerán tus alertas cuando haya actividad importante</p>
        </div>
    @else
        <div class="list-group">
            @foreach($notificaciones as $notif)
                <div class="list-group-item list-group-item-action p-3 {{ !$notif->leida ? 'bg-light border-start border-4 border-primary' : '' }}"
                     style="cursor: pointer;">
                    <div class="d-flex w-100 align-items-start gap-3">
                        {{-- Icono del tipo --}}
                        <div class="flex-shrink-0">
                            @php
                                $iconos = [
                                    'stock_bajo' => ['icon' => 'fas fa-exclamation-triangle', 'color' => 'warning'],
                                    'compra_realizada' => ['icon' => 'fas fa-shopping-cart', 'color' => 'success'],
                                    'pago_vencido' => ['icon' => 'fas fa-clock', 'color' => 'danger'],
                                    'proyecto_atrasado' => ['icon' => 'fas fa-exclamation-circle', 'color' => 'danger'],
                                    'asignacion_cambio' => ['icon' => 'fas fa-user-tie', 'color' => 'info'],
                                    'permiso_solicitado' => ['icon' => 'fas fa-file-check', 'color' => 'primary'],
                                    'mantenimiento_equipo' => ['icon' => 'fas fa-wrench', 'color' => 'secondary'],
                                    'obra_completada' => ['icon' => 'fas fa-flag-checkered', 'color' => 'success'],
                                ];
                                $config = $iconos[$notif->tipo_notificacion] ?? ['icon' => 'fas fa-bell', 'color' => 'primary'];
                            @endphp
                            <i class="{{ $config['icon'] }} fa-lg text-{{ $config['color'] }}"></i>
                        </div>

                        {{-- Contenido principal --}}
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 {{ !$notif->leida ? 'fw-bold' : '' }}">
                                    {{ $notif->asunto }}
                                </h6>
                                <small class="text-muted text-nowrap ms-2">
                                    {{ $notif->fecha_creacion ? \Carbon\Carbon::parse($notif->fecha_creacion)->diffForHumans() : 'Sin fecha' }}
                                </small>
                            </div>

                            <p class="mb-2 text-dark {{ !$notif->leida ? 'fw-500' : 'text-muted' }}">
                                {{ Str::limit($notif->mensaje, 100) }}
                            </p>

                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge bg-{{ $config['color'] }} bg-opacity-10 text-{{ $config['color'] }}">
                                    {{ $notif->categoria }}
                                </span>

                                @if($notif->leida)
                                    <small class="text-muted">
                                        Recibida {{ $notif->fecha_creacion ? \Carbon\Carbon::parse($notif->fecha_creacion)->diffForHumans() : 'Sin fecha' }}
                                    </small>

                                @else
                                    <span class="badge badge-status badge-cancelado-subtle text-danger">Nueva</span>
                                @endif
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex-shrink-0 dropdown">
                            <button class="btn btn-sm btn-ghost dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(!$notif->leida)
                                    <li>
                                        <form action="{{ route('reportes.alertas.marcar-leida', $notif->id_notificacion) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check me-2"></i> Marcar como leída
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('reportes.alertas.eliminar', $notif->id_notificacion) }}"
                                          method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item"
                                                onclick="return confirm('¿Eliminar esta notificación?')">
                                            <i class="fas fa-trash me-2"></i> Eliminar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $notificaciones->links() }}
        </div>
    @endif
</div>

<style>
    .btn-ghost {
        background: transparent;
        border: none;
        color: #6c757d;
        padding: 0.25rem 0.5rem;
    }
    .btn-ghost:hover {
        background: #e9ecef;
        color: #495057;
    }
</style>
@endsection
