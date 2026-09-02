<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
echo "Current Hash: " . $user->password . "\n";

// Manual Hash
$user->password = \Illuminate\Support\Facades\Hash::make('password123');
$user->save();
echo "After Hash::make: " . $user->password . "\n";
echo "Attempt Manual Hash: " . (\Illuminate\Support\Facades\Auth::attempt(['username' => $user->username, 'password' => 'password123']) ? 'Success' : 'Fail') . "\n";

// Raw Set (Let cast handle it)
$user->password = 'password123';
$user->save();
echo "After Raw Set: " . $user->password . "\n";
echo "Attempt Raw Set: " . (\Illuminate\Support\Facades\Auth::attempt(['username' => $user->username, 'password' => 'password123']) ? 'Success' : 'Fail') . "\n";
?>