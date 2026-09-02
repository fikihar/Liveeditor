<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\history.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Look for the mobile media query and add hide rule for btn-logout
    $search = "@media (max-width: 640px) {";
    $replace = "@media (max-width: 640px) {\n        .navbar-right form { display: none; } /* Sembunyikan tombol keluar atas di HP */";
    
    if (strpos($content, '.navbar-right form { display: none;') === false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    } else {
        echo "Already updated $file\n";
    }
}
?>