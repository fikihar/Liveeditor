@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')
@section('breadcrumb', 'Tugas / Buat Baru')

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

<form method="POST" action="{{ route('guru.tugas.store') }}">
  @csrf
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start">
    
    <div class="card">
      <div class="card-header"><div class="card-header-title">Informasi Soal</div></div>
      <div class="card-body">
        <div class="form-group">
          <label class="form-label form-label-required">Judul Soal</label>
          <input type="text" name="title" value="{{ old('title') }}" class="form-input" placeholder="Contoh: Membuat Layout CSS Grid" required>
        </div>
        
        <div class="form-group">
          <label class="form-label">Instruksi / Deskripsi</label>
          <textarea name="description" class="form-textarea" placeholder="Tuliskan petunjuk pengerjaan di sini..." rows="4">{{ old('description') }}</textarea>
        </div>

        <div class="divider"></div>
        <div class="form-section-title">Starter Code (Kode Awal)</div>
        <div class="form-hint" style="margin-bottom:12px;margin-top:-10px">Siswa akan melihat kode ini saat pertama kali membuka editor.</div>
        
        <div class="form-group">
          <label class="form-label">HTML Awal</label>
          <textarea name="starter_html" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_html', "<!DOCTYPE html>\n<html lang='id'>\n<head>\n  <meta charset='UTF-8'>\n  <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n  <title>Tugas</title>\n</head>\n<body>\n  <!-- Tulis kode di sini -->\n  \n</body>\n</html>") }}</textarea>
        </div>
        
        <div class="form-group" style="margin-top:20px;border-top:1px dashed var(--slate-200);padding-top:16px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:12px">
            <input type="checkbox" name="has_css" value="1" id="toggleCss" checked style="width:16px;height:16px;accent-color:var(--blue)">
            <span class="fw-600" style="color:var(--slate-800)">Aktifkan Tab CSS untuk Siswa</span>
          </label>
        </div>
        
        <div class="form-group" id="cssGroup">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_css', "/* Styling mulai dari sini */\nbody {\n  font-family: sans-serif;\n}") }}</textarea>
        </div>
        
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("toggleCss");
            const group = document.getElementById("cssGroup");
            toggle.addEventListener("change", function() {
               group.style.display = this.checked ? "block" : "none";
            });
            // trigger on load
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
            <option value="">-- Pilih Kelas --</option>
            @foreach($classes as $kelas)
              <option value="{{ $kelas->id }}" {{ old('class_id') == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label class="form-label form-label-required">Tipe Soal</label>
          <select name="type" class="form-select" required>
            <option value="latihan" {{ old('type') == 'latihan' ? 'selected' : '' }}>Latihan (Bebas waktu)</option>
            <option value="tugas" {{ old('type') == 'tugas' ? 'selected' : '' }}>Tugas (Dinilai ketat)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Batas Waktu (Deadline)</label>
          <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" class="form-input">
        </div>

        

      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary" style="width:100%">Simpan Tugas</button>
      </div>
    </div>
    
  </div>
</form>
@endsection