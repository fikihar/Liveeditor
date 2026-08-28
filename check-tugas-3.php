<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignments = App\Models\Assignment::withTrashed()->get();
echo "--- ALL ASSIGNMENTS (INCLUDING DELETED) ---\n";
foreach($assignments as $a) {
    $dead = $a->deadline ? $a->deadline->format('Y-m-d H:i:s') : 'NULL';
    $del = $a->deleted_at ? "DELETED" : "ACTIVE";
    echo "ID: {$a->id} | Judul: {$a->title} | Kelas: {$a->class_id} | Status: {$a->status} | State: {$del} | Deadline: {$dead}\n";
}