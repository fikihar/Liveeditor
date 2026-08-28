<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

$content = str_replace(
    "\$validated[\"has_css\"] = \$request->has(\"has_css\");",
    "\$validated[\"has_css\"] = \$request->has(\"has_css\");\n        \$validated['status'] = 'published';",
    $content
);

file_put_contents($file, $content);
echo "Controller fixed.\n";
?>