@extends('layouts.app')

@section('title', 'Proyectos')
@section('page_title', 'Proyectos')
@section('page_subtitle', 'Gestión Operativa · Lista de proyectos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Proyectos</h4>
        <a href="{{ route('operativa.proyectos.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Nuevo Proyecto</a>
    </div>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Código</th><th>Nombre</th><th>Contrato / Cliente</th><th>Estado</th><th>Avance</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            @forelse($proyectos as $p)
                @php $badge = match($p->estado ?? '') { 'en_ejecucion' => 'primary', 'concluido' => 'success', 'cancelado' => 'danger', 'pausado' => 'warning', default => 'secondary' }; @endphp
                <tr>
                    <td>{{ $p->id_proyecto }}</td>
                    <td><code>{{ $p->codigo_proyecto ?? '—' }}</code></td>
                    <td>{{ $p->nombre_proyecto }}</td>
                    <td>
                        @if($p->contrato)
                            <div><code>{{ $p->contrato->numero_contrato }}</code></div>
                            <div class="text-muted">{{ $p->contrato->cliente->nombre_razon ?? '—' }}</div>
                        @else —
                        @endif
                    </td>
                    <td><span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ',$p->estado ?? '')) }}</span></td>
                    <td>
                        <div class="progress" style="height:14px;min-width:80px">
                            <div class="progress-bar" style="width:{{ $p->porcentaje_avance ?? 0 }}%">{{ $p->porcentaje_avance ?? 0 }}%</div>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('operativa.proyectos.show', $p->id_proyecto) }}" class="btn btn-info btn-sm interactive-btn"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('operativa.proyectos.edit', $p->id_proyecto) }}" class="btn btn-edit btn-sm interactive-btn"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('operativa.proyectos.destroy', $p->id_proyecto) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar proyecto?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm interactive-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Sin proyectos registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $proyectos->links() }}
    </div></div></div>
@endsection