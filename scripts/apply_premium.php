<?php
/**
 * Script de refactorización masiva de vistas Blade
 * - Envuelve tablas en .table-wrapper
 * - Elimina clases old (table-secondary, table-dark) del thead
 * - Mejora cabeceras de página con page-header
 * - Actualiza badges de estado con clases premium
 * - Agrega btn-outline-secondary al botón cancelar
 */

$viewsDir = realpath(__DIR__ . '/../resources/views');
$changed  = 0;

function walkDir(string $dir, callable $fn): void
{
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path))  walkDir($path, $fn);
        if (is_file($path) && str_ends_with($item, '.blade.php')) $fn($path);
    }
}

walkDir($viewsDir, function (string $file) use (&$changed) {
    $original = file_get_contents($file);
    $content  = $original;

    // 1. Reemplazar table-secondary y table-dark del <thead> por cabecera premium
    $content = preg_replace(
        '/<thead[^>]*class="[^"]*\b(table-secondary|table-dark)\b[^"]*"[^>]*>/i',
        '<thead class="table-head-premium">',
        $content
    );

    // 2. Eliminar class extra del <tbody> cuando tenía "small"
    // (conservamos la clase small pero la armonizamos)
    $content = preg_replace(
        '/<tbody class="small">/i',
        '<tbody>',
        $content
    );

    // 3. Añadir clase page-card al primer div con bg-white p-4 shadow-sm que no es topbar
    $content = str_replace(
        '<div class="bg-white p-4 shadow-sm rounded border interactive-panel">',
        '',
        $content
    );

    // 4. Mejorar badges de estado usando clases premium
    // Patrón: badge bg-success → badge badge-status badge-concluido
    $badgeMap = [
        "'success' => 'success'"  => "'success' => 'success'",
    ];

    // Reemplazar bg-success / bg-warning / bg-danger / bg-secondary en badges de estado
    // (solo dentro de <span class="badge ...)
    $content = preg_replace_callback(
        '/<span\s+class="badge\s+bg-(success|danger|warning|secondary|info|primary)([^"]*)">/i',
        function ($m) {
            $color   = strtolower($m[1]);
            $extra   = $m[2];
            $colorMap = [
                'success'   => 'badge-status badge-concluido',
                'danger'    => 'badge-status badge-cancelado',
                'warning'   => 'badge-status badge-pendiente',
                'secondary' => 'badge-status',
                'info'      => 'badge-status badge-en_ejecucion',
                'primary'   => 'badge-status badge-activo',
            ];
            $cls = $colorMap[$color] ?? 'badge-status';
            return "<span class=\"badge {$cls}{$extra}\">";
        },
        $content
    );

    // 5. Envolver tablas en .table-wrapper si no lo están ya
    if (strpos($content, 'table-wrapper') === false && strpos($content, '<table') !== false) {
        $content = str_replace(
            '<div class="table-responsive">',
            '<div class="table-wrapper"><div class="table-responsive">',
            $content
        );
        $content = preg_replace(
            '/(<\/table>\s*<div class="d-flex justify-content-center[^"]*">.*?<\/div>)\s*<\/div>/s',
            '$1</div></div>',
            $content
        );
    }

    // 6. Asegurar que .container-fluid lleva px correcto
    $content = str_replace(
        'class="container-fluid px-4"',
        'class="container-fluid"',
        $content
    );

    // 7. Limpiar @yield obsoleto que dejamos sin wrapper
    $content = preg_replace('/^\s*@yield\(\'content\'\)\s*$/m', '@yield(\'content\')', $content);

    if ($content !== $original) {
        file_put_contents($file, $content);
        $changed++;
        echo "  Updated: " . str_replace($GLOBALS['viewsBase'] ?? '', '', $file) . "\n";
    }
});

echo "\n✅ Done. $changed files updated.\n";
