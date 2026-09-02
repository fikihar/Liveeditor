<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$start = strpos($content, 'public function submit(');
$end = strpos($content, 'public function logActivity(', $start);
echo substr($content, $start, $end - $start);
?>