<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\create.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php'
];

foreach($files as $file) {
    $html = file_get_contents($file);
    
    // Regex to remove the status form-group
    $html = preg_replace('/<div class="form-group" style="margin-top:24px">\s*<label class="form-label form-label-required">Status Publikasi<\/label>.*?<\/div>\s*<\/div>/is', '', $html);
    
    // Add Back button to topbar-actions
    $html = preg_replace("/@section\('topbar-actions'\)/", "@section('topbar-actions')\n  <a href=\"{{ route('guru.tugas.index') }}\" class=\"btn btn-secondary btn-sm\">Kembali</a>", $html);

    file_put_contents($file, $html);
}
echo "Status removed and Back button added.\n";
?>