<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\koreksi.blade.php';
$content = file_get_contents($file);

$search = '<div style="display:grid;grid-template-columns:260px 1fr 1fr;gap:20px;height:calc(100vh - 120px);align-items:stretch;">';
$replace = '<div class="koreksi-grid" style="display:grid;grid-template-columns:260px 1fr 1fr;gap:20px;height:calc(100vh - 120px);align-items:stretch;">';

if (strpos($content, 'koreksi-grid') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added koreksi-grid class.\n";
}

$searchCol1 = '<div style="display:flex;flex-direction:column;gap:16px;overflow-y:auto;">';
$replaceCol1 = '<div class="koreksi-col" style="display:flex;flex-direction:column;gap:16px;overflow-y:auto;">';
$content = str_replace($searchCol1, $replaceCol1, $content);

$searchCol2 = '<div style="display:flex;flex-direction:column;gap:16px;height:100%;overflow:hidden;">';
$replaceCol2 = '<div class="koreksi-col" style="display:flex;flex-direction:column;gap:16px;height:100%;overflow:hidden;">';
$content = str_replace($searchCol2, $replaceCol2, $content);

$searchCol3 = '<div class="card" style="display:flex;flex-direction:column;height:100%;overflow:hidden;border:1px solid var(--slate-200);">';
$replaceCol3 = '<div class="card koreksi-col" style="display:flex;flex-direction:column;height:100%;overflow:hidden;border:1px solid var(--slate-200);">';
$content = str_replace($searchCol3, $replaceCol3, $content);

file_put_contents($file, $content);
echo "Added koreksi-col classes.\n";
?>