<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

// Fix the syntax error: replace "} } else {" with "} else {"
$content = str_replace("}\n        } else {", "} else {", $content);

file_put_contents($file, $content);
echo "Syntax error fixed.\n";
?>