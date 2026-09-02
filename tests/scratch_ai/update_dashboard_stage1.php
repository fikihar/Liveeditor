<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php';
$html = file_get_contents($file);

// 1. Setup PHP Collections
$phpSetup = <<<PHP
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
      \$tugasSemua    = \$assignments->where('type','tugas');
      \$latihanSemua  = \$assignments->where('type','latihan');
      
      \$tugasAktif    = \$tugasSemua->filter(fn(\$a) => !(\$a->deadline && now()->gt(\$a->deadline)));
      \$tugasLewat    = \$tugasSemua->filter(fn(\$a) => (\$a->deadline && now()->gt(\$a->deadline)));
      
      \$latihanAktif  = \$latihanSemua->filter(fn(\$a) => !(\$a->deadline && now()->gt(\$a->deadline)));
      \$latihanLewat  = \$latihanSemua->filter(fn(\$a) => (\$a->deadline && now()->gt(\$a->deadline)));
    @endphp
PHP;
$html = preg_replace('/@if\(session\(\'success\'\)\).*?@endphp/is', $phpSetup, $html);

// 2. Make Stat Cards Clickable
$statRowRegex = '/<div class="stats-row">.*?<\/div>\s*<\/div>\s*<\/div>/is';
$newStatRow = <<<HTML
    <div class="stats-row">
      <div class="stat-card" onclick="document.getElementById('riwayat-tugas').scrollIntoView({behavior: 'smooth'})" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div class="stat-value">{{ \$tugasSemua->count() }}</div>
        <div class="stat-label">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:4px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          Total Tugas (Klik Riwayat)
        </div>
      </div>
      <div class="stat-card" onclick="document.getElementById('riwayat-latihan').scrollIntoView({behavior: 'smooth'})" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div class="stat-value">{{ \$latihanSemua->count() }}</div>
        <div class="stat-label">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:4px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          Total Latihan (Klik Riwayat)
        </div>
      </div>
    </div>
HTML;
$html = preg_replace($statRowRegex, $newStatRow, $html);

// We need to carefully replace the loops. I'll just write a script that completely rebuilds the body of the container.
?>