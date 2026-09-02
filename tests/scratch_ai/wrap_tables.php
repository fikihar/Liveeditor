<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\guru\kelas\index.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\siswa\index.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Check if table is already wrapped
    if (strpos($content, '<div class="table-responsive">') === false) {
        $content = str_replace('<table', '<div class="table-responsive">' . "\n" . '        <table', $content);
        $content = str_replace('</table>', '</table>' . "\n" . '      </div>', $content);
        file_put_contents($file, $content);
        echo "Wrapped table in $file\n";
    }
}
?>