<?php
$file = 'c:\laragon\www\liveeditor\routes\web.php';
$content = file_get_contents($file);

if (strpos($content, 'tugas.force_submit') === false) {
    $search = "Route::post('tugas/{tuga}/koreksi/{siswa}', [\App\Http\Controllers\Guru\AssignmentController::class, 'simpanNilai'])->name('tugas.nilai');";
    $replace = $search . "\n    Route::post('tugas/{tuga}/force-submit', [\App\Http\Controllers\Guru\AssignmentController::class, 'forceSubmit'])->name('tugas.force_submit');";
    
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added route.\n";
}
?>