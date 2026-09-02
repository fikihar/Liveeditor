<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php';
$html = file_get_contents($file);

if (preg_match('/<div id="criteria-section">.*?Tambah Kriteria\s*<\/button>\s*<\/div>/is', $html, $matches)) {
    $criteriaUI = $matches[0];
    
    // Remove the bad placement
    $html = str_replace($criteriaUI . "\n      </div>\n      <div class=\"card-footer form-actions\">", '<div class="card-footer form-actions">', $html);
    $html = preg_replace('/<\/div>\s*<div id="criteria-section">.*?Tambah Kriteria\s*<\/button>\s*<\/div>\s*<\/div>\s*<div class="card-footer form-actions">/is', "</div>\n      <div class=\"card-footer form-actions\">", $html);
    
    // Inject at the correct place
    $html = preg_replace('/<\/div>\s*<div class="card-footer form-actions">/is', "\n" . $criteriaUI . "\n      </div>\n      <div class=\"card-footer form-actions\">", $html);
    
    file_put_contents($file, $html);
    echo "edit.blade.php fixed.\n";
} else {
    echo "Could not find criteria section in edit.\n";
}
?>