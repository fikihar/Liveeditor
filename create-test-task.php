<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignment = App\Models\Assignment::create([
    'class_id' => 1, // X TJKT A (Kelasnya Ahmad Fauzi)
    'title' => 'LATIHAN UJI COBA CSS',
    'description' => 'Tugas ini untuk menguji pemanggilan External CSS. Coba lihat tab Hasil (pasti masih putih). Lalu kembali ke HTML, tambahkan tag <link rel="stylesheet" href="style.css"> di dalam <head>, lalu lihat Hasilnya lagi!',
    'type' => 'latihan',
    'deadline' => null,
    'has_css' => true,
    'status' => 'published',
    'starter_html' => "<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>Latihan CSS</title>\n  <!-- Ketik link CSS kamu di bawah baris ini -->\n  \n</head>\n<body>\n  <h1>Belajar CSS External</h1>\n  <p>Jika kode CSS terhubung, latar belakang ini akan berubah menjadi Kuning!</p>\n</body>\n</html>",
    'starter_css' => "body {\n  background-color: yellow;\n}\nh1 {\n  color: red;\n}"
]);

echo "Test task created! ID: " . $assignment->id;
?>