<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\koreksi.blade.php';
$content = file_get_contents($file);

$style = <<<HTML
@section('content')
  <style>
    /* macOS style scrollbars untuk halaman koreksi */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.4); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(100, 116, 139, 0.7); }
    
    /* Supaya textarea tidak punya outline outline kaku saat diklik */
    textarea:focus { outline: none; }
  </style>
HTML;

if (strpos($content, 'macOS style scrollbars') === false) {
    $content = str_replace("@section('content')", $style, $content);
    file_put_contents($file, $content);
    echo "Scrollbar styles added to koreksi.blade.php.\n";
} else {
    echo "Scrollbar styles already exist.\n";
}
?>