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
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('select[name="type"]');
        const criteriaSection = document.getElementById('criteria-section');
        
        function toggleCriteria() {
            if (typeSelect.value === 'tugas') {
                criteriaSection.style.display = 'block';
            } else {
                criteriaSection.style.display = 'none';
            }
        }
        
        typeSelect.addEventListener('change', toggleCriteria);
        toggleCriteria(); // initial load
    });
</script>
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

        

      
<div id="criteria-section"><!-- KRITERIA PENILAIAN -->
        <hr style="border-top:1px solid #e2e8f0;margin:32px 0;">
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;">Kriteria Penilaian Otomatis (Opsional)</h3>
        <p style="font-size:0.875rem;color:#64748b;margin-bottom:16px;">Tentukan aturan kunci jawaban untuk tugas ini. Sistem akan memberi nilai otomatis berdasarkan aturan ini.</p>

        <div id="criteria-container" style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
        </div>

        <button type="button" onclick="addCriteriaRow()" class="btn btn-secondary btn-sm" style="display:inline-flex;align-items:center;gap:4px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kriteria Baru
        </button>
        </div>
      </div>
      <div class="card-footer form-actions">
        <button type="submit" class="btn btn-primary" style="width:100%">Simpan Tugas</button>
      </div>
    </div>
    
  </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('select[name="type"]');
        const criteriaSection = document.getElementById('criteria-section');
        
        function toggleCriteria() {
            if (typeSelect.value === 'tugas') {
                criteriaSection.style.display = 'block';
            } else {
                criteriaSection.style.display = 'none';
            }
        }
        
        typeSelect.addEventListener('change', toggleCriteria);
        toggleCriteria(); // initial load
    });
</script>
<script>
    let criteriaCount = 0;

    function addCriteriaRow() {
        const container = document.getElementById('criteria-container');
        const row = document.createElement('div');
        row.className = 'criteria-row';
                row.style.cssText = 'background:#f8fafc; padding:16px; border:1px solid #e2e8f0; border-radius:12px; position:relative; box-shadow:0 2px 4px rgba(0,0,0,0.02); animation: fadeIn 0.3s ease; margin-bottom:12px;';
        row.innerHTML = `
            <button type="button" onclick="this.closest('.criteria-row').remove()" style="position:absolute; top:12px; right:12px; background:transparent; border:none; color:#ef4444; cursor:pointer; padding:4px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'" title="Hapus Kriteria">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap:12px; align-items:end; margin-bottom:12px; padding-right:24px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Tipe Cek</label>
                    <select name="criteria[${criteriaCount}][type]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" required>
                        <option value="has_tag">Tag HTML Muncul</option>
                        <option value="has_attribute">Atribut Muncul</option>
                        <option value="has_text">Teks Tulisan</option>
                        <option value="has_css">Properti CSS</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Target (misal: h1)</label>
                    <input type="text" name="criteria[${criteriaCount}][target]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" placeholder="button" required>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Nilai (Opsional)</label>
                    <input type="text" name="criteria[${criteriaCount}][value]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" placeholder="red">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Poin</label>
                    <input type="number" name="criteria[${criteriaCount}][points]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" value="10" min="1" required>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Pesan Error (Jika Gagal)</label>
                <input type="text" name="criteria[${criteriaCount}][description]" class="form-input" style="padding:8px 12px; font-size:0.875rem; width:100%;" placeholder="Gunakan tag <h1>" required>
            </div>
        `;
        container.appendChild(row);
        criteriaCount++;
    }
</script>
@endpush
