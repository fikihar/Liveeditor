<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// Remove linter functions
$html = preg_replace('/function htmlLinter\(view\) \{.*?return diagnostics;\s*\}/is', '', $html);
$html = preg_replace('/function cssLinter\(view\) \{.*?return diagnostics;\s*\}/is', '', $html);

// Remove imports
$html = str_replace('import { linter, lintGutter } from "https://esm.sh/@codemirror/lint@6.0.0";', '', $html);

// Clean up extensions arrays
$html = str_replace(', linter(htmlLinter), lintGutter()', '', $html);
$html = str_replace(', linter(cssLinter), lintGutter()', '', $html);

file_put_contents($file, $html);
echo "Linter removed.\n";
?>