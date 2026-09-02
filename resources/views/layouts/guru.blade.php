<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <title>@yield('title', 'ClassEditor') - ClassEditor</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/guru.css') }}">
  @stack('styles')
</head>
<body>

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
          </svg>
        </div>
        <div>
          <div class="sidebar-logo-text">ClassEditor</div>
          <div class="sidebar-logo-sub">Panel Guru</div>
        </div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-label">Menu</div>

      <a href="{{ route('guru.dashboard') }}"
         class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
      </a>

            <a href="{{ route('guru.kelas.index') }}"
         class="nav-item {{ request()->routeIs('guru.kelas.*') || request()->routeIs('guru.siswa.*') ? 'active' : '' }}">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Kelas & Siswa
      </a>

      <a href="{{ route('guru.tugas.index') }}"
         class="nav-item {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Tugas & Latihan
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="sidebar-user-info">
          <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
          <div class="sidebar-user-role">Guru</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="sidebar-logout" title="Keluar">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <!-- ===== TOPBAR ===== -->
  <header class="topbar">
    <div>
      <div class="topbar-title">@yield('title', 'Dashboard')</div>
      <div class="topbar-breadcrumb">@yield('breadcrumb', 'ClassEditor')</div>
    </div>
    <div class="topbar-actions">
      @yield('topbar-actions')
    </div>
  </header>

  <!-- ===== MAIN ===== -->
  <div class="main-wrap">
    <div class="main-content">

      @if(session('success'))
        <div class="alert alert-success">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:2px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-error">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:2px">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          {{ session('error') }}
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const deleteForms = document.querySelectorAll('form.form-delete');
      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const message = form.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini secara permanen?';
          Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            backdrop: `rgba(15,23,42,0.6)`
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    });
  </script>
  <script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
  </script>
</body>
</html>