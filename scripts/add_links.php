<?php
$dir = 'c:/Users/HP NOTEBOOK/Desktop/tbdgit/empresa/empresa/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getFilename(), 'index.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        
        // Find the main variable used in foreach
        if (preg_match('/@forelse\s*\(\s*\$(\w+)\s+as/i', $content, $matches) || preg_match('/@foreach\s*\(\s*\$(\w+)\s+as/i', $content, $matches)) {
            $varName = $matches[1];
            
            // Check if links() is already there
            if (strpos($content, '->links()') === false) {
                // Insert after </table>
                $injection = "\n    <div class=\"d-flex justify-content-center mt-3\">\n        {{ \$$varName->links() }}\n    </div>";
                
                // Only replace the last </table> or a generic one if it's a list.
                // It's safer to just str_replace the first '</table>' with '</table>' . $injection
                
                $newContent = preg_replace('/<\/table>/i', '</table>' . $injection, $content, 1);
                
                if ($newContent !== $content) {
                    file_put_contents($file->getPathname(), $newContent);
                    echo "Added pagination to: " . $file->getPathname() . " (var: $varName)\n";
                }
            }
        }
    }
}
