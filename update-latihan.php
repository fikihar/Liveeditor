<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php';
$html = file_get_contents($file);

// Update bagian Latihan footer
$oldLatihanFooter = <<<HTML
          <div class="assignment-footer">
            <span style="font-size:.75rem;color:var(--gray-400)">Latihan pendalaman materi</span>
            <span class="badge badge-latihan">Latihan</span>
          </div>
HTML;

$newLatihanFooter = <<<HTML
          <div class="assignment-footer">
            <span style="font-size:.75rem;color:var(--gray-400)">Latihan pendalaman materi</span>
            @php \$sub = \$assignment->submissions->first(); @endphp
            @if(\$sub && \$sub->status === 'submitted')
                <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">
                  <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:2px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  Selesai
                </span>
            @else
                <span class="badge badge-latihan">Latihan</span>
            @endif
          </div>
HTML;
$html = str_replace($oldLatihanFooter, $newLatihanFooter, $html);
file_put_contents($file, $html);
echo "Siswa Dashboard updated for Latihan.\n";

$fileEditor = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$htmlEditor = file_get_contents($fileEditor);

$oldModal = <<<HTML
      <h3 class="modal-title">Kumpulkan Tugas?</h3>
      <p class="modal-text">Pastikan kode HTML dan CSS kamu sudah berjalan dengan baik. Tugas yang sudah dikumpulkan tidak bisa diubah lagi.</p>
HTML;

$newModal = <<<HTML
      <h3 class="modal-title">{{ \$assignment->type === 'latihan' ? 'Selesaikan Latihan?' : 'Kumpulkan Tugas?' }}</h3>
      <p class="modal-text">
        @if(\$assignment->type === 'latihan')
          Pastikan kamu sudah puas bereksperimen. Latihan yang sudah diselesaikan akan ditutup.
        @else
          Pastikan kode HTML dan CSS kamu sudah berjalan dengan baik. Tugas yang sudah dikumpulkan tidak bisa diubah lagi dan akan menunggu penilaian Guru.
        @endif
      </p>
HTML;
$htmlEditor = str_replace($oldModal, $newModal, $htmlEditor);

// Update tombol Kumpulkan di topbar agar teksnya sesuai
$oldBtnSubmit = '<button class="btn-submit" onclick="submitWork()">Kumpulkan</button>';
$newBtnSubmit = '<button class="btn-submit" onclick="submitWork()">{{ $assignment->type === \'latihan\' ? \'Selesai\' : \'Kumpulkan\' }}</button>';
$htmlEditor = str_replace($oldBtnSubmit, $newBtnSubmit, $htmlEditor);

file_put_contents($fileEditor, $htmlEditor);
echo "Siswa Editor updated for Latihan vs Tugas.\n";
?>