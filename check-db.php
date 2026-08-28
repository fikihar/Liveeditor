<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DAFTAR SISWA ---\n";
$students = App\Models\User::where('role', 'siswa')->take(3)->get();
foreach($students as $s) {
    echo "Nama: {$s->name} | Username: {$s->username} | Class ID: {$s->class_id}\n";
}

echo "\n--- DAFTAR TUGAS ---\n";
$assignments = App\Models\Assignment::all();
if($assignments->isEmpty()) echo "Belum ada tugas sama sekali di database.\n";
foreach($assignments as $a) {
    echo "ID: {$a->id} | Judul: {$a->title} | Class ID: {$a->class_id} | Status: {$a->status}\n";
}