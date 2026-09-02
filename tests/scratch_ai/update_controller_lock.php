<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$oldCheck = <<<PHP
        // Pastikan tugas ini milik kelas siswa
        abort_if(\$assignment->class_id != \$siswa->class_id, 403, 'Akses ditolak.');
        abort_if(\$assignment->status !== 'published', 404, 'Tugas belum dipublikasi.');
PHP;

$newCheck = <<<PHP
        // Pastikan tugas ini milik kelas siswa
        abort_if(\$assignment->class_id != \$siswa->class_id, 403, 'Akses ditolak.');
        abort_if(\$assignment->status !== 'published', 404, 'Tugas belum dipublikasi.');
        
        // Cek tenggat waktu khusus tipe tugas
        if (\$assignment->type === 'tugas' && \$assignment->deadline && now()->gt(\$assignment->deadline)) {
            abort(403, 'Batas waktu pengerjaan tugas sudah habis!');
        }
PHP;

$content = str_replace($oldCheck, $newCheck, $content);
file_put_contents($file, $content);
echo "Backend lock added.\n";
?>