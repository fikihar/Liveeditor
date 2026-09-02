@extends('layouts.guru')
@section('title', 'Siswa — ' . $kelas->name)
@section('breadcrumb', 'Kelas / ' . $kelas->name . ' / Siswa')

@section('topbar-actions')
  <a href="{{ route('guru.siswa.create', $kelas) }}" class="btn btn-secondary btn-sm">+ Tambah Manual</a>
  <button onclick="document.getElementById('importModal').classList.add('open')" class="btn btn-primary btn-sm">
    📂 Import Excel
  </button>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <div class="card-header-title">Daftar Siswa</div>
      <div class="card-header-sub">{{ $students->count() }} siswa terdaftar di {{ $kelas->name }}</div>
    </div>
  </div>
  <div class="table-wrap" style="border:none;border-radius:0">
    <div class="table-responsive">
        <table>
      <thead>
        <tr>
          <th style="width:48px">#</th>
          <th>Nama Siswa</th>
          <th>NIS (Username)</th>
          <th style="width:140px">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($students as $i => $siswa)
        <tr>
          <td class="text-muted">{{ $i + 1 }}</td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:32px;height:32px;background:var(--blue-lt);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:var(--blue);flex-shrink:0">
                {{ strtoupper(substr($siswa->name, 0, 1)) }}
              </div>
              <div>
                <div class="td-main">{{ $siswa->name }}</div>
              </div>
            </div>
          </td>
          <td><span class="td-mono">{{ $siswa->username }}</span></td>
          <td>
            <div class="action-cell">
              <a href="{{ route('guru.siswa.edit', [$kelas, $siswa]) }}" class="btn btn-ghost btn-sm">Edit</a>
              <form method="POST" action="{{ route('guru.siswa.destroy', [$kelas, $siswa]) }}"
                    onsubmit="return confirm('Hapus {{ $siswa->name }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4">
            <div class="empty-state">
              <div class="empty-state-icon"><svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
              <h3>Belum Ada Siswa</h3>
              <p>Tambah siswa secara manual atau import dari Excel</p>
              <a href="{{ route('guru.siswa.create', $kelas) }}" class="btn btn-primary" style="margin-top:14px">+ Tambah Siswa</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
      </div>
  </div>
</div>

<!-- Modal Import -->
<div id="importModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title">Import Siswa dari Excel</div>
      <button class="modal-close" onclick="document.getElementById('importModal').classList.remove('open')">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <div class="import-format">
        <strong>Format kolom Excel yang dibutuhkan:</strong><br>
        Baris 1 = judul kolom: <code>nama</code> | <code>nis</code> | <code>password</code> (opsional)<br>
        <span class="text-muted">Password default jika kosong: <strong>smk1234</strong></span>
      </div>
      <form method="POST" action="{{ route('guru.siswa.import', $kelas) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label class="form-label form-label-required">Pilih File Excel</label>
          <input type="file" name="file" accept=".xlsx,.xls" class="form-input" required>
        </div>
        <div class="modal-footer" style="padding:0;border:0;margin-top:8px">
          <button type="button" onclick="document.getElementById('importModal').classList.remove('open')" class="btn btn-secondary">Batal</button>
          <button type="submit" class="btn btn-primary">Import Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection