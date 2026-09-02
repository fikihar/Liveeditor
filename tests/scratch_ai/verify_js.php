<?php
$html = file_get_contents('c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php');
if (strpos($html, 'function renderPreview()') !== false) {
    echo "JS successfully applied.\n";
} else {
    echo "JS replacement failed.\n";
}
?>