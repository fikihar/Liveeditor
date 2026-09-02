<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#2563eb">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <title>Riwayat Tugas - ClassEditor</title>
  <link rel="stylesheet" href="{{ asset('css/siswa.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .assignment-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    .assignment-card.locked { opacity: 0.6; cursor: not-allowed; filter: grayscale(80%); }
    .nav-links { display: flex; gap: 24px; margin-left: 32px; align-items: center; }
    .nav-link { color: #bfdbfe; text-decoration: none; font-size: 0.95rem; transition: color 0.2s; padding-bottom: 2px; }
    .nav-link:hover { color: white; }
    .nav-link.active { color: white; font-weight: 600; border-bottom: 2px solid white; }
    @media(max-width: 640px) {
        .nav-links { margin-left: 16px; gap: 16px; }
        .navbar-brand span { display: none; }
    }
      /* ---- BOTTOM NAV (MOBILE ONLY) ---- */
    .bottom-nav {
        display: none;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: white;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
        border-top: 1px solid var(--gray-200);
    }
    .bottom-nav-inner {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 60px;
    }
    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: var(--gray-400);
        text-decoration: none;
        font-size: 0.65rem;
        font-weight: 600;
        flex: 1;
        height: 100%;
        transition: color 0.2s;
    }
    .bottom-nav-item svg { width: 22px; height: 22px; stroke-width: 2px; }
    .bottom-nav-item.active { color: var(--blue); }
    .bottom-nav-item.active svg { stroke-width: 2.5px; }

    @media (max-width: 640px) {
        .navbar-right form { display: none; } /* Sembunyikan tombol keluar atas di HP */
        .navbar .nav-links { display: none; } /* Hide top links on mobile */
        .bottom-nav { display: block; }
        body { padding-bottom: 70px; } /* Space for bottom nav */
    }
    </style>
</head>
<body>

  <nav class="navbar">
    <div style="display:flex;align-items:center;">
        <a href="{{ route('siswa.dashboard') }}" class="navbar-brand">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
          </svg>
          <span>ClassEditor</span>
        </a>
        <div class="nav-links">
            <a href="{{ route('siswa.dashboard') }}" class="nav-link">Beranda</a>
            <a href="{{ route('siswa.riwayat') }}" class="nav-link active">Riwayat</a>
        </div>
    </div>
    <div class="navbar-right">
      <span class="navbar-user">{{ $siswa->name }}</span>
      <form method="POST" action="{{ route('logout') }}" id="logoutForm">
        @csrf
        <button type="button" class="btn-logout" onclick="confirmLogout()">Keluar</button>
      </form>
    </div>
  </nav>

  <div class="container">

    <!-- Greeting -->
    <div class="page-header" style="margin-bottom:24px;">
      <h1>Riwayat Tugas</h1>
      <p>Daftar tugas dan latihan yang sudah kedaluwarsa</p>
    </div>

    @php
      $tugasSemua    = $assignments->where('type','tugas');
      $latihanSemua  = $assignments->where('type','latihan');
      
      $tugasLewat    = $tugasSemua->filter(fn($a) => ($a->deadline && now()->gt($a->deadline)));
      $latihanLewat  = $latihanSemua->filter(fn($a) => ($a->deadline && now()->gt($a->deadline)));
    @endphp
    
    @if($tugasLewat->count() || $latihanLewat->count())
      
      <!-- RIWAYAT TUGAS KADALUARSA -->
      @if($tugasLewat->count())
        <div id="tugas" class="section-label" style="color:#94a3b8;">Tugas (Kedaluwarsa)</div>
        <div class="assignment-grid">
        @foreach($tugasLewat as $assignment)
        <a href="javascript:void(0)" class="assignment-card locked" onclick="Swal.fire('Terkunci!', 'Tugas ini sudah melewati batas waktu dan tidak bisa dikerjakan lagi.', 'error')">
          <div class="assignment-header">
            <div class="assignment-icon icon-tugas" style="background:#f1f5f9;color:#94a3b8;">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title" style="color:#64748b;">{{ $assignment->title }}</div>
            </div>
          </div>
          <div class="assignment-footer">
            <div>
                <span class="assignment-deadline" style="color:#ef4444;">
                  Batas: {{ $assignment->deadline->format('d M Y') }} &bull; {{ $assignment->deadline->format('H:i') }}
                </span>
            </div>
            @php $sub = $assignment->submissions->first(); @endphp
            @if($sub && $sub->status === 'submitted')
                @if($sub->score !== null)
                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Nilai: {{ $sub->score }}</span>
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
      @if($latihanLewat->count())
        <div id="latihan" class="section-label" style="margin-top:24px;color:#94a3b8;">Latihan (Kedaluwarsa)</div>
        <div class="assignment-grid">
        @foreach($latihanLewat as $assignment)
        <a href="javascript:void(0)" class="assignment-card locked" onclick="Swal.fire('Terkunci!', 'Latihan ini sudah melewati batas waktu dan tidak bisa dikerjakan lagi.', 'error')">
          <div class="assignment-header">
            <div class="assignment-icon icon-latihan" style="background:#f1f5f9;color:#94a3b8;">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title" style="color:#64748b;">{{ $assignment->title }}</div>
            </div>
          </div>
          <div class="assignment-footer">
            <span class="assignment-deadline" style="color:#ef4444;">
               Batas: {{ $assignment->deadline->format('d M Y') }} &bull; {{ $assignment->deadline->format('H:i') }}
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
        <h3 style="font-size:1.1rem;font-weight:600;color:var(--gray-900);margin-bottom:4px">Belum Ada Riwayat</h3>
        <p style="font-size:0.875rem;color:var(--gray-500);line-height:1.5">Tidak ada tugas atau latihan yang sudah kedaluwarsa.</p>
      </div>
    @endif

  </div>

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
    <!-- BOTTOM NAVIGATION (MOBILE) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('siswa.dashboard') }}" class="bottom-nav-item ">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="{{ route('siswa.riwayat') }}" class="bottom-nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat
            </a>
            <a href="#" class="bottom-nav-item" onclick="event.preventDefault(); confirmLogout();">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>
        </div>
    </nav>
  </body>
</html>