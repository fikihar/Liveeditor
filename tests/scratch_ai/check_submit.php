<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$search = "if (\$request->input('action') === 'save') {";
if (strpos($content, $search) !== false) {
    // wait, what is the exact string?
}
?>