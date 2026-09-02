<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php';
$html = file_get_contents($file);

// Find where stats-row ends
$startPos = strpos($html, '<div class="stats-row">');
// Find closing </div> of stats-row
$endStatsRow = strpos($html, '</div>', strpos($html, '</div>', strpos($html, '</div>', $startPos) + 1) + 1) + 6;

// I will just extract everything AFTER stats-row up to </body> and replace it.
$contentToReplace = substr($html, $endStatsRow, strpos($html, '  <script>', $endStatsRow) - $endStatsRow);

$newBody = <<<HTML

    @if(\$assignments->count())
      
      <!-- TUGAS AKTIF -->
      @if(\$tugasAktif->count())
        <div class="section-label" style="margin-top:24px;">Tugas Tersedia</div>
        <div class="assignment-grid">
        @foreach(\$tugasAktif as \$assignment)
        <a href="{{ route('siswa.editor.show', \$assignment) }}" class="assignment-card">
          <div class="assignment-header">
            <div class="assignment-icon icon-tugas">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title">{{ \$assignment->title }}</div>
              @if(\$assignment->description)
              <div class="assignment-desc">{{ Str::limit(\$assignment->description, 60) }}</div>
              @endif
            </div>
            <svg class="chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </div>
          <div class="assignment-footer">
            <div>
              @if(\$assignment->deadline)
                <span class="assignment-deadline">
                  <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:2px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ \$assignment->deadline->format('d M Y') }} &bull; {{ \$assignment->deadline->format('H:i') }}
                </span>
              @else
                <span style="font-size:.75rem;color:var(--gray-400)">Tanpa Batas Waktu</span>
              @endif
            </div>
            @php \$sub = \$assignment->submissions->first(); @endphp
            @if(\$sub && \$sub->status === 'submitted')
                @if(\$sub->score !== null)
                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Nilai: {{ \$sub->score }}</span>
                @else
                    <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">Menunggu Nilai</span>
                @endif
            @else
                <span class="badge badge-tugas">Tugas</span>
            @endif
          </div>
        </a>
        @endforeach
        </div>
      @endif

      <!-- LATIHAN AKTIF -->
      @if(\$latihanAktif->count())
        <div class="section-label" style="margin-top:24px;">Latihan Bebas</div>
        <div class="assignment-grid">
        @foreach(\$latihanAktif as \$assignment)
        <a href="{{ route('siswa.editor.show', \$assignment) }}" class="assignment-card">
          <div class="assignment-header">
            <div class="assignment-icon icon-latihan">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title">{{ \$assignment->title }}</div>
              @if(\$assignment->description)
              <div class="assignment-desc">{{ Str::limit(\$assignment->description, 60) }}</div>
              @endif
            </div>
            <svg class="chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </div>
          <div class="assignment-footer">
            <span style="font-size:.75rem;color:var(--gray-400)">Latihan pendalaman materi</span>
            @php \$sub = \$assignment->submissions->first(); @endphp
            @if(\$sub && \$sub->status === 'submitted')
                <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Selesai</span>
            @else
                <span class="badge badge-latihan">Latihan</span>
            @endif
          </div>
        </a>
        @endforeach
        </div>
      @endif

      <!-- RIWAYAT TUGAS KADALUARSA -->
      @if(\$tugasLewat->count())
        <div id="riwayat-tugas" class="section-label" style="margin-top:40px;color:#94a3b8;border-top:1px solid #e2e8f0;padding-top:24px;">Riwayat Tugas (Kedaluwarsa)</div>
        <div class="assignment-grid">
        @foreach(\$tugasLewat as \$assignment)
        <a href="javascript:void(0)" class="assignment-card locked" onclick="Swal.fire('Terkunci!', 'Tugas ini sudah melewati batas waktu dan tidak bisa dikerjakan lagi.', 'error')">
          <div class="assignment-header">
            <div class="assignment-icon icon-tugas" style="background:#f1f5f9;color:#94a3b8;">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title" style="color:#64748b;">{{ \$assignment->title }}</div>
            </div>
          </div>
          <div class="assignment-footer">
            <div>
                <span class="assignment-deadline" style="color:#ef4444;">
                  Melewati batas: {{ \$assignment->deadline->format('d M Y') }} &bull; {{ \$assignment->deadline->format('H:i') }}
                </span>
            </div>
            @php \$sub = \$assignment->submissions->first(); @endphp
            @if(\$sub && \$sub->status === 'submitted')
                @if(\$sub->score !== null)
                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Nilai: {{ \$sub->score }}</span>
                @else
                    <span class="badge" style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;">Menunggu Nilai</span>
                @endif
            @else
                <span class="badge" style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;">Tidak Mengumpulkan</span>
            @endif
          </div>
        </a>
        @endforeach
        </div>
      @endif
      
      <!-- RIWAYAT LATIHAN KADALUARSA -->
      @if(\$latihanLewat->count())
        <div id="riwayat-latihan" class="section-label" style="margin-top:24px;color:#94a3b8;">Riwayat Latihan (Kedaluwarsa)</div>
        <div class="assignment-grid">
        @foreach(\$latihanLewat as \$assignment)
        <a href="javascript:void(0)" class="assignment-card locked" onclick="Swal.fire('Terkunci!', 'Latihan ini sudah melewati batas waktu dan tidak bisa dikerjakan lagi.', 'error')">
          <div class="assignment-header">
            <div class="assignment-icon icon-latihan" style="background:#f1f5f9;color:#94a3b8;">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title" style="color:#64748b;">{{ \$assignment->title }}</div>
            </div>
          </div>
          <div class="assignment-footer">
            <span class="assignment-deadline" style="color:#ef4444;">
               Melewati batas: {{ \$assignment->deadline->format('d M Y') }} &bull; {{ \$assignment->deadline->format('H:i') }}
            </span>
          </div>
        </a>
        @endforeach
        </div>
      @endif

    @else
      <div class="empty-state">
        <div class="empty-state-icon" style="color:var(--gray-400);margin-bottom:12px">
          <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <h3 style="font-size:1.1rem;font-weight:600;color:var(--gray-900);margin-bottom:4px">Belum Ada Soal</h3>
        <p style="font-size:0.875rem;color:var(--gray-500);line-height:1.5">Guru belum memberikan tugas atau latihan.<br>Tunggu sebentar ya!</p>
      </div>
    @endif

  </div>
HTML;

$html = str_replace($contentToReplace, $newBody, $html);
file_put_contents($file, $html);
echo "Dashboard grid rewritten successfully.\n";
?>