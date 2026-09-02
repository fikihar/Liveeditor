<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\siswa\edit.blade.php';
$content = file_get_contents($file);

$search = '<div class="form-hint">Biarkan kosong jika password tidak ingin diubah</div>';
$replace = $search . "\n          @error('password')<div class=\"form-error-msg\" style=\"color:var(--red);font-size:0.8rem;margin-top:4px;\">{{ \$message }}</div>@enderror";

if (strpos($content, "@error('password')") === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Added error message for password.\n";
}
?>