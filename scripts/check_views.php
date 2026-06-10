<?php
$missing = [];
$base = __DIR__ . '/../';
$dirs = ['GestionOperativa', 'RRHH', 'Configuracion', 'Auth', ''];

foreach($dirs as $dir) {
    $path = $base . 'app/Http/Controllers/' . $dir;
    if(!is_dir($path)) continue;
    foreach(scandir($path) as $file) {
        if(str_ends_with($file, '.php')) {
            $content = file_get_contents($path.'/'.$file);
            preg_match_all("/view\(['\"]([^'\"]+)['\"]/", $content, $matches);
            foreach($matches[1] as $viewName) {
                $viewPath = $base . 'resources/views/'.str_replace('.', '/', $viewName).'.blade.php';
                if(!file_exists($viewPath) && !in_array($viewName, $missing)) {
                    $missing[] = $viewName . ' (referenced in ' . $file . ')';
                }
            }
        }
    }
}
echo "Missing Views:\n" . implode("\n", $missing) . "\n";
