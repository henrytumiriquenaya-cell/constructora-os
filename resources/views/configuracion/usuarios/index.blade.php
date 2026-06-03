@extends('layouts.app')

@section('title', 'Usuarios')
@section('page_title', 'Usuarios')
@section('page_subtitle', 'Configuración · Gestión de accesos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Configuración &rsaquo; Gestión de Usuarios</h4>
        <a href="{{ route('configuracion.usuarios.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus me-1"></i> Nuevo Usuario
        </a>
    </div>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $estado === 'activos' ? 'active fw-bold' : '' }}" href="{{ route('configuracion.usuarios.index', ['estado' => 'activos']) }}">
                Usuarios Activos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $estado === 'inactivos' ? 'active fw-bold text-danger' : 'text-danger' }}" href="{{ route('configuracion.usuarios.index', ['estado' => 'inactivos']) }}">
                Usuarios Inactivos
            </a>
        </li>
    </ul>

    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle table-interactive">
            <thead class="table-head-premium">
                <tr>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($usuarios as $u)
                <tr>
                    <td class="fw-bold">{{ $u->usuario }}</td>
                    <td>{{ $u->nombre_completo ?? $u->nombre_usuario }}</td>
                    <td>{{ $u->correo }}</td>
                    <td><span class="badge badge-status badge-en_ejecucion text-dark">{{ strtoupper($u->rol) }}</span></td>
                    <td>
                        @if($estado === 'activos')
                            <span class="badge badge-status badge-concluido">Activo</span>
                        @else
                            <span class="badge badge-status badge-cancelado">Inactivo</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('configuracion.usuarios.edit', $u->id_usuario) }}" class="btn btn-edit btn-sm interactive-btn" title="Editar"><i class="fas fa-pen"></i></a>
                        @if($estado === 'activos')
                            @if(auth()->id() != $u->id_usuario)
                                <form action="{{ route('configuracion.usuarios.destroy', $u->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desactivar este usuario?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm interactive-btn" title="Desactivar"><i class="fas fa-user-slash"></i></button>
                                </form>
                            @endif
                        @else
                            <form action="{{ route('configuracion.usuarios.restaurar', $u->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Reactivar este usuario?')">
                                @csrf
                                <button class="btn btn-success btn-sm interactive-btn" title="Reactivar"><i class="fas fa-user-check"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No hay usuarios en esta categoría.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $usuarios->links() }}
        </div></div></div>
@endsection
