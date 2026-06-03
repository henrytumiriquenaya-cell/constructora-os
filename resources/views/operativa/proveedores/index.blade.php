@extends('layouts.app')

@section('title', 'Proveedores')
@section('page_title', 'Proveedores')
@section('page_subtitle', 'Gestión Operativa · Lista de proveedores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Proveedores</h4>
        <a href="{{ route('operativa.proveedores.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Nuevo Proveedor</a>
    </div>
    <hr>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>Razón Social</th><th>NIT</th><th>Categoría</th><th>Ciudad</th><th>Teléfono</th><th>Correo</th><th>Calif.</th><th>Activo</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($proveedores as $p)
                <tr>
                    <td>{{ $p->id_proveedor }}</td>
                    <td class="fw-bold text-uppercase">{{ $p->razon_social }}</td>
                    <td>{{ $p->nit ?? '—' }}</td>
                    <td>{{ $p->categoria }}</td>
                    <td>{{ $p->ciudad->nombre ?? '—' }}</td>
                    <td>{{ $p->telefono }}</td>
                    <td>{{ $p->correo }}</td>
                    <td class="text-center">{{ $p->calificacion ?? '—' }}</td>
                    <td>
                        @if($p->activo == 1)
                            Activo
                        @else
                            Inactivo
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('operativa.proveedores.edit', $p->id_proveedor) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('operativa.proveedores.destroy', $p->id_proveedor) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted">Sin proveedores registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $proveedores->links() }}
    </div></div></div>
@endsection