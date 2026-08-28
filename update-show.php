<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$html = file_get_contents($file);

$oldBtn = '<button class="btn btn-secondary btn-sm" disabled title="Tersedia di update selanjutnya">Lihat Kode</button>';
$newBtn = '
                @if($sub)
                  <a href="{{ route(\'guru.tugas.koreksi\', [\'tuga\' => $assignment->id, \'siswa\' => $siswa->id]) }}" class="btn btn-primary btn-sm">Lihat & Koreksi</a>
                @else
                  <button class="btn btn-secondary btn-sm" disabled>Belum Tersedia</button>
                @endif
';

$html = str_replace($oldBtn, $newBtn, $html);

// Now, let's also show the score in the Status column if it's graded.
$oldStatus = '<span class="badge badge-green">Terkumpul</span>';
$newStatus = '
                  @if($sub->score !== null)
                    <span class="badge badge-green">Dinilai ({{ $sub->score }})</span>
                  @else
                    <span class="badge badge-blue">Terkumpul</span>
                  @endif
';

$html = str_replace($oldStatus, $newStatus, $html);

file_put_contents($file, $html);
echo "View updated.\n";
?>