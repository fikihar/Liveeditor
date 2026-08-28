<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('username', '2024001')->first();
echo "Username: {$user->username}\n";
echo "Class ID: {$user->class_id}\n";

$assignment = App\Models\Assignment::find(3); // The one I created
echo "Assignment Class ID: {$assignment->class_id}\n";