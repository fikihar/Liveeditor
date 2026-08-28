<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$routes = \Route::getRoutes();
foreach($routes as $r) {
    if ($r->uri() == 'broadcasting/auth') {
        echo "Found route: {$r->uri()}\n";
        echo "Middlewares: " . implode(", ", $r->middleware()) . "\n";
    }
}