<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$siswa = App\Models\User::where('username', '2024001')->first();
echo "Testing Dashboard for: {$siswa->name} (Class ID: {$siswa->class_id})\n";

$assignments = \App\Models\Assignment::where('class_id', $siswa->class_id)
            ->published()
            ->orderBy('created_at', 'desc')
            ->get();

echo "Found: {$assignments->count()} assignments\n";
foreach($assignments as $a) {
    echo "- {$a->title} (Status: {$a->status}, Class: {$a->class_id})\n";
}