<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$content = file_get_contents($file);

$search = "  function confirmForceSubmit() {";
$replace = "  <script>\n  function confirmForceSubmit() {";

if (strpos($content, '<script>\n  function confirmForceSubmit') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added missing <script> tag.\n";
} else {
    echo "Already fixed.\n";
}
?>