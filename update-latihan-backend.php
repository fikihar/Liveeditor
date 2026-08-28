<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

$oldVal = "\$request->validate([\n            'score' => 'required|integer|min:0|max:100'\n        ]);";

$newVal = "if (\$tuga->type === 'latihan') {\n            return back()->with('error', 'Latihan tidak perlu dinilai.');\n        }\n\n        \$request->validate([\n            'score' => 'required|integer|min:0|max:100'\n        ]);";

$content = str_replace($oldVal, $newVal, $content);
file_put_contents($file, $content);
echo "AssignmentController updated for Latihan validation.\n";
?>