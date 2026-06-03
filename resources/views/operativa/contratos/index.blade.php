@extends('layouts.app')

@section('title', 'Contratos')
@section('page_title', 'Contratos')
@section('page_subtitle', 'Gestión Operativa · Lista de contratos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Contratos</h4>
        <a href="{{ route('operativa.contratos.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i>Nuevo Contrato
        </a>
    </div>
    <hr>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Nro. Contrato</th><th>Cliente</th><th>Monto</th><th>Moneda</th><th>Tipo</th><th>Estado</th><th>Firma</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($contratos as $c)
                <tr>
                    <td>{{ $c->id_contrato }}</td>
                    <td class="fw-bold"><code>{{ $c->numero_contrato }}</code></td>
                    <td>{{ $c->cliente->nombre_razon ?? '—' }}</td>
                    <td class="text-end">{{ number_format($c->monto_total, 2) }}</td>
                    <td>{{ $c->moneda }}</td>
                    <td>{{ str_replace('_',' ',$c->tipo_contrato) }}</td>
                    <td>
                        @php $col=['borrador'=>'secondary','firmado'=>'primary','en_ejecucion'=>'success','concluido'=>'dark','rescindido'=>'danger'][$c->estado] ?? 'secondary' @endphp
                        <span class="badge bg-{{ $col }}">{{ str_replace('_',' ',$c->estado) }}</span>
                    </td>
                    <td>{{ $c->fecha_firma }}</td>
                    <td>
                        <a href="{{ route('operativa.contratos.show', $c->id_contrato) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('operativa.contratos.edit', $c->id_contrato) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('operativa.contratos.destroy', $c->id_contrato) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar contrato?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted">Sin contratos registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $contratos->links() }}
    </div></div></div>
@endsection