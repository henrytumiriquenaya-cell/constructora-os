<?php
$dir = 'c:/Users/HP NOTEBOOK/Desktop/tbdgit/empresa/empresa/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), 'index.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        
        // Find the main variable used in foreach
        if (preg_match('/@forelse\s*\(\s*\$(\w+)\s+as/i', $content, $matches) || preg_match('/@foreach\s*\(\s*\$(\w+)\s+as/i', $content, $matches)) {
            $varName = $matches[1];
            
            // Replace {{ $() }} with {{ $varName->links() }}
            if (strpos($content, '{{ $() }}') !== false) {
                $newContent = str_replace('{{ $() }}', '{{ $'.$varName.'->links() }}', $content);
                
                if ($newContent !== $content) {
                    file_put_contents($file->getPathname(), $newContent);
                    echo "Fixed pagination in: " . $file->getPathname() . "\n";
                }
            }
        }
    }
}
