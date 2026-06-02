<?php
$dirs = ['actions', 'actions_amelioration', 'adoptions', 'bss', 'consignations', 'demandes_specimens', 'events', 'examens', 'formations', 'non_conformites', 'reclamations', 'retours', 'taches'];

foreach ($dirs as $dir) {
    $indexPath = "resources/views/{$dir}/index.blade.php";
    if (file_exists($indexPath)) {
        $content = file_get_contents($indexPath);
        if (strpos($content, "@method('DELETE')") === false && strpos($content, "DELETE") === false) {
            echo "MISSING DELETE IN: {$dir}/index.blade.php\n";
        } else {
            echo "HAS DELETE: {$dir}/index.blade.php\n";
        }
    } else {
        echo "NO INDEX FILE: {$dir}\n";
    }
}
