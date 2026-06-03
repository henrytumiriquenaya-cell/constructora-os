<?php
/**
 * Script de refactorización masiva de vistas Blade - V2
 * - Reemplaza btn-warning por btn-edit
 * - Reemplaza btn-danger por btn-delete
 * - Asegura que los badges municipales/ambientales se estilicen.
 */

$viewsDir = realpath(__DIR__ . '/../resources/views');
$changed  = 0;

function walkDirV2(string $dir, callable $fn): void
{
    foreach (scandir($dir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path))  walkDirV2($path, $fn);
        if (is_file($path) && str_ends_with($item, '.blade.php')) $fn($path);
    }
}

walkDirV2($viewsDir, function (string $file) use (&$changed) {
    $original = file_get_contents($file);
    $content  = $original;

    // 1. Reemplazar btn-warning por btn-edit (para iconos de editar fa-pen)
    $content = preg_replace(
        '/(class="[^"]*\b)btn-warning(\b[^"]*".*?<i class="[^"]*fa-pen)/',
        '$1btn-edit$2',
        $content
    );

    // 2. Reemplazar btn-danger por btn-delete (para iconos de eliminar fa-trash)
    $content = preg_replace(
        '/(class="[^"]*\b)btn-danger(\b[^"]*".*?<i class="[^"]*fa-trash)/',
        '$1btn-delete$2',
        $content
    );
    
    // 3. Eliminar d-none d-md-inline y similares del texto del sidebar para que SIEMPRE se muestre el texto
    $content = str_replace(
        'd-none d-md-inline',
        '',
        $content
    );

    // 4. Si hay badges que usan bg-success o similar para estados, mapear a badge-status y el nombre
    $content = preg_replace(
        '/<span class="badge\s+bg-(success|warning|danger|info|primary|secondary)([^"]*)">\s*\{\{\s*ucfirst\((.*?)\)\s*\}\}\s*<\/span>/i',
        '<span class="badge badge-status badge-{{ strtolower($3) }}$2">{{ ucfirst($3) }}</span>',
        $content
    );

    if ($content !== $original) {
        file_put_contents($file, $content);
        $changed++;
        echo "  Updated V2: " . str_replace($GLOBALS['viewsBase'] ?? '', '', $file) . "\n";
    }
});

echo "\n✅ Done V2. $changed files updated.\n";
