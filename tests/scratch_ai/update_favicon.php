<?php
// 1. Create the SVG favicon
$svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
    <rect width="512" height="512" rx="120" fill="#2563eb" />
    <path stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M220 420l72-328M340 170l86 86-86 86M172 342l-86-86 86-86" fill="none" />
</svg>
SVG;

file_put_contents('c:\laragon\www\liveeditor\public\favicon.svg', $svg);

// 2. Inject <link rel="icon"> to all HTML headers
$files = [
    'c:\laragon\www\liveeditor\resources\views\layouts\guru.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\history.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php',
    'c:\laragon\www\liveeditor\resources\views\auth\login.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Check if it already has an icon link
    if (strpos($content, 'favicon.svg') === false) {
        $search = '<title>';
        $replace = "<link rel=\"icon\" href=\"{{ asset('favicon.svg') }}\" type=\"image/svg+xml\">\n  <title>";
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
    }
}

// 3. Rename/Remove the default Laravel favicon to avoid cache collision
if (file_exists('c:\laragon\www\liveeditor\public\favicon.ico')) {
    unlink('c:\laragon\www\liveeditor\public\favicon.ico');
}

echo "Favicon updated successfully.\n";
?>