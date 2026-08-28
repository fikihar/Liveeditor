<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php';
$html = file_get_contents($file);

// Target Edit button and replace with Edit + Form
$html = preg_replace(
    '/<a href="{{ route\(\'guru\.tugas\.edit\', \$tugas\) }}" class="btn btn-ghost btn-sm">Edit<\/a>/i',
    '<a href="{{ route(\'guru.tugas.edit\', $tugas) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form action="{{ route(\'guru.tugas.destroy\', $tugas) }}" method="POST" class="form-delete" data-confirm="Semua nilai dan file siswa terkait tugas ini akan ikut terhapus!" style="display:inline-block;margin:0;">
                  @csrf
                  @method(\'DELETE\')
                  <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626;">Hapus</button>
                </form>',
    $html
);
file_put_contents($file, $html);

$fileShow = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$htmlShow = file_get_contents($fileShow);
$htmlShow = preg_replace(
    '/<a href="{{ route\(\'guru\.tugas\.edit\', \$assignment\) }}" class="btn btn-secondary btn-sm">Edit Info<\/a>/i',
    '<a href="{{ route(\'guru.tugas.edit\', $assignment) }}" class="btn btn-secondary btn-sm">Edit Info</a>
    <form action="{{ route(\'guru.tugas.destroy\', $assignment) }}" method="POST" class="form-delete" data-confirm="Semua jawaban siswa akan ikut terhapus!" style="margin:0;display:inline-block;">
      @csrf
      @method(\'DELETE\')
      <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626;background:#fef2f2;border:1px solid #fecaca;">Hapus Tugas</button>
    </form>',
    $htmlShow
);
file_put_contents($fileShow, $htmlShow);

echo "Delete buttons successfully added to index and show.\n";
?>