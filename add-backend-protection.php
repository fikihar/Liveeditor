<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$oldLogic = <<<PHP
        \$submission = Submission::where('assignment_id', \$assignment->id)
            ->where('student_id', \$siswa->id)
            ->firstOrFail();

        \$submission->html_code = \$request->html_code ?? '';
PHP;

$newLogic = <<<PHP
        \$submission = Submission::where('assignment_id', \$assignment->id)
            ->where('student_id', \$siswa->id)
            ->firstOrFail();

        if (\$assignment->type === 'tugas' && \$submission->status === 'submitted') {
            return back()->with('error', 'Tugas sudah dikumpulkan dan tidak dapat diubah lagi.');
        }

        \$submission->html_code = \$request->html_code ?? '';
PHP;

$content = str_replace($oldLogic, $newLogic, $content);
file_put_contents($file, $content);
echo "EditorController backend protection added.\n";
?>