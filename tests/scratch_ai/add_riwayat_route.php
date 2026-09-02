<?php
$file = 'c:\laragon\www\liveeditor\routes\web.php';
$content = file_get_contents($file);

if (strpos($content, "name('riwayat')") === false) {
    $search = "Route::get('/dashboard', [\App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');";
    $replace = $search . "\n    Route::get('/riwayat', [\App\Http\Controllers\Siswa\DashboardController::class, 'history'])->name('riwayat');";
    
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Route added.\n";
} else {
    echo "Route already exists.\n";
}
?>