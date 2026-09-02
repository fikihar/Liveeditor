<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php';
$content = file_get_contents($file);

// Replace the badge logic and the table row opacity
$searchTr = "<tr";
$content = preg_replace('/<tr/i', '<tr @if($tugas->deadline && now()->gt($tugas->deadline)) style="opacity: 0.65; background: #f8fafc;" @endif', $content, -1);
// Wait, the the first `<tr` is inside `<thead>`. We should only replace inside the loop.
// Let's do it safer.
$content = file_get_contents($file); // Reset
$oldLoop = "@forelse(\$assignments as \$tugas)\n          <tr>";
$newLoop = "@forelse(\$assignments as \$tugas)\n          <tr @if(\$tugas->deadline && now()->gt(\$tugas->deadline)) style=\"opacity:0.65; background:#f8fafc;\" title=\"Tugas telah ditutup (Melewati Deadline)\" @endif>";
$content = str_replace($oldLoop, $newLoop, $content);

// Replace Badge logic
$oldBadge = <<<HTML
              @if(\$tugas->status === 'published')
                <span class="badge badge-green">Dipublikasi</span>
              @else
                <span class="badge badge-gray">Draft</span>
              @endif
HTML;
$newBadge = <<<HTML
              @if(\$tugas->status === 'published')
                  @if(\$tugas->deadline && now()->gt(\$tugas->deadline))
                      <span class="badge badge-gray" style="background:#e2e8f0;color:#64748b;">Ditutup</span>
                  @else
                      <span class="badge badge-green">Aktif</span>
                  @endif
              @else
                <span class="badge badge-gray">Draft</span>
              @endif
HTML;
$content = str_replace($oldBadge, $newBadge, $content);

// Replace Submission logic
$oldSubmission = <<<HTML
          <td>
            <span class="fw-600" style="color:var(--blue)">{{ \$tugas->submissions_count }}</span>
            <span class="text-muted">siswa</span>
          </td>
HTML;
$newSubmission = <<<HTML
          <td>
            <div style="display:flex;align-items:center;gap:4px;">
              <span class="fw-600" style="color:var(--blue)">{{ \$tugas->submissions_count }}</span>
              <span class="text-muted" style="font-size:0.8rem;">/ {{ \$tugas->classRoom?->students_count ?? 0 }} siswa</span>
            </div>
            <!-- Progress bar kecil -->
            @php 
               \$pct = \$tugas->classRoom?->students_count > 0 ? round((\$tugas->submissions_count / \$tugas->classRoom->students_count) * 100) : 0;
            @endphp
            <div style="width:100%;height:4px;background:#e2e8f0;border-radius:2px;margin-top:6px;overflow:hidden;">
               <div style="height:100%;background:{{ \$pct == 100 ? '#22c55e' : 'var(--blue)' }};width:{{ \$pct }}%;"></div>
            </div>
          </td>
HTML;
$content = str_replace($oldSubmission, $newSubmission, $content);

file_put_contents($file, $content);
echo "View updated.\n";
?>