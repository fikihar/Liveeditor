<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

$oldCheck = "\$submission = \App\Models\Submission::where('assignment_id', \$tuga->id)\n            ->where('student_id', \$siswa->id)\n            ->firstOrFail();";

$newCheck = "\$submission = \App\Models\Submission::where('assignment_id', \$tuga->id)\n            ->where('student_id', \$siswa->id)\n            ->firstOrFail();\n\n        if (\$submission->status === 'draft') {\n            return back()->with('error', 'Tugas masih berupa draft dan belum dikumpulkan siswa.');\n        }";

$content = str_replace($oldCheck, $newCheck, $content);
file_put_contents($file, $content);
echo "AssignmentController updated.\n";
?>