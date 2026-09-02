<?php
$file = 'c:\laragon\www\liveeditor\app\Imports\StudentsImport.php';
$content = file_get_contents($file);

$content = preg_replace('/\'password\'\s*=>\s*Hash::make\((.*?)\)/', "'password' => $1", $content);

file_put_contents($file, $content);
echo "Hash::make removed from StudentsImport.\n";
?>