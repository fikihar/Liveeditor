<?php
$file = 'c:\laragon\www\liveeditor\database\seeders\DatabaseSeeder.php';
$content = file_get_contents($file);

$content = preg_replace('/\'password\'\s*=>\s*Hash::make\((.*?)\)/', "'password' => $1", $content);

file_put_contents($file, $content);
echo "Seeder updated.\n";
?>