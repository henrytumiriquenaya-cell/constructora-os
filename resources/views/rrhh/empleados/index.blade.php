@extends('layouts.app')

@section('title', 'Empleados')
@section('page_title', 'Empleados')
@section('page_subtitle', 'Recursos Humanos · Lista de empleados')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">RRHH &rsaquo; Empleados</h4>
        <a href="{{ route('rrhh.empleados.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Nuevo Empleado</a>
    </div>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr><th>ID</th><th>CI</th><th>Nombre completo</th><th>Cargo</th><th>Modalidad</th><th>Tipo contrato</th><th>Ingreso</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            @forelse($empleados as $e)
                <tr>
                    <td>{{ $e->id_empleado }}</td>
                    <td>{{ $e->ci }}</td>
                    <td>{{ $e->nombres }} {{ $e->apellidos }}</td>
                    <td>{{ $e->cargo }}</td>
                    <td><span class="badge badge-status">{{ $e->modalidad_pago }}</span></td>
                    <td>{{ $e->tipo_contrato }}</td>
                    <td>{{ $e->fecha_ingreso }}</td>
                    <td>
                        <span class="badge bg-{{ $e->activo ? 'success' : 'secondary' }}">{{ $e->activo ? 'Activo' : 'Baja' }}</span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('rrhh.empleados.edit', $e->id_empleado) }}" class="btn btn-edit btn-sm interactive-btn"><i class="fas fa-pen"></i></a>
                        <form action="{{ route('rrhh.empleados.destroy', $e->id_empleado) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Dar de baja al empleado?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm interactive-btn" title="Dar de baja"><i class="fas fa-user-slash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center text-muted">Sin empleados registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $empleados->links() }}
    </div></div></div>
@endsection
