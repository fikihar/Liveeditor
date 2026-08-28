@extends('layouts.guru')
@section('title', 'Dashboard')
@section('breadcrumb', 'Selamat datang kembali!')

@section('topbar-actions')
  <a href="{{ route('guru.kelas.create') }}" class="btn btn-primary btn-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Buat Kelas
  </a>
@endsection

@section('content')
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
    <div>
      <div class="stat-value">{{ $totalKelas }}</div>
      <div class="stat-label">Total Kelas</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-green"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
    <div>
      <div class="stat-value">{{ $totalSiswa }}</div>
      <div class="stat-label">Total Siswa</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-purple"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
    <div>
      <div class="stat-value">{{ $totalAssignment }}</div>
      <div class="stat-label">Tugas/Latihan</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-orange"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div>
      <div class="stat-value">{{ $totalSubmission }}</div>
      <div class="stat-label">Dikumpulkan</div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-header-title">Kelas Aktif</div>
      <div class="card-header-sub">Kelola kelas dan pantau aktivitas siswa</div>
    </div>
    <a href="{{ route('guru.kelas.create') }}" class="btn btn-secondary btn-sm">+ Kelas Baru</a>
  </div>
  <div class="card-body" style="padding:0">
    @forelse($classes as $kelas)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 22px;border-bottom:1px solid var(--slate-100)">
      <div style="display:flex;align-items:center;gap:14px">
        <div style="width:40px;height:40px;background:var(--blue-lt);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
        <div>
          <div class="fw-600" style="color:var(--slate-800)">{{ $kelas->name }}</div>
          <div class="text-sm text-muted">{{ $kelas->students_count }} siswa terdaftar</div>
        </div>
      </div>
      <div class="action-cell">
        <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-secondary btn-sm">Kelola Siswa</a>
        <a href="{{ route('guru.kelas.show', $kelas) }}" class="btn btn-ghost btn-sm">Detail →</a>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <div class="empty-state-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
      <h3>Belum ada kelas</h3>
      <p>Buat kelas pertama Anda untuk mulai mengajar</p>
      <a href="{{ route('guru.kelas.create') }}" class="btn btn-primary">Buat Kelas Sekarang</a>
    </div>
    @endforelse
  </div>
</div>
@endsection