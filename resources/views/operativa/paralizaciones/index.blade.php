@extends('layouts.app')

@section('title', 'Paralizaciones')
@section('page_title', 'Paralizaciones')
@section('page_subtitle', 'Gestión Operativa · Obras paralizadas')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Paralizaciones de Obra</h4>
        <a href="{{ route('operativa.paralizaciones.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Nueva Paralización</a>
    </div>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Proyecto</th><th>Motivo</th><th>Inicio</th><th>Fin</th><th>Estado</th><th>Registrado por</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            @forelse($paralizaciones as $p)
                @php $badge = match($p->estado ?? '') { 'activa' => 'danger', 'levantada' => 'success', default => 'warning' }; @endphp
                <tr>
                    <td>{{ $p->id_paralizacion }}</td>
                    <td>{{ $p->proyecto->nombre_proyecto ?? '—' }}</td>
                    <td>{{ $p->motivo }}</td>
                    <td>{{ $p->fecha_inicio_par }}</td>
                    <td>{{ $p->fecha_fin_par ?? '—' }}</td>
                    <td><span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ',$p->estado ?? '')) }}</span></td>
                    <td>{{ $p->registrado_por ?? '—' }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('operativa.paralizaciones.edit', $p->id_paralizacion) }}" class="btn btn-edit btn-sm interactive-btn"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('operativa.paralizaciones.destroy', $p->id_paralizacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar paralización?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm interactive-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Sin paralizaciones registradas.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $paralizaciones->links() }}
    </div></div></div>
@endsection