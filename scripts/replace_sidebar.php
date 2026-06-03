<?php
$file = __DIR__ . '/../resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// 1. Add Tabler icons to head
if (!str_contains($content, 'tabler-icons.min.css')) {
    $content = str_replace(
        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">',
        '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">' . "\n    " . '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">',
        $content
    );
}

// 2. Replace the sidebar HTML
$newSidebar = <<<'HTML'
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
                <div class="user-role">{{ $authUser->rolNormalizado() }}</div>
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
                        <a href="{{ route('operativa.inventario.index') }}" class="nav-link {{ request()->routeIs('operativa.inventario.*') ? 'active-link' : '' }}"><i class="ti ti-packages"></i> Inventario</a>
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
HTML;

$content = preg_replace(
    '/(<nav class="sidebar.*?">).*?(<\/nav>)/s',
    $newSidebar,
    $content
);

// 3. Add JS for toggle
$js = <<<'HTML'
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
</script>
</body>
HTML;

if (!str_contains($content, 'function toggleSection(')) {
    $content = str_replace('</body>', $js, $content);
}

file_put_contents($file, $content);
echo "Sidebar updated successfully.";
