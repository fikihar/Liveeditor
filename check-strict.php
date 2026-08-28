<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('username', '2024001')->first();
$assignment = App\Models\Assignment::find(3);

var_dump($user->class_id);
var_dump($assignment->class_id);
var_dump($user->class_id === $assignment->class_id);
var_dump($user->class_id == $assignment->class_id);