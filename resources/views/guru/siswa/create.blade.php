@extends('layouts.guru')
@section('title', 'Tambah Siswa')
@section('breadcrumb', $kelas->name . ' / Tambah Siswa')

@section('content')
<div style="max-width:520px">
  <div class="card">
    <div class="card-header" style="padding-bottom:18px;border-bottom:1px solid var(--slate-100)">
      <div>
        <div class="card-header-title">Tambah Siswa</div>
        <div class="card-header-sub">Kelas {{ $kelas->name }}</div>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('guru.siswa.store', $kelas) }}">
        @csrf
        <div class="form-group">
          <label class="form-label form-label-required">Nama Lengkap</label>
          <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Ahmad Fauzi" autofocus required>
          @error('name')<div class="form-error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label form-label-required">NIS (Username Login)</label>
          <input type="text" name="username" value="{{ old('username') }}" class="form-input" placeholder="20240001" required>
          <div class="form-hint">NIS digunakan siswa untuk login</div>
          @error('username')<div class="form-error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Password</label>
          <input type="text" name="password" class="form-input" placeholder="Kosongkan = default smk1234">
          <div class="form-hint">Kosongkan untuk menggunakan password default: <strong>smk1234</strong></div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Tambah Siswa</button>
          <a href="{{ route('guru.siswa.index', $kelas) }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection