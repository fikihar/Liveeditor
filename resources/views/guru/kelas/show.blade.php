@extends('layouts.guru')
@section('title', $kelas->name)
@section('breadcrumb', 'Kelas / ' . $kelas->name)

@section('topbar-actions')
  <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-primary btn-sm">Kelola Siswa</a>
  <a href="{{ route('guru.kelas.edit', $kelas) }}"  class="btn btn-secondary btn-sm">Edit Kelas</a>
@endsection

@section('content')
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr)">
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
    <div><div class="stat-value">{{ $students->count() }}</div><div class="stat-label">Siswa</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-purple"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
    <div><div class="stat-value">{{ $assignments->count() }}</div><div class="stat-label">Tugas/Latihan</div></div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-green"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
    <div><div class="stat-value">{{ $assignments->where('status','published')->count() }}</div><div class="stat-label">Dipublikasi</div></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div><div class="card-header-title">Daftar Siswa</div></div>
    <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-secondary btn-sm">Kelola →</a>
  </div>
  <div class="table-wrap" style="border:none;border-radius:0">
    <table>
      <thead><tr><th>#</th><th>Nama</th><th>NIS</th></tr></thead>
      <tbody>
        @forelse($students as $i => $siswa)
        <tr>
          <td class="text-muted">{{ $i + 1 }}</td>
          <td><div class="td-main">{{ $siswa->name }}</div></td>
          <td><div class="td-mono">{{ $siswa->username }}</div></td>
        </tr>
        @empty
        <tr><td colspan="3"><div class="empty-state" style="padding:32px"><div class="empty-state-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div><h3>Belum ada siswa</h3></div></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection