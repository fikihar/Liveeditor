@extends('layouts.guru')
@section('title', 'Edit Tugas')
@section('breadcrumb', 'Tugas / ' . $assignment->title . ' / Edit')

@section('content')
@if($errors->any())
  <div class="alert alert-error" style="margin-bottom:20px;background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:8px;color:#b91c1c;">
    <strong style="display:block;margin-bottom:8px">Mohon periksa kembali isian Anda:</strong>
    <ul style="margin:0;padding-left:20px">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('guru.tugas.update', $assignment) }}">
  @csrf @method('PUT')
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start">
    
    <div class="card">
      <div class="card-header"><div class="card-header-title">Informasi Soal</div></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label form-label-required">Judul Soal</label>
          <input type="text" name="title" value="{{ old('title', $assignment->title) }}" class="form-input" required>
        </div>
        
        <div class="form-group">
          <label class="form-label">Instruksi / Deskripsi</label>
          <textarea name="description" class="form-textarea" rows="4">{{ old('description', $assignment->description) }}</textarea>
        </div>

        <div class="divider"></div>
        <div class="form-section-title">Starter Code (Kode Awal)</div>
        
        <div class="form-group">
          <label class="form-label">HTML Awal</label>
          <textarea name="starter_html" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_html', $assignment->starter_html) }}</textarea>
        </div>
        
        <div class="form-group" style="margin-top:20px;border-top:1px dashed var(--slate-200);padding-top:16px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:12px">
            <input type="checkbox" name="has_css" value="1" id="toggleCss" {{ old('has_css', $assignment->has_css) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--blue)">
            <span class="fw-600" style="color:var(--slate-800)">Aktifkan Tab CSS untuk Siswa</span>
          </label>
        </div>
        
        <div class="form-group" id="cssGroup">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_css', $assignment->starter_css) }}</textarea>
        </div>
        
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("toggleCss");
            const group = document.getElementById("cssGroup");
            toggle.addEventListener("change", function() {
               group.style.display = this.checked ? "block" : "none";
            });
            group.style.display = toggle.checked ? "block" : "none";
          });
        </script>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div class="card-header-title">Pengaturan Tugas</div></div>
      <div class="card-body space-y-4">
        
        <div class="form-group">
          <label class="form-label form-label-required">Kelas Tujuan</label>
          <select name="class_id" class="form-select" required>
            @foreach($classes as $kelas)
              <option value="{{ $kelas->id }}" {{ old('class_id', $assignment->class_id) == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label form-label-required">Tipe Soal</label>
          <select name="type" class="form-select" required>
            <option value="latihan" {{ old('type', $assignment->type) == 'latihan' ? 'selected' : '' }}>Latihan</option>
            <option value="tugas" {{ old('type', $assignment->type) == 'tugas' ? 'selected' : '' }}>Tugas</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Batas Waktu (Deadline)</label>
          <input type="datetime-local" name="deadline" value="{{ old('deadline', $assignment->deadline?->format('Y-m-d\TH:i')) }}" class="form-input">
        </div>

        

      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary" style="width:100%">Simpan Perubahan</button>
      </div>
    </div>
    
  </div>
</form>
@endsection