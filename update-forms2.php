<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\kelas\index.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $html = file_get_contents($file);
        
        // Use a generic approach: find any form with an action ending in destroy and add class form-delete
        // First, remove any onsubmit
        $html = preg_replace('/onsubmit="[^"]*"/i', '', $html);
        
        // Then, find form tags that don't have class="form-delete" and add it
        $html = preg_replace('/(<form\s+[^>]*action="[^"]*destroy[^"]*"[^>]*)>/i', '$1 class="form-delete">', $html);
        
        file_put_contents($file, $html);
    }
}
echo "Forms properly updated.\n";
?>