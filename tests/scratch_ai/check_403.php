<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assignment = \App\Models\Assignment::find(9);
echo "Assignment class_id: " . $assignment->class_id . "\n";
echo "ClassRoom exists? " . ($assignment->classRoom ? 'Yes' : 'No') . "\n";
if ($assignment->classRoom) {
    echo "Teacher ID in ClassRoom: " . $assignment->classRoom->teacher_id . "\n";
}

// How did we fetch it in other methods?
// Let's check edit() method in AssignmentController.
$controller = file_get_contents('app/Http/Controllers/Guru/AssignmentController.php');
if (preg_match('/abort_if\(.*?, 403\);/i', $controller, $matches)) {
    echo "Authorization pattern found in Controller: " . $matches[0] . "\n";
}
?>