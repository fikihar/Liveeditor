<?php
$file = 'c:\laragon\www\liveeditor\public\css\guru.css';
$content = file_get_contents($file);

$search = "/* Tabel responsif - bisa di-scroll horizontal */";
$replace = <<<CSS
    /* Grid 4 kolom menjadi 2 kolom atau 1 kolom di HP */
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
    }
    
    /* Layout kolom koreksi di HP menjadi bersusun ke bawah */
    .koreksi-grid {
        grid-template-columns: 1fr !important;
        height: auto !important;
        gap: 16px;
    }
    .koreksi-col {
        height: 500px !important;
    }
    
    $search
CSS;

if (strpos($content, '.stats-grid {') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added stats-grid and koreksi-grid mobile CSS.\n";
}
?>