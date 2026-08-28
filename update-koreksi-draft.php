<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\koreksi.blade.php';
$html = file_get_contents($file);

// 1. Perbesar nama
$oldName = '<h4 style="font-weight:700;font-size:16px;margin-bottom:4px;color:var(--slate-800);">{{ $siswa->name }}</h4>';
$newName = '<h4 style="font-weight:800;font-size:22px;margin-bottom:2px;color:var(--slate-900);">{{ $siswa->name }}</h4>';
$html = str_replace($oldName, $newName, $html);

// 2. Disable form jika status draft
$oldForm = <<<HTML
        <form action="{{ route('guru.tugas.nilai', [\$assignment->id, \$siswa->id]) }}" method="POST">
          @csrf
          <div class="form-group" style="margin-bottom:12px;">
            <label class="form-label" style="font-weight:600;color:var(--slate-700);">Beri Nilai (0-100)</label>
            <input type="number" name="score" class="form-input" value="{{ old('score', \$submission->score) }}" min="0" max="100" style="font-size:24px;font-weight:700;text-align:center;padding:12px;" placeholder="0" required>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;">Simpan Nilai</button>
        </form>
HTML;

$newForm = <<<HTML
        @if(\$submission->status === 'draft')
          <div style="margin-top:16px;padding:12px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:6px;font-size:13px;text-align:center;">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px auto;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
             Siswa belum menekan tombol <b>Kumpulkan</b>.<br>Tugas masih berupa Draft.
          </div>
          <div style="opacity:0.5;pointer-events:none;margin-top:16px;">
            <div class="form-group" style="margin-bottom:12px;">
              <label class="form-label" style="font-weight:600;color:var(--slate-700);">Beri Nilai (0-100)</label>
              <input type="text" class="form-input" value="-" style="font-size:24px;font-weight:700;text-align:center;padding:12px;" disabled>
            </div>
            <button type="button" class="btn btn-secondary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;" disabled>Belum Bisa Dinilai</button>
          </div>
        @else
          <form action="{{ route('guru.tugas.nilai', [\$assignment->id, \$siswa->id]) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:12px;">
              <label class="form-label" style="font-weight:600;color:var(--slate-700);">Beri Nilai (0-100)</label>
              <input type="number" name="score" class="form-input" value="{{ old('score', \$submission->score) }}" min="0" max="100" style="font-size:24px;font-weight:700;text-align:center;padding:12px;" placeholder="0" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;">Simpan Nilai</button>
          </form>
        @endif
HTML;

$html = str_replace($oldForm, $newForm, $html);
file_put_contents($file, $html);
echo "Koreksi view updated with draft blocking.\n";
?>