@extends('layouts.app')
@section('content')
<div class="container-fluid" style="max-width:800px">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">Gestión Operativa &rsaquo; Detalles del Cliente</h4>
        <div>
            <a href="{{ route('operativa.clientes.edit', $cliente->id_cliente) }}" class="btn btn-edit btn-sm"><i class="fas fa-pen me-1"></i> Editar</a>
            <a href="{{ route('operativa.clientes.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Volver</a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3"><i class="fas fa-user-tie me-2"></i> {{ $cliente->nombre_razon }}</h5>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Tipo:</div>
                <div class="col-md-8">{{ ucfirst($cliente->tipo_cliente) }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Documento / NIT:</div>
                <div class="col-md-8">{{ $cliente->documento_identidad }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Estado:</div>
                <div class="col-md-8">
                    @php $badge = match($cliente->estado) { 'activo' => 'success', 'moroso' => 'danger', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst($cliente->estado) }}</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Teléfono Principal:</div>
                <div class="col-md-8">{{ $cliente->telefono_principal }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Teléfono Secundario:</div>
                <div class="col-md-8">{{ $cliente->telefono_secundario ?? '—' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Correo:</div>
                <div class="col-md-8">{{ $cliente->correo }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Dirección:</div>
                <div class="col-md-8">{{ $cliente->direccion }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4 text-muted fw-bold">Fecha Registro:</div>
                <div class="col-md-8">{{ $cliente->fecha_registro }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
