<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

// Fix store()
$oldStore = '$assignment = Assignment::create($validated);';
$newStore = '$validated["has_css"] = $request->has("has_css");' . "\n        " . '$assignment = Assignment::create($validated);';
$content = str_replace($oldStore, $newStore, $content);

// Fix update()
$oldUpdate = '$tuga->update($validated);';
$newUpdate = '$validated["has_css"] = $request->has("has_css");' . "\n        " . '$tuga->update($validated);';
$content = str_replace($oldUpdate, $newUpdate, $content);

file_put_contents($file, $content);
echo "Controller fixed!\n";
?>