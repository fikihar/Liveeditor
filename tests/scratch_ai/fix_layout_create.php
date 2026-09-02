<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\create.blade.php';
$html = file_get_contents($file);

// Extract the criteria section
if (preg_match('/<div id="criteria-section">.*?<\/button>\s*<\/div>/is', $html, $matches)) {
    $criteriaUI = $matches[0];
    
    // Remove it from its current bad location
    // The bad location is: </div>\s*<div id="criteria-section">...</div>\s*</div>\s*<div class="card-footer form-actions">
    // Let's just remove the criteriaUI and the stray </div>
    $html = str_replace($criteriaUI . "\n      </div>\n      <div class=\"card-footer form-actions\">", '<div class="card-footer form-actions">', $html);
    
    // In case the above str_replace didn't catch it due to spacing:
    $html = preg_replace('/<\/div>\s*<div id="criteria-section">.*?<\/button>\s*<\/div>\s*<\/div>\s*<div class="card-footer form-actions">/is', "</div>\n      <div class=\"card-footer form-actions\">", $html);
    
    // Now correctly inject it INSIDE the card-body, right before its closing div
    // The closing div of card body is the one right before card-footer.
    // So we look for:
    // </div>
    // <div class="card-footer form-actions">
    // and replace with:
    // $criteriaUI
    // </div>
    // <div class="card-footer form-actions">
    
    // But wait, the criteriaUI we extracted already has <hr> at the top.
    
    $html = preg_replace('/<\/div>\s*<div class="card-footer form-actions">/is', "\n" . $criteriaUI . "\n      </div>\n      <div class=\"card-footer form-actions\">", $html);
    
    file_put_contents($file, $html);
    echo "create.blade.php fixed.\n";
} else {
    echo "Could not find criteria section in create.\n";
}
?>