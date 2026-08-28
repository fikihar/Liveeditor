<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\AssignmentController.php';
$content = file_get_contents($file);

// Remove status from validation in both store and update
$content = str_replace("'status'       => 'required|in:draft,published',\n", "", $content);

// In store method, inject status = 'published'
$content = preg_replace('/(\$validated\[\'has_css\'\] = \$request->has\(\'has_css\'\);)/', "$1\n        \$validated['status'] = 'published';", $content);

// In update method, inject status = 'published'
// We'll just replace both instances of $validated['has_css']...
// Let's be careful.
$content = preg_replace('/(\$validated\[\'has_css\'\] = \$request->has\(\'has_css\'\);)/', "$1\n        \$validated['status'] = 'published';", $content);

file_put_contents($file, $content);
echo "AssignmentController updated.\n";
?>