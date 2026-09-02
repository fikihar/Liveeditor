<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $siswa = App\Models\User::where('role', 'siswa')->first();
    auth()->login($siswa);
    $assignments = App\Models\Assignment::published()->where('class_id', $siswa->class_id)->with('submissions')->latest()->get();
    
    $view = view('siswa.dashboard', compact('siswa', 'assignments'))->render();
    echo "Dashboard OK. Length: " . strlen($view);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}
?>