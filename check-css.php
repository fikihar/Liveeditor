<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignments = App\Models\Assignment::all();
echo "--- ALL ASSIGNMENTS ---\n";
foreach($assignments as $a) {
    echo "ID: {$a->id} | Judul: {$a->title} | Has CSS: {$a->has_css} | CSS Awal length: " . strlen($a->starter_css) . "\n";
}