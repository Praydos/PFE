<?php
$dir = new RecursiveDirectoryIterator('c:/Users/DADAS/Desktop/PFE/bookland/app/Http/Controllers');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $original = $content;

        $lines = explode("\n", $content);
        foreach ($lines as &$line) {
            // Check if the line has a role check but DOES NOT have an admin check
            if (strpos($line, 'role !==') !== false && strpos($line, "'admin'") === false) {
                // Find `if (` or `if(` 
                if (preg_match('/if\s*\((.*?role !==.*?)\)/', $line, $matches)) {
                    $condition = $matches[1];
                    $replaced = preg_replace('/if\s*\((.*?role !==.*?)\)/', 'if ($user->role !== \'admin\' && ($1))', $line);
                    $line = $replaced;
                }
            }
            
            // Also check for `in_array($user->role, [`
            if (strpos($line, 'in_array($user->role, [') !== false && strpos($line, "'admin'") === false) {
                $line = str_replace("['", "['admin', '", $line);
                $line = str_replace('["', '["admin", "', $line);
            }
        }
        $content = implode("\n", $lines);
        
        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.\n";
