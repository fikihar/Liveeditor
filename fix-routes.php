<?php
$routeFile = 'c:\laragon\www\liveeditor\routes\web.php';
$routes = file_get_contents($routeFile);
$newRoute = "Route::post('tugas/{assignment}/log-cheat', [\App\Http\Controllers\Siswa\EditorController::class, 'logCheat'])->name('editor.cheat');\n    Route::post('tugas/{assignment}/submit'";
$routes = str_replace("Route::post('tugas/{assignment}/submit'", $newRoute, $routes);
file_put_contents($routeFile, $routes);
echo "Routes updated.\n";
?>