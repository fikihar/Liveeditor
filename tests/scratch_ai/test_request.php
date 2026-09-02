<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'GET');
$response = $kernel->handle($request);
echo "Login Status: " . $response->getStatusCode() . "\n";

// Login manually
$siswa = App\Models\User::where('role', 'siswa')->first();
auth()->login($siswa);

$assignment = App\Models\Assignment::where('class_id', $siswa->class_id)->first();
if(!$assignment) { echo "No assignment for this student."; exit; }

$url = "/siswa/tugas/{$assignment->id}/editor";
echo "Fetching: $url\n";
$request = Illuminate\Http\Request::create($url, 'GET');
// Provide session to request
$request->setLaravelSession(app('session')->driver());
$request->setUserResolver(function() use ($siswa) { return $siswa; });

$response = $kernel->handle($request);
echo "Editor Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() == 500) {
    echo "Error 500 Output:\n";
    echo substr($response->getContent(), 0, 1000);
}
?>