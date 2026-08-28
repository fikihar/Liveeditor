<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$submissions = App\Models\Submission::all();
foreach($submissions as $s) {
    echo "ID: {$s->id} | Student: {$s->student_id} | Status: {$s->status} | Score: " . ($s->score !== null ? $s->score : 'NULL') . "\n";
}