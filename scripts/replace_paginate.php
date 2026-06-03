<?php
$dir = 'c:/Users/HP NOTEBOOK/Desktop/tbdgit/empresa/empresa/app/Http/Controllers';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Let's find index methods and replace ->get() or ::all() with ->paginate(15)
        // A simple way is to use regex: find public function index() { ... } and inside it replace get() / all()
        
        $lines = explode("\n", $content);
        $inIndex = false;
        $modified = false;
        $braceCount = 0;
        
        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            
            if (preg_match('/public function (index|costos|log|asignaciones|pagos|permisos|feriados)\s*\(/', $line)) {
                $inIndex = true;
                $braceCount = 0;
            }
            
            if ($inIndex) {
                $braceCount += substr_count($line, '{');
                $braceCount -= substr_count($line, '}');
                
                // Only replace if it's an assignment or return statement involving get() or all()
                // Avoid replacing get() if it's inside a parameter or something else, but here we can just replace ->get() with ->paginate(15)
                // and ::all() with ::paginate(15)
                
                if (preg_match('/::all\(\)/', $line)) {
                    $lines[$i] = str_replace('::all()', '::paginate(15)', $line);
                    $modified = true;
                }
                
                if (preg_match('/->get\(\)/', $line)) {
                    $lines[$i] = str_replace('->get()', '->paginate(15)', $line);
                    $modified = true;
                }
                
                if (preg_match('/->get\(\s*\)/', $line)) {
                    $lines[$i] = preg_replace('/->get\(\s*\)/', '->paginate(15)', $lines[$i]);
                    $modified = true;
                }
                
                if ($braceCount <= 0 && strpos($line, '}') !== false) {
                    $inIndex = false;
                }
            }
        }
        
        if ($modified) {
            file_put_contents($file->getPathname(), implode("\n", $lines));
            echo "Modified: " . $file->getFilename() . "\n";
        }
    }
}
