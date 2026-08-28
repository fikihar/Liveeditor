@extends('layouts.guru')
@section('title', 'Koreksi Jawaban')
@section('breadcrumb', 'Tugas / ' . $assignment->title . ' / Koreksi / ' . $siswa->name)

@section('topbar-actions')
  <a href="{{ route('guru.tugas.show', $assignment) }}" class="btn btn-secondary btn-sm">Kembali</a>
@endsection

@section('content')
<!-- Ubah layout menjadi 3 Kolom: Panel Info (260px) | Kode (1fr) | Preview (1fr) -->
<div style="display:grid;grid-template-columns:260px 1fr 1fr;gap:20px;height:calc(100vh - 120px);align-items:stretch;">
  
  <!-- KOLOM 1: Info Siswa & Panel Nilai -->
  <div style="display:flex;flex-direction:column;gap:16px;overflow-y:auto;">
    
    <div class="card" style="border-top: 4px solid var(--primary-500);">
      <div class="card-body">
        <h4 style="font-weight:800;font-size:22px;margin-bottom:2px;color:var(--slate-900);">{{ $siswa->name }}</h4>
        <p style="font-size:13px;color:var(--slate-500);margin-bottom:12px;">NIS: {{ $siswa->username }}</p>
        
        <div style="background:var(--slate-50);padding:10px;border-radius:6px;font-size:12px;color:var(--slate-600);margin-bottom:20px;">
          <strong>Waktu Submit:</strong><br>
          {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : 'Masih Draft' }}
        </div>
        
        @if($assignment->type === 'latihan')
          <div style="margin-top:16px;padding:12px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:6px;font-size:13px;text-align:center;">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px auto;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
             Ini adalah <b>Latihan Bebas</b>.<br>Siswa hanya bereksperimen dan sistem tidak menuntut nilai.
          </div>
          <div style="margin-top:16px;">
            <a href="{{ route('guru.tugas.show', $assignment) }}" class="btn btn-secondary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;">Kembali ke Daftar</a>
          </div>
        @elseif($submission->status === 'draft')
          <div style="margin-top:16px;padding:12px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:6px;font-size:13px;text-align:center;">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 8px auto;display:block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
             Siswa belum menekan tombol <b>Kumpulkan</b>.<br>Tugas masih berupa Draft.
          </div>
          <div style="opacity:0.5;pointer-events:none;margin-top:16px;">
            <div class="form-group" style="margin-bottom:12px;">
              <label class="form-label" style="font-weight:600;color:var(--slate-700);">Beri Nilai (0-100)</label>
              <input type="text" class="form-input" value="-" style="font-size:24px;font-weight:700;text-align:center;padding:12px;" disabled>
            </div>
            <button type="button" class="btn btn-secondary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;" disabled>Belum Bisa Dinilai</button>
          </div>
        @else
          <form action="{{ route('guru.tugas.nilai', [$assignment->id, $siswa->id]) }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom:12px;">
              <label class="form-label" style="font-weight:600;color:var(--slate-700);">Beri Nilai (0-100)</label>
              <input type="number" name="score" class="form-input" value="{{ old('score', $submission->score) }}" min="0" max="100" style="font-size:24px;font-weight:700;text-align:center;padding:12px;" placeholder="0" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;font-size:14px;font-weight:600;">Simpan Nilai</button>
          </form>
        @endif
      </div>
    </div>

  </div>

  <!-- KOLOM 2: Area Kode (HTML & CSS) -->
  <div style="display:flex;flex-direction:column;gap:16px;height:100%;overflow:hidden;">
    
    <!-- Kode HTML -->
    <div class="card" style="display:flex;flex-direction:column;flex:1;overflow:hidden;border:1px solid var(--slate-200);">
      <div class="card-header" style="background:var(--slate-50);border-bottom:1px solid var(--slate-200);padding:10px 16px;">
        <div class="card-header-title" style="font-family:monospace;font-size:14px;color:var(--orange-600);">index.html</div>
      </div>
      <div class="card-body" style="padding:0;flex:1;display:flex;">
        <textarea readonly style="width:100%;height:100%;border:none;background:#1e293b;color:#e2e8f0;font-family:'SF Mono', Consolas, monospace;padding:16px;font-size:13px;resize:none;line-height:1.5;">{{ $submission->html_code }}</textarea>
      </div>
    </div>

    <!-- Kode CSS -->
    @if($assignment->has_css)
    <div class="card" style="display:flex;flex-direction:column;flex:1;overflow:hidden;border:1px solid var(--slate-200);">
      <div class="card-header" style="background:var(--slate-50);border-bottom:1px solid var(--slate-200);padding:10px 16px;">
        <div class="card-header-title" style="font-family:monospace;font-size:14px;color:var(--blue-600);">style.css</div>
      </div>
      <div class="card-body" style="padding:0;flex:1;display:flex;">
        <textarea readonly style="width:100%;height:100%;border:none;background:#1e293b;color:#e2e8f0;font-family:'SF Mono', Consolas, monospace;padding:16px;font-size:13px;resize:none;line-height:1.5;">{{ $submission->css_code }}</textarea>
      </div>
    </div>
    @endif

  </div>

  <!-- KOLOM 3: Preview Hasil -->
  <div class="card" style="display:flex;flex-direction:column;height:100%;overflow:hidden;border:1px solid var(--slate-200);">
    <div class="card-header" style="background:var(--slate-50);border-bottom:1px solid var(--slate-200);padding:10px 16px;display:flex;justify-content:space-between;align-items:center;">
      <div class="card-header-title" style="font-size:14px;color:var(--slate-700);">Preview Hasil</div>
      <!-- Indikator Ukuran (Hanya Visual) -->
      <span style="font-size:11px;background:var(--slate-200);padding:2px 8px;border-radius:12px;color:var(--slate-600);">Desktop/Tablet View</span>
    </div>
    <div class="card-body" style="padding:0;flex:1;background:white;position:relative;">
      <iframe id="preview-frame" style="width:100%;height:100%;border:none;"></iframe>
    </div>
  </div>

</div>

<script>
  window.addEventListener('DOMContentLoaded', () => {
      const doc = document.getElementById('preview-frame').contentWindow.document;
      doc.open();
      
      let htmlCode = {!! json_encode($submission->html_code ?? '') !!};
      let cssCode = {!! json_encode($submission->css_code ?? '') !!};
      
      const externalCssRegex = /<link\s+[^>]*href=["']style\.css["'][^>]*>/gi;
      if (externalCssRegex.test(htmlCode)) {
          htmlCode = htmlCode.replace(externalCssRegex, `<style>\n${cssCode}\n</style>`);
      }
      
      doc.write(htmlCode);
      doc.close();
  });
</script>
@endsection