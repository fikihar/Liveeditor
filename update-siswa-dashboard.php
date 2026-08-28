<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php';
$html = file_get_contents($file);

$oldFooter = '<span class="badge badge-tugas">Tugas</span>';
$newFooter = '
            @php $sub = $assignment->submissions->first(); @endphp
            @if($sub && $sub->status === \'submitted\')
                @if($sub->score !== null)
                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Nilai: {{ $sub->score }}</span>
                @else
                    <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">Menunggu Nilai</span>
                @endif
            @else
                <span class="badge badge-tugas">Tugas</span>
            @endif
';

$html = str_replace($oldFooter, $newFooter, $html);
file_put_contents($file, $html);
echo "Student dashboard updated with scores.\n";
?>