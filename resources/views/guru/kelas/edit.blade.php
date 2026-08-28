@extends('layouts.guru')
@section('title', 'Edit Kelas')
@section('breadcrumb', 'Kelas / Edit')

@section('content')
<div style="max-width:560px">
  <div class="card">
    <div class="card-header" style="padding-bottom:18px;border-bottom:1px solid var(--slate-100)">
      <div>
        <div class="card-header-title">Edit Kelas</div>
        <div class="card-header-sub">{{ $kelas->name }}</div>
      </div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('guru.kelas.update', $kelas) }}">
        @csrf @method('PUT')
        <div class="form-group">
          <label class="form-label form-label-required">Nama Kelas</label>
          <input type="text" name="name" value="{{ old('name', $kelas->name) }}" class="form-input" required>
          @error('name')<div class="form-error-msg">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea name="description" class="form-textarea">{{ old('description', $kelas->description) }}</textarea>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          <a href="{{ route('guru.kelas.index') }}" class="btn btn-secondary">Batal</a>
          <form method="POST" action="{{ route('guru.kelas.destroy', $kelas) }}" onsubmit="return confirm('Hapus kelas ini?')" style="margin-left:auto">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Hapus Kelas</button>
          </form>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection