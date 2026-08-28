<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

$readOnlyExt = "<?php echo (\$assignment->type === 'tugas' && \$submission->status === 'submitted') ? ', EditorView.editable.of(false)' : ''; ?>";
$readOnlyExt = "{{ (\$assignment->type === 'tugas' && \$submission->status === 'submitted') ? ', EditorView.editable.of(false)' : '' }}";
// Wait, I can't use PHP tag inside JS string directly, it's a blade file so {{ }} works.

$html = str_replace(
    "extensions: [basicSetup, html(), oneDark, updateListener]",
    "extensions: [basicSetup, html(), oneDark, updateListener {{ (\$assignment->type === 'tugas' && \$submission->status === 'submitted') ? ', EditorView.editable.of(false)' : '' }}]",
    $html
);

$html = str_replace(
    "extensions: [basicSetup, css(), oneDark, updateListener]",
    "extensions: [basicSetup, css(), oneDark, updateListener {{ (\$assignment->type === 'tugas' && \$submission->status === 'submitted') ? ', EditorView.editable.of(false)' : '' }}]",
    $html
);

file_put_contents($file, $html);
echo "CodeMirror read-only added.\n";
?>