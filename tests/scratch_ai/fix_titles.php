<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\layouts\guru.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\history.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php',
    'c:\laragon\www\liveeditor\resources\views\auth\login.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Fix mojibake and standardize titles
    $content = preg_replace('/<title>.*?<\/title>/s', '<title>@yield(\'title\', \'ClassEditor\') - ClassEditor</title>', $content);
    
    // For specific views that don't use yield properly or are standalone:
    if (strpos($file, 'siswa\dashboard.blade.php') !== false) {
        $content = str_replace("<title>@yield('title', 'ClassEditor') - ClassEditor</title>", "<title>Beranda Siswa - ClassEditor</title>", $content);
    }
    if (strpos($file, 'siswa\history.blade.php') !== false) {
        $content = str_replace("<title>@yield('title', 'ClassEditor') - ClassEditor</title>", "<title>Riwayat Tugas - ClassEditor</title>", $content);
    }
    if (strpos($file, 'siswa\editor.blade.php') !== false) {
        $content = preg_replace('/<title>.*?<\/title>/s', '<title>{{ $assignment->title }} - ClassEditor</title>', $content);
    }
    if (strpos($file, 'auth\login.blade.php') !== false) {
        $content = preg_replace('/<title>.*?<\/title>/s', '<title>Login - ClassEditor</title>', $content);
    }
    
    file_put_contents($file, $content);
    echo "Updated title in $file\n";
}
?>