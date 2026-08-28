<?php

// --- 1. REWRITE CREATE TUGAS (Tanpa Emoji) ---
$createView = <<<'HTML'
@extends('layouts.guru')
@section('title', 'Buat Tugas Baru')
@section('breadcrumb', 'Tugas / Buat Baru')

@section('content')
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
        
        <div class="form-group">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_css', "/* Styling mulai dari sini */\nbody {\n  font-family: sans-serif;\n}") }}</textarea>
        </div>
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

        <div class="form-group" style="margin-top:24px">
          <label class="form-label form-label-required">Status Publikasi</label>
          <div style="display:flex;gap:12px">
            <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;cursor:pointer">
              <input type="radio" name="status" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}> Draft
            </label>
            <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;cursor:pointer">
              <input type="radio" name="status" value="published" {{ old('status') == 'published' ? 'checked' : '' }}> Langsung Publikasi
            </label>
          </div>
        </div>

      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary" style="width:100%">Simpan Tugas</button>
      </div>
    </div>
    
  </div>
</form>
@endsection
HTML;
file_put_contents(__DIR__ . '/resources/views/guru/tugas/create.blade.php', $createView);


// --- 2. REWRITE EDIT TUGAS (Tanpa Emoji) ---
$editView = <<<'HTML'
@extends('layouts.guru')
@section('title', 'Edit Tugas')
@section('breadcrumb', 'Tugas / ' . $assignment->title . ' / Edit')

@section('content')
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
        
        <div class="form-group">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old('starter_css', $assignment->starter_css) }}</textarea>
        </div>
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

        <div class="form-group" style="margin-top:24px">
          <label class="form-label form-label-required">Status Publikasi</label>
          <div style="display:flex;gap:12px">
            <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;cursor:pointer">
              <input type="radio" name="status" value="draft" {{ old('status', $assignment->status) == 'draft' ? 'checked' : '' }}> Draft
            </label>
            <label style="display:flex;align-items:center;gap:6px;font-size:.875rem;cursor:pointer">
              <input type="radio" name="status" value="published" {{ old('status', $assignment->status) == 'published' ? 'checked' : '' }}> Dipublikasi
            </label>
          </div>
        </div>

      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary" style="width:100%">Simpan Perubahan</button>
      </div>
    </div>
    
  </div>
</form>
@endsection
HTML;
file_put_contents(__DIR__ . '/resources/views/guru/tugas/edit.blade.php', $editView);

echo "Guru Create & Edit views rewrited.\n";
?>