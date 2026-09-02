<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$content = file_get_contents($file);

$search = '<a href="{{ route(\'guru.tugas.edit\', $assignment) }}" class="btn btn-secondary btn-sm">Edit Info</a>';
$replace = <<<HTML
        <a href="{{ route('guru.tugas.edit', \$assignment) }}" class="btn btn-secondary btn-sm">Edit Info</a>
        <form action="{{ route('guru.tugas.force_submit', \$assignment) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tarik paksa semua tugas siswa yang belum dikumpulkan? (Status Draft akan diubah menjadi Dikumpulkan dan dinilai otomatis jika ada kriteria)')">
          @csrf
          <button type="submit" class="btn btn-primary btn-sm" style="background:#ef4444;border-color:#ef4444;">Tarik Paksa (Force Submit)</button>
        </form>
HTML;

if (strpos($content, 'Tarik Paksa') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Button added.\n";
}
?>