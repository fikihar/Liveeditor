<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// Expose htmlEditor to window
if (strpos($html, 'window.htmlEditor = htmlEditor;') === false) {
    $html = preg_replace(
        '/(const htmlEditor = new EditorView\(\{.*?\}\);)/s',
        "$1\n      window.htmlEditor = htmlEditor;",
        $html
    );
}

// Expose cssEditor to window
if (strpos($html, 'window.cssEditor = cssEditor;') === false) {
    $html = preg_replace(
        '/(const cssEditor = new EditorView\(\{.*?\}\);)/s',
        "$1\n        window.cssEditor = cssEditor;",
        $html
    );
}

// Modify confirmReset to use window.htmlEditor
$html = str_replace(
    'htmlEditor.dispatch({ changes: { from: 0, to: htmlEditor.state.doc.length, insert: document.getElementById(\'starter-html-data\').value } });',
    'window.htmlEditor.dispatch({ changes: { from: 0, to: window.htmlEditor.state.doc.length, insert: document.getElementById(\'starter-html-data\').value } });',
    $html
);

$html = str_replace(
    'cssEditor.dispatch({ changes: { from: 0, to: cssEditor.state.doc.length, insert: document.getElementById(\'starter-css-data\').value } });',
    'window.cssEditor.dispatch({ changes: { from: 0, to: window.cssEditor.state.doc.length, insert: document.getElementById(\'starter-css-data\').value } });',
    $html
);

file_put_contents($file, $html);
echo "Scope issue fixed.\n";
?>