<?php
// 1. UPDATE ROUTES
$routeFile = 'c:\laragon\www\liveeditor\routes\web.php';
$routes = file_get_contents($routeFile);
$newRoute = "Route::post('editor/{assignment}/log-cheat', [\App\Http\Controllers\Siswa\EditorController::class, 'logCheat'])->name('editor.cheat');\n    Route::post('editor/{assignment}/submit'";
$routes = str_replace("Route::post('editor/{assignment}/submit'", $newRoute, $routes);
file_put_contents($routeFile, $routes);

// 2. UPDATE CONTROLLER
$ctrlFile = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$ctrl = file_get_contents($ctrlFile);
$newMethod = <<<PHP
    public function logCheat(Assignment \$assignment)
    {
        \$siswa = auth()->user();
        if (\$assignment->type === 'tugas') {
            \$submission = Submission::where('assignment_id', \$assignment->id)->where('student_id', \$siswa->id)->first();
            if (\$submission && \$submission->status === 'draft') {
                \$submission->increment('cheat_count');
            }
        }
        return response()->json(['success' => true]);
    }

    public function submit(Request \$request, Assignment \$assignment)
PHP;
$ctrl = str_replace("public function submit(Request \$request, Assignment \$assignment)", $newMethod, $ctrl);
file_put_contents($ctrlFile, $ctrl);
echo "Backend cheat logging setup complete.\n";
?>