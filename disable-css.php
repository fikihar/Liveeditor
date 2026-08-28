<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignment = App\Models\Assignment::find(1);
if ($assignment) {
    $assignment->has_css = false;
    $assignment->save();
    echo "Tugas 1 (BUAT HTML) CSS-nya berhasil dinonaktifkan.\n";
}