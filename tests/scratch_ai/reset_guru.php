<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$guru = \App\Models\User::where('username', 'guru')->first();
if ($guru) {
    // Because of the 'hashed' cast, just assigning the raw password works.
    $guru->password = 'guru1234';
    $guru->save();
    echo "Guru password reset to guru1234 successfully.\n";
} else {
    echo "Guru not found.\n";
}
?>