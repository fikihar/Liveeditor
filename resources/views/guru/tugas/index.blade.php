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
<style>
  .tab-btn { background:none; border:none; padding:12px 16px; color:#64748b; font-weight:600; cursor:pointer; font-size:0.875rem; border-bottom:2px solid transparent; transition:all 0.2s; margin-right:8px; }
  .tab-btn:hover { color:#0f172a; }
  .tab-btn.active { color:#3b82f6; border-bottom-color:#3b82f6; }
</style>

<div class="card">
  <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
    <div>
      <div class="card-header-title">Daftar Tugas & Latihan</div>
      <div class="card-header-sub">Pantau soal dan pengumpulan siswa</div>
    </div>
  </div>
  
  <!-- Tabs -->
  <div style="border-bottom: 1px solid #e2e8f0; padding: 0 20px 0 16px; margin-bottom: 0;">
    <button class="tab-btn active" onclick="filterTable('semua', this)">Semua</button>
    <button class="tab-btn" onclick="filterTable('tugas', this)">Tugas (Dinilai)</button>
    <button class="tab-btn" onclick="filterTable('latihan', this)">Latihan (Bebas)</button>
  </div>

  <div class="table-wrap" style="border:none;border-radius:0">
    <div class="table-responsive">
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
        <tr class="row-item" data-type="{{ $tugas->type }}">
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
                  @if($tugas->deadline && now()->gt($tugas->deadline))
                      <span class="badge badge-gray" style="background:#e2e8f0;color:#64748b;">Ditutup</span>
                  @else
                      <span class="badge badge-green">Aktif</span>
                  @endif
              @else
                <span class="badge badge-gray">Draft</span>
              @endif
            </div>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:4px;">
              <span class="fw-600" style="color:var(--blue)">{{ $tugas->submissions_count }}</span>
              <span class="text-muted" style="font-size:0.8rem;">/ {{ $tugas->classRoom?->students_count ?? 0 }} siswa</span>
            </div>
            <!-- Progress bar kecil -->
            @php 
               $pct = $tugas->classRoom?->students_count > 0 ? round(($tugas->submissions_count / $tugas->classRoom->students_count) * 100) : 0;
            @endphp
            <div style="width:100%;height:4px;background:#e2e8f0;border-radius:2px;margin-top:6px;overflow:hidden;">
               <div style="height:100%;background:{{ $pct == 100 ? '#22c55e' : 'var(--blue)' }};width:{{ $pct }}%;"></div>
            </div>
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
        <tr id="empty-row">
          <td colspan="6">
            <div class="empty-state">
              <div class="empty-state-icon"></div>
              <h3 id="empty-title">Belum Ada Tugas</h3>
              <p>Buat latihan ringan atau tugas untuk dikerjakan siswa</p>
              <a href="{{ route('guru.tugas.create') }}" class="btn btn-primary" style="margin-top:14px">Buat Sekarang</a>
            </div>
          </td>
        </tr>
        @endforelse
        
        <tr id="empty-filter-row" style="display:none;">
          <td colspan="6">
            <div class="empty-state">
              <div class="empty-state-icon"></div>
              <h3>Data Tidak Ditemukan</h3>
              <p>Tidak ada data untuk kategori yang dipilih.</p>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
      </div>
  </div>
</div>

@push('scripts')
<script>
  function filterTable(type, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const rows = document.querySelectorAll('tbody tr.row-item');
    let count = 0;
    rows.forEach(row => {
      if (type === 'semua' || row.dataset.type === type) {
        row.style.display = '';
        count++;
      } else {
        row.style.display = 'none';
      }
    });
    
    const emptyRow = document.getElementById('empty-row');
    const emptyFilterRow = document.getElementById('empty-filter-row');
    
    if(emptyRow && emptyRow.style.display !== 'none') {
       // if completely empty from backend
       return;
    }
    
    if (emptyFilterRow) {
        emptyFilterRow.style.display = count === 0 ? '' : 'none';
    }
  }
</script>
@endpush
@endsection