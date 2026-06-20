@extends('layouts.app')


@section('title', 'Pagos y Planillas')
@section('page_title', 'Pagos y Planillas')
@section('page_subtitle', 'Recursos Humanos · Control de pagos')

@section('content')
    <h3 class="fw-light text-secondary">Recursos Humanos > Registro de Pagos a Empleados</h3>
    <hr>
    <form method="GET" class="row g-2 mb-3">

        <div class="col-md-4">
            <select name="cargo" class="form-select">
                <option value="">Todos los cargos</option>

                @foreach($cargos as $cargo)
                    <option value="{{ $cargo }}"
                        {{ request('cargo') == $cargo ? 'selected' : '' }}>
                        {{ $cargo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="periodo_mes" class="form-select">
                <option value="">Todos los periodos</option>

                @foreach($periodos as $periodo)
                    <option value="{{ $periodo }}"
                        {{ request('periodo_mes') == $periodo ? 'selected' : '' }}>
                        {{ $periodo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                Filtrar
            </button>
        </div>

        <div class="col-auto">
            <a href="{{ url()->current() }}" class="btn btn-secondary">
                Limpiar
            </a>
        </div>

    </form>
    <div class="table-wrapper"><div class="table-responsive">
        <table class="table table-bordered table-hover table-sm align-middle small">
            <thead class="table-head-premium">
                <tr>
                    <th>ID Pago Emp</th>
                    <th>ID Maestro</th>
                    <th>Empleado</th>
                    <th>Tipo Haber</th>
                    <th>Periodo (Mes)</th>
                    <th>Días Trab.</th>
                    <th>Horas Trab.</th>
                    <th>Modalidad</th>
                    <th>Tarifa Aplicada</th>
                    <th>Monto Calculado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagos as $p)
                <tr>
                    <td>{{ $p->id_pago_emp }}</td>
                    <td>{{ $p->id_pago }}</td>
                    <td>
                        <div class="fw-semibold">
                            {{ $p->empleado->nombres ?? 'ID: '.$p->id_empleado }}
                            {{ $p->empleado->apellidos ?? '' }}
                        </div>

                        <small class="text-muted">
                            {{ $p->empleado->cargo ?? 'Sin cargo' }}
                        </small>
                    </td>
                    <td>{{ $p->tipo_haber }}</td>
                    <td class="text-center">{{ $p->periodo_mes }}</td>
                    <td class="text-center">{{ $p->dias_trabajados }}</td>
                    <td class="text-center">{{ $p->horas_trabajadas }}</td>
                    <td>{{ $p->modalidad_aplicada }}</td>
                    <td class="text-end">{{ number_format($p->tarifa_aplicada, 2) }}</td>
                    <td class="text-end fw-bold text-primary">
                        {{ number_format($p->monto_calculado, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $pagos->links() }}
    </div></div></div>
@endsection