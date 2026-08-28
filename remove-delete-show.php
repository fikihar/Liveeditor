<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$html = file_get_contents($file);

$html = preg_replace("/@section\('topbar-actions'\).*?@endsection/is", "@section('topbar-actions')
    <div style=\"display:flex;gap:8px;align-items:center;\">
      <a href=\"{{ route('guru.tugas.index') }}\" class=\"btn btn-secondary btn-sm\">Kembali</a>
      <a href=\"{{ route('guru.kelas.show', \$assignment->class_id) }}\" class=\"btn btn-ghost btn-sm\">Lihat Kelas</a>
      <a href=\"{{ route('guru.tugas.edit', \$assignment) }}\" class=\"btn btn-secondary btn-sm\">Edit Info</a>
    </div>
@endsection", $html);

file_put_contents($file, $html);
echo "Removed delete button from show.blade.php.\n";
?>