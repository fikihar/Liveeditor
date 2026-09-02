<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$content = file_get_contents($file);

// Add overscroll-behavior-y: none to body and touch-action: manipulation to tabs/buttons
$search1 = "html, body { height: 100%; overflow: hidden;";
$replace1 = "html, body { height: 100%; overflow: hidden; overscroll-behavior-y: none; /* Mencegah pull-to-refresh di HP */";

if (strpos($content, 'overscroll-behavior-y') === false) {
    $content = str_replace($search1, $replace1, $content);
    
    // Add touch-action to tabs
    $search2 = ".tab { flex: 1;";
    $replace2 = ".tab { flex: 1; touch-action: manipulation; /* Mencegah double-tap zoom di HP */";
    $content = str_replace($search2, $replace2, $content);
    
    file_put_contents($file, $content);
    echo "Editor mobile UX polished.\n";
} else {
    echo "Already polished.\n";
}
?>