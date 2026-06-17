@extends('layouts.app')

@section('title', 'Feriados')
@section('page_title', 'Feriados')
@section('page_subtitle', 'Configuración · Calendario de feriados')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-light text-secondary mb-0">Recursos Humanos &rsaquo; Calendario de Feriados</h3>
        <a href="{{ route('rrhh.feriados.create') }}" class="btn btn-primary btn-sm interactive-btn">
            <i class="fas fa-plus me-1"></i> Nuevo Feriado
        </a>
    </div>
    <hr>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Departamento</th>
                    <th class="text-end">Recargo (%)</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feriados as $f)
                <tr>
                    <td>{{ $f->id_feriado }}</td>
                    <td class="fw-bold">{{ \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $f->nombre }}</td>
                    <td>
                        @php
                            $colorTipo = match($f->tipo) {
                                'nacional'       => 'en_ejecucion',
                                'departamental'  => 'pendiente',
                                'municipal'      => 'concluido',
                                default          => 'pendiente',
                            };
                        @endphp
                        <span class="badge badge-status badge-{{ $colorTipo }} text-dark">{{ ucfirst($f->tipo) }}</span>
                    </td>
                    <td>{{ $f->departamento ?? 'Nacional' }}</td>
                    <td class="text-end fw-bold text-danger">{{ number_format($f->recargo_pct, 2) }}%</td>
                    <td class="text-center">
                        <a href="{{ route('rrhh.feriados.edit', $f->id_feriado) }}"
                           class="btn btn-sm btn-outline-secondary interactive-btn" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('rrhh.feriados.destroy', $f->id_feriado) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('¿Eliminar el feriado {{ $f->nombre }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger interactive-btn" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No hay feriados registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $feriados->links() }}
        </div>
    </div></div>
@endsection