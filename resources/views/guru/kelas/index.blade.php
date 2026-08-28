@extends('layouts.guru')
@section('title', 'Kelas')
@section('breadcrumb', 'Manajemen kelas Anda')

@section('topbar-actions')
  <a href="{{ route('guru.kelas.create') }}" class="btn btn-primary btn-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Buat Kelas
  </a>
@endsection

@section('content')
<div class="kelas-grid">
  @forelse($classes as $kelas)
  <div class="kelas-card">
    <div class="kelas-card-top"></div>
    <div class="kelas-card-body">
      <div class="kelas-card-name">{{ $kelas->name }}</div>
      <div class="kelas-card-desc">{{ $kelas->description ?: 'Tidak ada deskripsi' }}</div>
      <div class="kelas-card-meta">
        <span class="badge badge-blue"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg> {{ $kelas->students_count }} siswa</span>
      </div>
    </div>
    <div class="kelas-card-foot">
      <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">Kelola Siswa</a>
      <a href="{{ route('guru.kelas.show', $kelas) }}"  class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">Detail</a>
      <a href="{{ route('guru.kelas.edit', $kelas) }}"  class="btn btn-ghost btn-sm">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
      </a>
    </div>
  </div>
  @empty
  <div class="card" style="grid-column:1/-1">
    <div class="empty-state">
      <div class="empty-state-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
      <h3>Belum Ada Kelas</h3>
      <p>Kelas Anda akan muncul di sini setelah dibuat</p>
      <a href="{{ route('guru.kelas.create') }}" class="btn btn-primary">Buat Kelas Pertama</a>
    </div>
  </div>
  @endforelse
</div>
@endsection