<?php
/**
 * Script: polish_views.php
 * - Agrega @section('page_title') y @section('page_subtitle') a todas las vistas
 * - Corrige el cierre incorrecto de table-wrapper generado por el script anterior
 * - Elimina <div class="container-fluid"> redundante en vistas que ya están en main-content
 * - Asegura que los headers de página sigan el patrón premium
 */

$viewsDir = realpath(__DIR__ . '/../resources/views');
$changed  = 0;

// Mapa de rutas de vista → título/subtítulo
$titleMap = [
    'operativa/clientes/index'       => ['Clientes',            'Gestión Operativa · Lista de clientes'],
    'operativa/clientes/create'      => ['Nuevo Cliente',       'Gestión Operativa · Registro de cliente'],
    'operativa/clientes/edit'        => ['Editar Cliente',      'Gestión Operativa · Modificar datos del cliente'],
    'operativa/proyectos/index'      => ['Proyectos',           'Gestión Operativa · Lista de proyectos'],
    'operativa/proyectos/create'     => ['Nuevo Proyecto',      'Gestión Operativa · Registrar nuevo proyecto'],
    'operativa/proyectos/edit'       => ['Editar Proyecto',     'Gestión Operativa · Modificar proyecto'],
    'operativa/proyectos/show'       => ['Detalle Proyecto',    'Gestión Operativa · Información completa'],
    'operativa/contratos/index'      => ['Contratos',           'Gestión Operativa · Lista de contratos'],
    'operativa/contratos/create'     => ['Nuevo Contrato',      'Gestión Operativa · Registrar contrato'],
    'operativa/contratos/edit'       => ['Editar Contrato',     'Gestión Operativa · Modificar contrato'],
    'operativa/contratos/show'       => ['Detalle Contrato',    'Gestión Operativa · Información del contrato'],
    'operativa/cotizaciones/index'   => ['Cotizaciones',        'Gestión Operativa · Lista de cotizaciones'],
    'operativa/cotizaciones/create'  => ['Nueva Cotización',    'Gestión Operativa · Registrar cotización'],
    'operativa/cuotas/index'         => ['Cuotas de Pago',      'Gestión Operativa · Control de pagos y vencimientos'],
    'operativa/cuotas/create'        => ['Nueva Cuota',         'Gestión Operativa · Registrar cuota de pago'],
    'operativa/cuotas/edit'          => ['Editar Cuota',        'Gestión Operativa · Modificar cuota'],
    'operativa/compras/index'        => ['Compras',             'Gestión Operativa · Registro de compras'],
    'operativa/compras/create'       => ['Nueva Compra',        'Gestión Operativa · Registrar compra'],
    'operativa/compras/edit'         => ['Editar Compra',       'Gestión Operativa · Modificar compra'],
    'operativa/inventario/index'     => ['Inventario',          'Gestión Operativa · Stock de materiales'],
    'operativa/paralizaciones/index' => ['Paralizaciones',      'Gestión Operativa · Obras paralizadas'],
    'operativa/paralizaciones/create'=> ['Nueva Paralización',  'Gestión Operativa · Registrar paralización'],
    'operativa/paralizaciones/edit'  => ['Editar Paralización', 'Gestión Operativa · Modificar paralización'],
    'operativa/finalizadas/index'    => ['Obras Terminadas',    'Gestión Operativa · Registro de obras concluidas'],
    'operativa/proveedores/index'    => ['Proveedores',         'Gestión Operativa · Lista de proveedores'],
    'operativa/maquinarias/index'    => ['Maquinaria',          'Gestión Operativa · Catálogo de maquinaria'],
    'operativa/ciudades/index'       => ['Ciudades',            'Maestros · Lista de ciudades'],
    'operativa/ciudades/create'      => ['Nueva Ciudad',        'Maestros · Registrar ciudad'],
    'operativa/ciudades/edit'        => ['Editar Ciudad',       'Maestros · Modificar ciudad'],
    'operativa/asistencia/index'     => ['Control de Horas',    'RRHH · Registro de asistencia y horas'],
    'rrhh/empleados/index'           => ['Empleados',           'Recursos Humanos · Lista de empleados'],
    'rrhh/empleados/create'          => ['Nuevo Empleado',      'Recursos Humanos · Registrar empleado'],
    'rrhh/empleados/edit'            => ['Editar Empleado',     'Recursos Humanos · Modificar datos del empleado'],
    'rrhh/asignaciones/index'        => ['Asignaciones',        'Recursos Humanos · Asignación de personal'],
    'rrhh/pagos/index'               => ['Pagos y Planillas',   'Recursos Humanos · Control de pagos'],
    'rrhh/permisos/index'            => ['Permisos y Trámites', 'Recursos Humanos · Solicitudes de permiso'],
    'rrhh/feriados/index'            => ['Feriados',            'Configuración · Calendario de feriados'],
    'reportes/costos/index'          => ['Resumen de Costos',   'Reportes · Análisis financiero por proyecto'],
    'reportes/alertas/index'         => ['Alertas',             'Sistema · Notificaciones y alertas'],
    'reportes/log/index'             => ['Log de Auditoría',    'Sistema · Registro de cambios'],
    'configuracion/usuarios/index'   => ['Usuarios',            'Configuración · Gestión de accesos'],
    'configuracion/usuarios/create'  => ['Nuevo Usuario',       'Configuración · Registrar usuario'],
    'configuracion/usuarios/edit'    => ['Editar Usuario',      'Configuración · Modificar usuario'],
];

function walkDir2(string $dir, callable $fn): void
{
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path))  walkDir2($path, $fn);
        if (is_file($path) && str_ends_with($item, '.blade.php')) $fn($path);
    }
}

walkDir2($viewsDir, function (string $file) use ($viewsDir, $titleMap, &$changed) {
    // Build relative key (forward slashes, no extension)
    $rel = str_replace('\\', '/', substr($file, strlen($viewsDir) + 1));
    $key = str_replace('.blade.php', '', $rel);

    $original = file_get_contents($file);
    $content  = $original;

    // ── 1. Inject @section('page_title') if not present ──────────────────────
    if (isset($titleMap[$key]) && strpos($content, "@section('page_title'") === false) {
        [$title, $subtitle] = $titleMap[$key];
        $injection  = "@section('title', '$title')\n";
        $injection .= "@section('page_title', '$title')\n";
        $injection .= "@section('page_subtitle', '$subtitle')\n\n";

        // Insert right after @extends line
        $content = preg_replace(
            '/^(@extends\([^\)]+\)\s*\n)/m',
            "$1\n$injection",
            $content,
            1
        );
    }

    // ── 2. Fix malformed table-wrapper from previous script ───────────────────
    // The previous script may have left </div></div></div> without proper nesting
    // Pattern: table + pagination div ending with </div></div></div> → fix to </div></div>
    $content = preg_replace(
        '/(<\/table>\s*<div[^>]*justify-content-center[^>]*>.*?<\/div>)\s*<\/div><\/div><\/div>/s',
        "$1\n    </div>\n</div>",
        $content
    );

    // ── 3. Remove redundant outer container-fluid wrapper ─────────────────────
    // The main layout already provides padding, so remove the wrapping div
    // But be careful to only remove the outermost one if it wraps everything
    if (preg_match('/^@section\(\'content\'\)\s*<div class="container-fluid">/m', $content)) {
        $content = preg_replace(
            '/^(@section\(\'content\'\))\s*<div class="container-fluid">/m',
            "$1",
            $content,
            1
        );
        // Remove its closing </div> just before @endsection
        $content = preg_replace(
            '/<\/div>\s*(@endsection\s*)$/',
            "$1",
            $content
        );
    }

    // ── 4. Modernize page header h3 blocks ────────────────────────────────────
    // Replace: <h3 class="fw-light text-secondary mb-0">Something</h3>
    // With:    <h4 class="fw-semibold mb-0" style="color:#0f172a;">Something</h4>
    $content = preg_replace(
        '/<h3 class="fw-light text-secondary mb-0">([^<]+)<\/h3>/',
        '<h4 class="fw-semibold mb-0" style="color:#0f172a;font-size:1.05rem;">$1</h4>',
        $content
    );

    if ($content !== $original) {
        file_put_contents($file, $content);
        $changed++;
        echo "  Polished: $key\n";
    }
});

echo "\n✅ Done. $changed files polished.\n";
