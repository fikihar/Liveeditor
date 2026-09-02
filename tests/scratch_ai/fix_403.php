<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

$content = str_replace(
    'abort_if($tuga->classRoom->teacher_id != $guru->id, 403);',
    'abort_if($tuga->classRoom->guru_id != $guru->id, 403);',
    $content
);

file_put_contents($file, $content);
echo "403 Bug fixed in Controller.\n";
?>