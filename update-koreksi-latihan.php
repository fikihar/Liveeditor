<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\koreksi.blade.php';
$html = file_get_contents($file);

$oldForm = <<<HTML
        @if(\$submission->status === 'draft')
HTML;

$newForm = <<<HTML
        @if(\$assignment->type === 'latihan')
          <div style="margin-top:16px;padding:12px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:6px;font-size:13px;text-align:center;">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px auto;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
             Ini adalah <b>Latihan Bebas</b>.<br>Siswa hanya bereksperimen dan sistem tidak menuntut nilai.
          </div>
          <div style="margin-top:16px;">
            <a href="{{ route('guru.tugas.show', \$assignment) }}" class="btn btn-secondary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;">Kembali ke Daftar</a>
          </div>
        @elseif(\$submission->status === 'draft')
HTML;

$html = str_replace($oldForm, $newForm, $html);
file_put_contents($file, $html);
echo "Koreksi view updated for Latihan.\n";
?>