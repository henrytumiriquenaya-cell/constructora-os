@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Asignaciones de Maquinaria</h4>
    <a href="{{ route('operativa.maquinarias.asignaciones_create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Nueva asignación
    </a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Maquinaria</th>
            <th>Proyecto</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Horas Asignadas</th>
            <th>Costo Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asignaciones as $asig)
        <tr>
            <td>{{ $asig->id_asig_maq }}</td>
            <td>{{ $asig->maquinaria->nombre ?? 'N/A' }}</td>
            <td>{{ $asig->proyecto->nombre_proyecto ?? 'N/A' }}</td>
            <td>{{ $asig->fecha_inicio }}</td>
            <td>{{ $asig->fecha_fin ?? '-' }}</td>
            <td>{{ $asig->horas_asignadas }}</td>
            <td>{{ $asig->costo_total ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection