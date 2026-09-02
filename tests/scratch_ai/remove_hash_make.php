<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Guru\StudentController.php';
$content = file_get_contents($file);

// Replace Hash::make in store
$content = preg_replace('/\'password\'\s*=>\s*Hash::make\((.*?)\)/', "'password' => $1", $content);

// Replace Hash::make in update
$content = preg_replace('/\$siswa->password\s*=\s*Hash::make\(\$validated\[\'password\'\]\);/', "\$siswa->password = \$validated['password'];", $content);

file_put_contents($file, $content);
echo "Hash::make removed from StudentController.\n";
?>