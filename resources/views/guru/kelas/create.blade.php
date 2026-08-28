@extends('layouts.guru')
@section('title', 'Buat Kelas')
@section('breadcrumb', 'Kelas / Buat Baru')

@section('content')
<div style="max-width:560px">
  <div class="card">
    <div class="card-header" style="padding-bottom:18px;border-bottom:1px solid var(--slate-100)">
      <div>
        <div class="card-header-title">Buat Kelas Baru</div>
        <div class="card-header-sub">Isi informasi kelas yang akan Anda ajar</div>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('guru.kelas.store') }}">
        @csrf
        <div class="form-group">
          <label class="form-label form-label-required">Nama Kelas</label>
          <input type="text" name="name" value="{{ old('name') }}"
                 class="form-input" placeholder="Contoh: X TJKT A" autofocus required>
          @error('name')
            <div class="form-error-msg">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <label class="form-label">Deskripsi <span class="text-muted">(opsional)</span></label>
          <textarea name="description" class="form-textarea"
                    placeholder="Keterangan singkat tentang kelas ini">{{ old('description') }}</textarea>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Simpan Kelas</button>
          <a href="{{ route('guru.kelas.index') }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection