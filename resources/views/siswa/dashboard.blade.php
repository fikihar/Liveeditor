<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#2563eb">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <title>Beranda Siswa - ClassEditor</title>
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
            <a href="{{ route('siswa.dashboard') }}" class="nav-link active">Beranda</a>
            <a href="{{ route('siswa.riwayat') }}" class="nav-link">Riwayat</a>
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
    <div class="page-header">
      <h1>Halo, {{ explode(' ', $siswa->name)[0] }}!</h1>
      <p>{{ $siswa->classRoom?->name ?? '-' }} &bull; Pemrograman Dasar</p>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Stats -->
    @php
      $tugasSemua    = $assignments->where('type','tugas');
      $latihanSemua  = $assignments->where('type','latihan');
      
      $tugasAktif    = $tugasSemua->filter(fn($a) => !($a->deadline && now()->gt($a->deadline)));
      $tugasLewat    = $tugasSemua->filter(fn($a) => ($a->deadline && now()->gt($a->deadline)));
      
      $latihanAktif  = $latihanSemua->filter(fn($a) => !($a->deadline && now()->gt($a->deadline)));
      $latihanLewat  = $latihanSemua->filter(fn($a) => ($a->deadline && now()->gt($a->deadline)));
    @endphp
    <div class="stats-row">
      <div class="stat-card" onclick="window.location.href='{{ route('siswa.riwayat') }}#tugas'" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div class="stat-value">{{ $tugasSemua->count() }}</div>
        <div class="stat-label">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:4px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          Total Tugas
        </div>
      </div>
      <div class="stat-card" onclick="window.location.href='{{ route('siswa.riwayat') }}#latihan'" style="cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
        <div class="stat-value">{{ $latihanSemua->count() }}</div>
        <div class="stat-label">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:4px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          Total Latihan
        </div>
      </div>
    </div>

    @if($assignments->count())
      
      <!-- TUGAS AKTIF -->
      @if($tugasAktif->count())
        <div class="section-label" style="margin-top:24px;">Tugas Tersedia</div>
        <div class="assignment-grid">
        @foreach($tugasAktif as $assignment)
        <a href="{{ route('siswa.editor.show', $assignment) }}" class="assignment-card">
          <div class="assignment-header">
            <div class="assignment-icon icon-tugas">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title">{{ $assignment->title }}</div>
              @if($assignment->description)
              <div class="assignment-desc">{{ Str::limit($assignment->description, 60) }}</div>
              @endif
            </div>
            <svg class="chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </div>
          <div class="assignment-footer">
            <div>
              @if($assignment->deadline)
                <span class="assignment-deadline">
                  <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;margin-right:2px;vertical-align:text-bottom;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $assignment->deadline->format('d M Y') }} &bull; {{ $assignment->deadline->format('H:i') }}
                </span>
              @else
                <span style="font-size:.75rem;color:var(--gray-400)">Tanpa Batas Waktu</span>
              @endif
            </div>
            @php $sub = $assignment->submissions->first(); @endphp
            @if($sub && $sub->status === 'submitted')
                @if($sub->score !== null)
                    <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Nilai: {{ $sub->score }}</span>
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
      @if($latihanAktif->count())
        <div class="section-label" style="margin-top:24px;">Latihan Bebas</div>
        <div class="assignment-grid">
        @foreach($latihanAktif as $assignment)
        <a href="{{ route('siswa.editor.show', $assignment) }}" class="assignment-card">
          <div class="assignment-header">
            <div class="assignment-icon icon-latihan">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <div class="assignment-info">
              <div class="assignment-title">{{ $assignment->title }}</div>
              @if($assignment->description)
              <div class="assignment-desc">{{ Str::limit($assignment->description, 60) }}</div>
              @endif
            </div>
            <svg class="chevron" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </div>
          <div class="assignment-footer">
            <span style="font-size:.75rem;color:var(--gray-400)">Latihan pendalaman materi</span>
            @php $sub = $assignment->submissions->first(); @endphp
            @if($sub && $sub->status === 'submitted')
                <span class="badge" style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;">Selesai</span>
            @else
                <span class="badge badge-latihan">Latihan</span>
            @endif
          </div>
        </a>
        @endforeach
        </div>
      @endif
      
      @if($tugasAktif->count() === 0 && $latihanAktif->count() === 0)
        <div class="empty-state">
          <div class="empty-state-icon" style="color:var(--gray-400);margin-bottom:12px">
            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
          </div>
          <h3 style="font-size:1.1rem;font-weight:600;color:var(--gray-900);margin-bottom:4px">Belum Ada Tugas Baru</h3>
          <p style="font-size:0.875rem;color:var(--gray-500);line-height:1.5">Saat ini tidak ada tugas atau latihan yang aktif.<br>Mungkin ada di Riwayat Tugas?</p>
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
            <a href="{{ route('siswa.dashboard') }}" class="bottom-nav-item active">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="{{ route('siswa.riwayat') }}" class="bottom-nav-item ">
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