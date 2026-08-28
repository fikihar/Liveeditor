@extends('layouts.guru')
@section('title', 'Manajemen Tugas')
@section('breadcrumb', 'Semua Tugas & Latihan')

@section('topbar-actions')
  <a href="{{ route('guru.tugas.create') }}" class="btn btn-primary btn-sm">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Buat Tugas Baru
  </a>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <div class="card-header-title">Daftar Tugas & Latihan</div>
      <div class="card-header-sub">Pantau soal dan pengumpulan siswa</div>
    </div>
  </div>
  <div class="table-wrap" style="border:none;border-radius:0">
    <table>
      <thead>
        <tr>
          <th>Soal</th>
          <th>Kelas</th>
          <th>Tipe / Status</th>
          <th>Pengumpulan</th>
          <th>Deadline</th>
          <th style="width:140px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($assignments as $tugas)
        <tr>
          <td>
            <div class="td-main">{{ $tugas->title }}</div>
            <div class="td-sub">{{ Str::limit($tugas->description, 50) }}</div>
          </td>
          <td>
            <a href="{{ route('guru.kelas.show', $tugas->class_id) }}" class="td-main" style="color:var(--blue)">
              {{ $tugas->classRoom?->name ?? "Kelas Dihapus" }}
            </a>
          </td>
          <td>
            <div style="display:flex;gap:6px">
              @if($tugas->type === 'tugas')
                <span class="badge badge-red">Tugas</span>
              @else
                <span class="badge badge-blue">Latihan</span>
              @endif

              @if($tugas->status === 'published')
                <span class="badge badge-green">Dipublikasi</span>
              @else
                <span class="badge badge-gray">Draft</span>
              @endif
            </div>
          </td>
          <td>
            <span class="fw-600" style="color:var(--blue)">{{ $tugas->submissions_count }}</span>
            <span class="text-muted">siswa</span>
          </td>
          <td>
            @if($tugas->deadline)
              <div class="td-main">{{ $tugas->deadline->format('d M Y') }}</div>
              <div class="td-sub">{{ $tugas->deadline->format('H:i') }} WIB</div>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            <div class="action-cell">
              <a href="{{ route('guru.tugas.show', $tugas) }}" class="btn btn-secondary btn-sm">Lihat</a>
              <a href="{{ route('guru.tugas.edit', $tugas) }}" class="btn btn-ghost btn-sm">Edit</a>
                <form action="{{ route('guru.tugas.destroy', $tugas) }}" method="POST" class="form-delete" data-confirm="Semua nilai dan file siswa terkait tugas ini akan ikut terhapus!" style="display:inline-block;margin:0;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-ghost btn-sm" style="color:#dc2626;">Hapus</button>
                </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="empty-state">
              <div class="empty-state-icon"></div>
              <h3>Belum Ada Tugas</h3>
              <p>Buat latihan ringan atau tugas untuk dikerjakan siswa</p>
              <a href="{{ route('guru.tugas.create') }}" class="btn btn-primary" style="margin-top:14px">Buat Sekarang</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection