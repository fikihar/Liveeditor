<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

$search = "\$assignments = Assignment::with(['classRoom'])";
$replace = "\$assignments = Assignment::with(['classRoom' => function(\$q) { \$q->withCount('students'); }])";

if (strpos($content, $replace) === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Controller updated with students count.\n";
}
?>