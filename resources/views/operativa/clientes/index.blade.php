@extends('layouts.app')

@section('title', 'Clientes')
@section('page_title', 'Clientes')
@section('page_subtitle', 'Gestión Operativa · Lista de clientes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Clientes</h4>
        <a href="{{ route('operativa.clientes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Nuevo Cliente
        </a>
    </div>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th><th>Tipo</th><th>Nombre / Razón Social</th>
                    <th>Doc. Identidad</th><th>Teléfono</th><th>Correo</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($clientes as $c)
                <tr>
                    <td>{{ $c->id_cliente }}</td>
                    <td><span class="badge badge-status">{{ ucfirst($c->tipo_cliente) }}</span></td>
                    <td>{{ $c->nombre_razon }}</td>
                    <td>{{ $c->documento_identidad }}</td>
                    <td>{{ $c->telefono_principal }}</td>
                    <td>{{ $c->correo }}</td>
                    <td>
                        @php $badge = match($c->estado) { 'activo' => 'success', 'moroso' => 'danger', default => 'secondary' }; @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($c->estado) }}</span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('operativa.clientes.edit', $c->id_cliente) }}" class="btn btn-edit btn-sm interactive-btn"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('operativa.clientes.destroy', $c->id_cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar cliente?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-delete btn-sm interactive-btn"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Sin clientes registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $clientes->links() }}
    </div></div></div>
@endsection