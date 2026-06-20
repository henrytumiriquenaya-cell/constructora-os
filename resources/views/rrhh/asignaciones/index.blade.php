@extends('layouts.app')

@section('title', 'Asignaciones')
@section('page_title', 'Asignaciones')
@section('page_subtitle', 'Recursos Humanos · Asignación de personal')

@section('content')

<h3 class="fw-light text-secondary">
    Recursos Humanos > Asignación de Personal
</h3>

<hr>

<div class="d-flex justify-content-between mb-3">
    <h5 class="fw-light">Asignaciones de Personal</h5>

    <button
        class="btn btn-primary btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#modalAsignar">
        + Asignar empleado
    </button>
</div>

<div class="table-wrapper">
    <div class="table-responsive">

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
                        {{ $a->empleado->nombres ?? 'ID: '.$a->id_empleado }}
                        {{ $a->empleado->apellidos ?? '' }}
                    </td>

                    <td>
                        {{ $a->proyecto->nombre_proyecto ?? 'ID: '.$a->id_proyecto }}
                    </td>

                    <td>{{ $a->rol_en_proyecto }}</td>
                    <td>{{ $a->fecha_inicio_asig }}</td>
                    <td>{{ $a->fecha_fin_asig ?? 'Activo' }}</td>

                    <td class="text-center">
                        {{ $a->horas_semana }}
                    </td>

                    <td class="text-end text-success fw-bold">
                        {{ number_format($a->tarifa_hora, 2) }}
                    </td>

                    <td>
                        <small class="text-muted">
                            {{ $a->observaciones }}
                        </small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $asignaciones->links() }}
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalAsignar" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form method="POST"
              action="{{ route('asignaciones.store') }}"
              class="modal-content">

            @csrf

            <div class="modal-header">
                <h5 class="modal-title">
                    Asignar Empleado a Proyecto
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Empleado
                    </label>

                    <select name="id_empleado"
                            class="form-select"
                            required>

                        <option value="">
                            Seleccione un empleado
                        </option>

                        @foreach($empleados as $e)
                            <option value="{{ $e->id_empleado }}">
                                {{ $e->nombres }} {{ $e->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Proyecto
                    </label>

                    <select name="id_proyecto"
                            class="form-select"
                            required>

                        <option value="">
                            Seleccione un proyecto
                        </option>

                        @foreach($proyectos as $p)
                            <option value="{{ $p->id_proyecto }}">
                                {{ $p->nombre_proyecto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Rol en el proyecto
                    </label>

                    <input type="text"
                           name="rol_en_proyecto"
                           class="form-control"
                           placeholder="Ej: Ingeniero, Albañil, Supervisor"
                           required>
                </div>

                <div class="row mb-3">

                    <div class="col">
                        <label class="form-label fw-semibold">
                            Horas por semana
                        </label>

                        <input type="number"
                               name="horas_semana"
                               class="form-control"
                               step="0.1"
                               placeholder="Ej: 40">
                    </div>

                    <div class="col">
                        <label class="form-label fw-semibold">
                            Tarifa por hora
                        </label>

                        <input type="number"
                               name="tarifa_hora"
                               class="form-control"
                               step="0.01"
                               placeholder="Ej: 25.00">
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Fecha de inicio
                    </label>

                    <input type="date"
                           name="fecha_inicio_asig"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Fecha de fin
                    </label>

                    <input type="date"
                           name="fecha_fin_asig"
                           class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="form-control"
                              rows="2"
                              placeholder="Opcional"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit"
                        class="btn btn-primary">
                    Guardar
                </button>
            </div>

        </form>

    </div>
</div>

@endsection