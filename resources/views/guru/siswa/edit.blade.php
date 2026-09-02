@extends('layouts.guru')
@section('title', 'Edit Siswa')
@section('breadcrumb', $kelas->name . ' / Edit Siswa')

@section('content')
<div style="max-width:520px">
  <div class="card">
    <div class="card-header" style="padding-bottom:18px;border-bottom:1px solid var(--slate-100)">
      <div style="display:flex;align-items:center;gap:12px">
        <div style="width:40px;height:40px;background:var(--blue-lt);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:700;color:var(--blue)">
          {{ strtoupper(substr($siswa->name, 0, 1)) }}
        </div>
        <div>
          <div class="card-header-title">{{ $siswa->name }}</div>
          <div class="card-header-sub">NIS: {{ $siswa->username }}</div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('guru.siswa.update', [$kelas, $siswa]) }}">
        @csrf @method('PUT')
        <div class="form-group">
          <label class="form-label form-label-required">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name', $siswa->name) }}" class="form-input" required>
          @error('name')<div class="form-error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label form-label-required">NIS (Username)</label>
          <input type="text" name="username" value="{{ old('username', $siswa->username) }}" class="form-input" required>
          @error('username')<div class="form-error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Password Baru</label>
          <input type="text" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
          <div class="form-hint">Biarkan kosong jika password tidak ingin diubah</div>
          @error('password')<div class="form-error-msg" style="color:var(--red);font-size:0.8rem;margin-top:4px;">{{ $message }}</div>@enderror
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection