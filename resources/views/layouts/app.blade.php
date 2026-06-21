<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestión') — Constructora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/premium.css?v=' . (time() + 200)) }}">
    <script>
    (function() {
        const saved = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', saved);
    })();
    </script>
</head>
<body>
<div style="display:flex; min-height:100vh;">

    {{-- ════════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════════ --}}
        {{-- ════════════════════════════════════════════════════
         SIDEBAR
    ════════════════════════════════════════════════════ --}}
    <nav class="sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand d-flex align-items-center gap-3">
            <div class="brand-icon">C</div>
            <div>
                <div class="brand-name">CONSTRUCTORA</div>
                <div class="brand-sub">Gestión Integral</div>
            </div>
        </div>

        {{-- User --}}
        @auth
        <div class="sidebar-user d-flex align-items-center gap-2 mt-2">
            <div class="user-avatar">
                {{ strtoupper(substr($authUser->nombreParaMostrar(), 0, 2)) }}
            </div>
            <div>
                <div class="user-name text-truncate" style="max-width:135px;">{{ $authUser->nombreParaMostrar() }}</div>
                <div class="user-role">{{ Auth::user()->roles->pluck('nombre')->join(', ') ?: 'Sin rol' }}</div>
            </div>
        </div>
        @endauth

        {{-- Navigation Accordion --}}
        <div class="flex-grow-1 pb-3" style="overflow-y:auto; overflow-x:hidden;" id="sidebarAccordion">

            {{-- INICIO --}}
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Inicio</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active-link' : '' }}">
                        <i class="ti ti-layout-dashboard"></i> Panel de control
                    </a>
                </div>
            </div>

            {{-- MAESTROS --}}
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Maestros</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist','rrhh']) && \Illuminate\Support\Facades\Route::has('operativa.ciudades.index'))
                        <a href="{{ route('operativa.ciudades.index') }}" class="nav-link {{ request()->routeIs('operativa.ciudades.*') ? 'active-link' : '' }}"><i class="ti ti-map-pin"></i> Ciudades</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.maquinarias.catalogo'))
                        <a href="{{ route('operativa.maquinarias.catalogo') }}" class="nav-link {{ request()->routeIs('operativa.maquinarias.catalogo*') ? 'active-link' : '' }}"><i class="ti ti-crane"></i> Cat. maquinaria</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.materiales.index'))
                        <a href="{{ route('operativa.materiales.index') }}" class="nav-link {{ request()->routeIs('operativa.materiales.*') ? 'active-link' : '' }}"><i class="ti ti-box-seam"></i> Materiales</a>
                    @endif
                </div>
            </div>

            {{-- GESTIÓN OPERATIVA --}}
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Gestión Operativa</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    @if(Auth::user()->hasRole(['admin','gerente','contab']) && \Illuminate\Support\Facades\Route::has('operativa.clientes.index'))
                        <a href="{{ route('operativa.clientes.index') }}" class="nav-link {{ request()->routeIs('operativa.clientes.*') ? 'active-link' : '' }}"><i class="ti ti-users"></i> Clientes</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','cliente']) && \Illuminate\Support\Facades\Route::has('operativa.contratos.index'))
                        <a href="{{ route('operativa.contratos.index') }}" class="nav-link {{ request()->routeIs('operativa.contratos.*') ? 'active-link' : '' }}"><i class="ti ti-file-text"></i> Contratos</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist','rrhh','cliente']) && \Illuminate\Support\Facades\Route::has('operativa.proyectos.index'))
                        <a href="{{ route('operativa.proyectos.index') }}" class="nav-link {{ request()->routeIs('operativa.proyectos.*') ? 'active-link' : '' }}"><i class="ti ti-building"></i> Proyectos</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra']) && \Illuminate\Support\Facades\Route::has('operativa.cotizaciones.index'))
                        <a href="{{ route('operativa.cotizaciones.index') }}" class="nav-link {{ request()->routeIs('operativa.cotizaciones.*') ? 'active-link' : '' }}"><i class="ti ti-receipt"></i> Cotizaciones</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','cliente']) && \Illuminate\Support\Facades\Route::has('operativa.cuotas.index'))
                        <a href="{{ route('operativa.cuotas.index') }}" class="nav-link {{ request()->routeIs('operativa.cuotas.*') ? 'active-link' : '' }}"><i class="ti ti-credit-card"></i> Cuotas de pago</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','contab','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.compras.index'))
                        <a href="{{ route('operativa.compras.index') }}" class="nav-link {{ request()->routeIs('operativa.compras.*') ? 'active-link' : '' }}"><i class="ti ti-shopping-cart"></i> Compras</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.inventario.index'))
                        <a href="{{ route('operativa.inventario.index') }}" class="nav-link {{ request()->routeIs('operativa.inventario.index') ? 'active-link' : '' }}"><i class="ti ti-packages"></i> Inventario</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.inventario.uso.create'))
                        <a href="{{ route('operativa.inventario.uso.create') }}" class="nav-link {{ request()->routeIs('operativa.inventario.uso.*') ? 'active-link' : '' }}"><i class="ti ti-package-export"></i> Uso de material</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.movimientos.index'))
                        <a href="{{ route('operativa.movimientos.index') }}" class="nav-link {{ request()->routeIs('operativa.movimientos.*') ? 'active-link' : '' }}"><i class="ti ti-arrows-right-left"></i> Movimientos</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra']) && \Illuminate\Support\Facades\Route::has('operativa.paralizaciones.index'))
                        <a href="{{ route('operativa.paralizaciones.index') }}" class="nav-link {{ request()->routeIs('operativa.paralizaciones.*') ? 'active-link' : '' }}"><i class="ti ti-player-pause"></i> Paralizaciones</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','cliente']) && \Illuminate\Support\Facades\Route::has('operativa.finalizadas.index'))
                        <a href="{{ route('operativa.finalizadas.index') }}" class="nav-link {{ request()->routeIs('operativa.finalizadas.*') ? 'active-link' : '' }}"><i class="ti ti-flag"></i> Obras terminadas</a>
                    @endif
                </div>
            </div>

            {{-- RECURSOS HUMANOS --}}
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Recursos Humanos</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra','rrhh']) && \Illuminate\Support\Facades\Route::has('rrhh.empleados.index'))
                        <a href="{{ route('rrhh.empleados.index') }}" class="nav-link {{ request()->routeIs('rrhh.empleados.*') ? 'active-link' : '' }}"><i class="ti ti-user"></i> Empleados</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','jefe obra','rrhh']) && \Illuminate\Support\Facades\Route::has('rrhh.asignaciones.index'))
                        <a href="{{ route('rrhh.asignaciones.index') }}" class="nav-link {{ request()->routeIs('rrhh.asignaciones.*') ? 'active-link' : '' }}"><i class="ti ti-user-plus"></i> Asig. personal</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','jefe obra','rrhh']) && \Illuminate\Support\Facades\Route::has('operativa.asistencia.index'))
                        <a href="{{ route('operativa.asistencia.index') }}" class="nav-link {{ request()->routeIs('operativa.asistencia.*') ? 'active-link' : '' }}"><i class="ti ti-clock"></i> Control de horas</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('operativa.maquinarias.asignaciones'))
                        <a href="{{ route('operativa.maquinarias.asignaciones') }}" class="nav-link {{ request()->routeIs('operativa.maquinarias.asignaciones*') ? 'active-link' : '' }}"><i class="ti ti-settings"></i> Asig. maquinaria</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','contab','rrhh']) && \Illuminate\Support\Facades\Route::has('rrhh.pagos.index'))
                        <a href="{{ route('rrhh.pagos.index') }}" class="nav-link {{ request()->routeIs('rrhh.pagos.*') ? 'active-link' : '' }}"><i class="ti ti-cash"></i> Pagos / planillas</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','jefe obra']) && \Illuminate\Support\Facades\Route::has('rrhh.permisos.index'))
                        <a href="{{ route('rrhh.permisos.index') }}" class="nav-link {{ request()->routeIs('rrhh.permisos.*') ? 'active-link' : '' }}"><i class="ti ti-clipboard"></i> Permisos y trámites</a>
                    @endif
                </div>
            </div>

            {{-- REPORTES --}}
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Reportes</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist']) && \Illuminate\Support\Facades\Route::has('reportes.costos.index'))
                        <a href="{{ route('reportes.costos.index') }}" class="nav-link {{ request()->routeIs('reportes.costos.*') ? 'active-link' : '' }}"><i class="ti ti-chart-bar"></i> Resumen costos</a>
                    @endif
                    @if(Auth::user()->hasRole(['admin','gerente','contab','jefe obra','logist','rrhh']) && \Illuminate\Support\Facades\Route::has('reportes.alertas.index'))
                        <a href="{{ route('reportes.alertas.index') }}" class="nav-link {{ request()->routeIs('reportes.alertas.*') ? 'active-link' : '' }}">
                            <i class="ti ti-bell"></i> Alertas
                            @php
                                try { $alertCount = \App\Models\Notificacion::where('id_destinatario', Auth::id())->where('leida', 0)->count(); } catch (\Exception $e) { $alertCount = 0; }
                            @endphp
                            @if($alertCount > 0)
                                <span class="ms-auto badge rounded-pill" style="background:#ef4444;color:white;font-size:0.65rem;padding:2px 6px;">{{ $alertCount }}</span>
                            @endif
                        </a>
                    @endif
                    @if(Auth::user()->hasRole(['admin']) && \Illuminate\Support\Facades\Route::has('reportes.log.index'))
                        <a href="{{ route('reportes.log.index') }}" class="nav-link {{ request()->routeIs('reportes.log.*') ? 'active-link' : '' }}"><i class="ti ti-shield"></i> Log de auditoría</a>
                    @endif
                </div>
            </div>

            {{-- CONFIGURACIÓN --}}
            @if(Auth::user()->hasRole(['admin','rrhh']))
            <div class="sidebar-section">
                <div class="sidebar-header expanded" onclick="toggleSection(this)">
                    <span>Configuración</span>
                    <i class="ti ti-caret-right"></i>
                </div>
                <div class="sidebar-items">
                    @if(Auth::user()->hasRole(['admin']))
                        <a href="{{ route('configuracion.usuarios.index') }}" class="nav-link {{ request()->routeIs('configuracion.usuarios.*') ? 'active-link' : '' }}"><i class="ti ti-users-group"></i> Usuarios</a>
                    @endif
                    <a href="{{ route('rrhh.feriados.index') }}" class="nav-link {{ request()->routeIs('rrhh.feriados.*') ? 'active-link' : '' }}"><i class="ti ti-calendar"></i> Feriados</a>
                </div>
            </div>
            @endif

        </div>

        {{-- Logout --}}
        @auth
        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="ti ti-logout"></i> Cerrar sesión
                </button>
            </form>
        </div>
        @endauth
    </nav>

    {{-- ════════════════════════════════════════════════════
         MAIN AREA
    ════════════════════════════════════════════════════ --}}
    <div class="flex-grow-1 d-flex flex-column" style="min-width:0; margin-left: 260px; width: calc(100% - 260px);">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="topbar-title">
                <h5>@yield('page_title', 'Panel de control')</h5>
                <small>@yield('page_subtitle', 'Sistema de Gestión')</small>
            </div>
            <div class="topbar-actions">
                {{-- Theme toggle --}}
                <button type="button" id="themeToggle" class="topbar-bell" title="Cambiar tema" style="border:1px solid var(--topbar-border);">
                    <i class="fas fa-moon" id="themeIcon" style="font-size:0.9rem;"></i>
                </button>
                {{-- Bell --}}
                @if(\Illuminate\Support\Facades\Route::has('reportes.alertas.index'))
                <a href="{{ route('reportes.alertas.index') }}" class="topbar-bell">
                    <i class="fas fa-bell" style="font-size:0.9rem;"></i>
                    @if(isset($alertCount) && $alertCount > 0)
                        <span class="badge-dot"></span>
                    @endif
                </a>
                @endif

                {{-- Avatar --}}
                @auth
                <div class="topbar-avatar" title="{{ $authUser->nombreParaMostrar() }}">
                    {{ strtoupper(substr($authUser->nombreParaMostrar(), 0, 2)) }}
                </div>
                @endauth
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="main-content">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-circle-xmark me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-triangle-exclamation me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
@yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSection(header) {
    header.classList.toggle('expanded');
    const items = header.nextElementSibling;
    if (header.classList.contains('expanded')) {
        items.style.maxHeight = items.scrollHeight + "px";
    } else {
        items.style.maxHeight = "0px";
    }
}
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sidebar-header.expanded').forEach(header => {
        const items = header.nextElementSibling;
        items.style.maxHeight = items.scrollHeight + "px";
    });
});

// Theme toggle
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');

function updateIcon() {
    const current = document.documentElement.getAttribute('data-theme');
    themeIcon.className = current === 'light' ? 'fas fa-sun' : 'fas fa-moon';
}
updateIcon();

themeToggle.addEventListener('click', () => {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    updateIcon();
});
</script>
@stack('scripts')
</body>
</html>
