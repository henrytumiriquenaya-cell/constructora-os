@extends('layouts.app')

@section('title', 'Inventario')
@section('page_title', 'Inventario')
@section('page_subtitle', 'Gestión Operativa · Stock de materiales')

@section('content')

{{-- ── Encabezado ─────────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0 page-heading">
            <i class="ti ti-package me-2" style="color:var(--indigo);"></i>
            Inventario Central
        </h4>
        <small class="text-muted-dm">Stock actual de materiales en almacén central.</small>
    </div>

    {{-- Botón de sincronización — solo visible para admin --}}
    @if(Auth::user()->hasRole('admin'))
    <form action="{{ route('operativa.inventario.recalcular') }}" method="POST"
          onsubmit="return confirm('¿Recalcular el inventario desde los movimientos reales?\nEsto corregirá cualquier desincronización.')">
        @csrf
        <button type="submit" class="btn btn-outline-warning btn-sm">
            <i class="ti ti-refresh me-1"></i> Sincronizar inventario
        </button>
    </form>
    @endif
</div>

{{-- ── Alertas de sesión ───────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Tarjetas de inventario ──────────────────────────────────────────────── --}}
<div class="row g-3">
    @forelse($inventario as $item)
        @php
            $semaforo    = $item->semaforo ?? 'verde';
            $borderColor = match($semaforo) {
                'rojo'     => 'danger',
                'amarillo' => 'warning',
                default    => 'success',
            };
            $badgeColor  = match($semaforo) {
                'rojo'     => 'danger',
                'amarillo' => 'warning text-dark',
                default    => 'success',
            };
            $badgeText   = match($semaforo) {
                'rojo'     => 'CRÍTICO',
                'amarillo' => 'BAJO',
                default    => 'OK',
            };
        @endphp

        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 shadow-sm interactive-card border-{{ $borderColor }}">
                <div class="card-body">

                    {{-- Nombre del material y badge de estado --}}
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1 fw-semibold" style="color:var(--text-primary);">
                                {{ $item->material ?? 'Material' }}
                            </h6>
                            <div class="small text-muted-dm">Almacén Central</div>
                        </div>
                        <span class="badge bg-{{ $badgeColor }}">{{ $badgeText }}</span>
                    </div>

                    <hr class="my-2 border-secondary">

                    {{-- Datos de stock --}}
                    <div class="small">
                        <div>
                            <strong>Disponible:</strong>
                            {{ number_format((float)($item->cantidad_disponible ?? 0), 2) }}
                            {{ $item->unidad_medida ?? '' }}
                        </div>
                        <div>
                            <strong>Reservada:</strong>
                            {{ number_format((float)($item->cantidad_reservada ?? 0), 2) }}
                        </div>
                        <div>
                            <strong>Mínimo:</strong>
                            {{ number_format((float)($item->stock_minimo ?? 0), 2) }}
                        </div>
                    </div>

                    {{-- Fecha de actualización --}}
                    <div class="mt-3">
                        <small class="text-muted-dm">
                            Actualizado:
                            @if($item->fecha_ultima_actualizacion)
                                {{ \Carbon\Carbon::parse($item->fecha_ultima_actualizacion)->format('d/m/Y H:i') }}
                            @else
                                N/D
                            @endif
                        </small>
                    </div>

                </div>
            </div>
        </div>

    @empty
        <div class="col-12">
            <div class="alert alert-secondary d-flex align-items-center gap-2">
                <i class="ti ti-mood-empty" style="font-size:1.5rem;"></i>
                No hay datos de inventario.
            </div>
        </div>
    @endforelse
</div>

{{-- ── Paginación ──────────────────────────────────────────────────────────── --}}
@if(method_exists($inventario, 'links'))
<div class="d-flex justify-content-center mt-4">
    {{ $inventario->links() }}
</div>
@endif

@endsection