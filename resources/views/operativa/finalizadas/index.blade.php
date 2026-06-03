@extends('layouts.app')


@section('title', 'Obras Terminadas')
@section('page_title', 'Obras Terminadas')
@section('page_subtitle', 'Gestión Operativa · Registro de obras concluidas')

@section('content')
    <h3 class="mt-4 fw-light text-secondary">Histórico de Cierres de Obra</h3>
    <hr>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle">
            <thead class="table-head-premium">
                <tr class="text-nowrap">
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Fecha Cierre Real</th>
                    <th>Nro Acta Entrega</th>
                    <th>Monto Cierre Final</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($obras as $o)
                <tr>
                    <td>{{ $o->id_terminada }}</td>
                    <td class="fw-bold">{{ $o->proyecto->nombre_proyecto ?? 'N/A' }}</td>
                    <td>{{ $o->fecha_terminacion_real }}</td>
                    <td>{{ $o->numero_acta }}</td>
                    <td>{{ number_format($o->monto_final, 2) }}</td>
                    <td><small>{{ $o->observaciones }}</small></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $obras->links() }}
    </div></div></div>
@endsection