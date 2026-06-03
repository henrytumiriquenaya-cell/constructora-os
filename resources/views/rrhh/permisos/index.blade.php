@extends('layouts.app')

@section('title', 'Permisos y Trámites')
@section('page_title', 'Permisos y Trámites')
@section('page_subtitle', 'Recursos Humanos · Solicitudes de permiso')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Recursos Humanos &rsaquo; Permisos y Feriados</h4>
        <a href="{{ route('rrhh.permisos.create') ?? '#' }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Nuevo permiso
        </a>
    </div>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th><th>Tipo</th><th>Entidad</th><th>F. Solicitud</th><th>Costo</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permisos as $p)
                <tr>
                    <td>{{ $p->id_permiso }}</td>
                    <td>
                        @php
                            $tipo = strtolower($p->tipo_permiso ?? '');
                            $badge = match(true) {
                                str_contains($tipo, 'municipal') => 'badge-municipal',
                                str_contains($tipo, 'ambiental') => 'badge-ambiental',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge badge-status {{ $badge }}">{{ ucfirst($p->tipo_permiso) }}</span>
                    </td>
                    <td>{{ $p->entidad_emisora }}</td>
                    <td>{{ $p->fecha_solicitud }}</td>
                    <td>{{ number_format($p->costo_tramite, 2) }}</td>
                    <td>
                        @php
                            $estado = strtolower($p->estado ?? '');
                            $badgeEst = match($estado) {
                                'aprobado' => 'badge-aprobado',
                                'pendiente' => 'badge-pendiente',
                                'rechazado' => 'badge-rechazado',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge badge-status {{ $badgeEst }}">{{ ucfirst($p->estado) }}</span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('rrhh.permisos.edit', $p->id_permiso) }}" class="btn btn-edit btn-sm interactive-btn"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('rrhh.permisos.destroy', $p->id_permiso) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar permiso?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-delete btn-sm interactive-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">Sin permisos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $permisos->links() }}
    </div></div></div>

@endsection