<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $siswa = App\Models\User::where('role', 'siswa')->first();
    auth()->login($siswa);
    
    $assignment = App\Models\Assignment::first();
    if(!$assignment) { echo "No assignment found."; exit; }

    // Simulate what show() does
    abort_if($assignment->class_id != $siswa->class_id, 403, 'Akses ditolak.');
    abort_if($assignment->status !== 'published', 404, 'Tugas belum dipublikasi.');

    $submission = App\Models\Submission::firstOrNew([
        'assignment_id' => $assignment->id,
        'student_id'    => $siswa->id,
    ]);

    if (!$submission->exists) {
        $submission->html_code = $assignment->starter_html;
        $submission->css_code  = $assignment->starter_css;
        $submission->status    = 'draft';
        // $submission->save(); // don't save for test
    }
    
    // Test view rendering
    $view = view('siswa.editor', compact('assignment', 'submission'))->render();
    echo "View rendered successfully. Length: " . strlen($view);
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>