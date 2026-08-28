<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// 1. Ubah tombol kembali (btn-back)
$oldBack = '<a href="{{ route(\'siswa.dashboard\') }}" class="btn-back" onclick="saveDraft(event)">';
$newBack = <<<HTML
        @if(\$assignment->type === 'tugas' && \$submission->status === 'submitted')
          <a href="{{ route('siswa.dashboard') }}" class="btn-back">
        @else
          <a href="{{ route('siswa.dashboard') }}" class="btn-back" onclick="saveDraft(event)">
        @endif
HTML;
$html = str_replace($oldBack, $newBack, $html);

// 2. Ubah tombol Submit
$oldSubmit = '<button class="btn-submit" onclick="submitWork()">{{ $assignment->type === \'latihan\' ? \'Selesai\' : \'Kumpulkan\' }}</button>';
$newSubmit = <<<HTML
      @if(\$assignment->type === 'tugas' && \$submission->status === 'submitted')
        <button class="btn-submit" style="background:rgba(255,255,255,0.1);color:#94a3b8;cursor:not-allowed;" disabled>Terkunci</button>
      @else
        <button class="btn-submit" onclick="submitWork()">{{ \$assignment->type === 'latihan' ? 'Selesai' : 'Kumpulkan' }}</button>
      @endif
HTML;
$html = str_replace($oldSubmit, $newSubmit, $html);

// 3. (Opsional tapi keren) Disable CodeMirror di JS jika sudah submitted
$oldJS = "lineNumbers: true,\n        theme: 'one-dark'";
$newJS = "lineNumbers: true,\n        theme: 'one-dark',\n        readOnly: {{ (\$assignment->type === 'tugas' && \$submission->status === 'submitted') ? 'true' : 'false' }}";
$html = preg_replace("/lineNumbers:\s*true,\s*theme:\s*'one-dark'/is", $newJS, $html);

file_put_contents($file, $html);
echo "Frontend protection implemented.\n";
?>