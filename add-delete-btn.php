<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php';
$html = file_get_contents($file);

$oldCell = <<<HTML
              <div class="action-cell">
                <a href="{{ route('guru.tugas.show', \$tugas) }}" class="btn btn-secondary btn-sm">Lihat</a>
                <a href="{{ route('guru.tugas.edit', \$tugas) }}" class="btn btn-ghost btn-sm">Edit</a>
              </div>
HTML;

$newCell = <<<HTML
              <div class="action-cell" style="display:flex; gap:4px;">
                <a href="{{ route('guru.tugas.show', \$tugas) }}" class="btn btn-secondary btn-sm">Lihat</a>
                <a href="{{ route('guru.tugas.edit', \$tugas) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form action="{{ route('guru.tugas.destroy', \$tugas) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus secara permanen? Semua nilai dan file siswa terkait tugas/latihan ini juga akan ikut terhapus!');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626;">Hapus</button>
                </form>
              </div>
HTML;

$html = str_replace($oldCell, $newCell, $html);
file_put_contents($file, $html);
echo "Delete button added to index.\n";
?>