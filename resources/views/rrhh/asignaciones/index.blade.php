@extends('layouts.app')


@section('title', 'Asignaciones')
@section('page_title', 'Asignaciones')
@section('page_subtitle', 'Recursos Humanos · Asignación de personal')

@section('content')
    <h3 class="fw-light text-secondary">Recursos Humanos > Asignación de Personal</h3>
    <hr>
    
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle small">
            <thead class="table-head-premium">
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Proyecto</th>
                    <th>Rol en Proyecto</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Horas/Semana</th>
                    <th>Tarifa Hora</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $a)
                <tr>
                    <td>{{ $a->id_asignacion }}</td>
                    <td class="fw-bold text-uppercase">
                        {{ $a->empleado->nombres ?? 'ID: '.$a->id_empleado }} {{ $a->empleado->apellidos ?? '' }}
                    </td>
                    <td>{{ $a->proyecto->nombre_proyecto ?? 'ID: '.$a->id_proyecto }}</td>
                    <td>{{ $a->rol_en_proyecto }}</td>
                    <td>{{ $a->fecha_inicio_asig }}</td>
                    <td>{{ $a->fecha_fin_asig ?? 'Activo' }}</td>
                    <td class="text-center">{{ $a->horas_semana }}</td>
                    <td class="text-end text-success fw-bold">{{ number_format($a->tarifa_hora, 2) }}</td>
                    <td><small class="text-muted">{{ $a->observaciones }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $asignaciones->links() }}
    </div></div></div>
@endsection