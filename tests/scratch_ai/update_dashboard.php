<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php';
$html = file_get_contents($file);

// 1. Add SweetAlert inside <head> if not exists
if (strpos($html, 'sweetalert2') === false) {
    $html = str_replace(
        '</head>',
        '  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' . "\n" . '  <style>
    .assignment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
  </style>' . "\n</head>",
        $html
    );
}

// 2. Fix Logout button
$logoutRegex = '/<form method="POST" action="\{\{ route\(\'logout\'\) \}\}">(.*?)<\/form>/is';
$newLogout = <<<HTML
      <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="btn-logout" onclick="confirmLogout()">Keluar</button>
      </form>
HTML;
$html = preg_replace($logoutRegex, $newLogout, $html);

// 3. Add JS for logout
$js = <<<JS
  <script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Pastikan semua kodemu sudah tersimpan/dikumpulkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        })
    }
  </script>
</body>
JS;
$html = str_replace('</body>', $js, $html);

// 4. Wrap tugas with grid and handle locking
$tugasSectionOld = <<<HTML
        <div class="section-label">Tugas Tersedia</div>
        @foreach(\$assignments->where('type','tugas') as \$assignment)
        <a href="{{ route('siswa.editor.show', \$assignment) }}" class="assignment-card">
HTML;
$tugasSectionNew = <<<HTML
        <div class="section-label">Tugas Tersedia</div>
        <div class="assignment-grid">
        @foreach(\$assignments->where('type','tugas') as \$assignment)
        @php \$isLocked = \$assignment->deadline && now()->gt(\$assignment->deadline); @endphp
        <a href="{{ \$isLocked ? 'javascript:void(0)' : route('siswa.editor.show', \$assignment) }}" 
           class="assignment-card {{ \$isLocked ? 'locked' : '' }}"
           @if(\$isLocked) onclick="Swal.fire('Terkunci!', 'Tugas ini sudah melewati batas waktu dan tidak bisa dikerjakan lagi.', 'error')" @endif>
HTML;
$html = str_replace($tugasSectionOld, $tugasSectionNew, $html);

// Close grid for tugas
$html = str_replace(
    '        @endforeach
      @endif

      <!-- Latihan Section -->',
    '        @endforeach
        </div>
      @endif

      <!-- Latihan Section -->',
    $html
);

// 5. Wrap latihan with grid
$latihanSectionOld = <<<HTML
        <div class="section-label" style="margin-top:24px">Latihan Bebas</div>
        @foreach(\$assignments->where('type','latihan') as \$assignment)
        <a href="{{ route('siswa.editor.show', \$assignment) }}" class="assignment-card">
HTML;
$latihanSectionNew = <<<HTML
        <div class="section-label" style="margin-top:24px">Latihan Bebas</div>
        <div class="assignment-grid">
        @foreach(\$assignments->where('type','latihan') as \$assignment)
        <a href="{{ route('siswa.editor.show', \$assignment) }}" class="assignment-card">
HTML;
$html = str_replace($latihanSectionOld, $latihanSectionNew, $html);

// Close grid for latihan
$html = str_replace(
    '        @endforeach
      @endif
    @else',
    '        @endforeach
        </div>
      @endif
    @else',
    $html
);

// 6. Fix Date format bug
$html = str_replace(
    "{{ \$assignment->deadline->format('d M Y &bull; H:i') }}",
    "{{ \$assignment->deadline->format('d M Y') }} &bull; {{ \$assignment->deadline->format('H:i') }}",
    $html
);

// 7. Add locked visual styling
$html = str_replace(
    '</head>',
    '  <style>.assignment-card.locked { opacity: 0.6; cursor: not-allowed; filter: grayscale(80%); }</style>' . "\n</head>",
    $html
);

file_put_contents($file, $html);
echo "Dashboard updated.\n";
?>