@extends('layouts.guru')
@section('title', 'Detail Tugas')
@section('breadcrumb', 'Tugas / ' . $assignment->title)

@section('topbar-actions')
    <div style="display:flex;gap:8px;align-items:center;">
      <a href="{{ route('guru.tugas.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
      <a href="{{ route('guru.kelas.show', $assignment->class_id) }}" class="btn btn-ghost btn-sm">Lihat Kelas</a>
      <a href="{{ route('guru.tugas.edit', $assignment) }}" class="btn btn-secondary btn-sm">Edit Info</a>
    </div>
@endsection

@section('content')
<style>
  .live-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; transition: background .3s; }
  .live-dot.offline { background: #cbd5e1; }
  .live-dot.online { background: #22c55e; box-shadow: 0 0 6px #22c55e; }
  @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
  .typing-indicator { animation: blink 1s infinite; }
</style>
<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;align-items:start">
  
  <!-- Info Tugas -->
  <div class="card">
    <div class="card-header">
      <div class="card-header-title">Detail Soal</div>
    </div>
    <div class="card-body">
      <h3 style="font-size:1.1rem;font-weight:700;color:var(--slate-900)">{{ $assignment->title }}</h3>
      <p style="font-size:.875rem;color:var(--slate-500);margin-top:6px;line-height:1.6">{{ $assignment->description ?: 'Tidak ada deskripsi instruksi.' }}</p>
      
      <div class="divider"></div>
      
      <div style="display:grid;gap:12px">
        <div style="display:flex;justify-content:space-between;font-size:.875rem">
          <span class="text-muted">Kelas:</span>
          <span class="fw-600">{{ $assignment->classRoom?->name ?? "Kelas Dihapus" }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.875rem">
          <span class="text-muted">Tipe:</span>
          @if($assignment->type === 'tugas')
            <span class="badge badge-red">Tugas</span>
          @else
            <span class="badge badge-blue">Latihan</span>
          @endif
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.875rem">
          <span class="text-muted">Status:</span>
          @if($assignment->status === 'published')
            <span class="badge badge-green">Dipublikasi</span>
          @else
            <span class="badge badge-gray">Draft</span>
          @endif
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.875rem">
          <span class="text-muted">Deadline:</span>
          <span class="fw-600" style="color:var(--orange)">
            {{ $assignment->deadline ? $assignment->deadline->format('d M Y H:i') : 'Tanpa batas' }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- Pengumpulan (Submissions) -->
  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-header-title">Status Pengumpulan</div>
        <div class="card-header-sub">{{ $assignment->submissions->where('status', 'submitted')->count() }} dari {{ $assignment->classRoom?->students->count() ?? 0 }} siswa mengumpulkan</div>
      </div>
    </div>
    <div class="table-wrap" style="border:none;border-radius:0">
      <table>
        <thead>
          <tr>
            <th>Siswa</th>
            <th>Status</th>
            <th>Waktu Kumpul</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($assignment->classRoom?->students ?? [] as $siswa)
            @php 
              $sub = $assignment->submissions->where('student_id', $siswa->id)->first();
            @endphp
            <tr>
              <td>
                <div class="td-main">
                <span id="status-dot-{{ $siswa->id }}" class="live-dot offline" title="Offline"></span>
                {{ $siswa->name }}
                <span id="typing-{{ $siswa->id }}" class="typing-indicator" style="display:none; font-size:11px; color:#3b82f6; margin-left:8px; font-style:italic">mengetik...</span>
                                  <span id="cheat-{{ $siswa->id }}" class="cheat-indicator" style="display:none; font-size:11px; color:#dc2626; margin-left:8px; font-weight:700; background:#fee2e2; padding:2px 6px; border-radius:4px; animation: blink 1s infinite;">⚠️ Keluar Layar!</span>
                  <button id="cctv-btn-{{ $siswa->id }}" onclick="openLiveView({{ $siswa->id }}, '{{ addslashes($siswa->name) }}')" style="display:none; margin-left:8px; border:none; background:#eff6ff; color:#3b82f6; padding:2px 6px; border-radius:4px; font-size:11px; cursor:pointer; font-weight:600;"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;margin-top:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Live</button>
              </div>
                <div class="td-sub">{{ $siswa->username }}</div>
              </td>
              <td>
                @if(!$sub)
                  <span class="badge badge-gray">Belum Kumpul</span>
                @elseif($sub->status === 'draft')
                  <span class="badge badge-orange">Mengerjakan (Draft)</span>
                @else
                  
                  @if($sub->score !== null)
                    <span class="badge badge-green">Dinilai ({{ $sub->score }})</span>
                  @else
                    <span class="badge badge-blue">Terkumpul</span>
                  @endif

                @endif
              </td>
              <td class="text-sm text-muted">
                {{ $sub && $sub->submitted_at ? $sub->submitted_at->format('d/m/Y H:i') : '-' }}
              </td>
              <td>
                
                @if($sub)
                  <a href="{{ route('guru.tugas.koreksi', ['tuga' => $assignment->id, 'siswa' => $siswa->id]) }}" class="btn btn-primary btn-sm">Lihat & Koreksi</a>
                @else
                  <button class="btn btn-secondary btn-sm" disabled>Belum Tersedia</button>
                @endif

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Reverb Echo Client -->
<script src="https://js.pusher.com/8.3.0/pusher.min.js">  window.studentCodes = {};
  
  function openLiveView(studentId, studentName) {
      if(!window.studentCodes[studentId]) return;
      let data = window.studentCodes[studentId];
      Swal.fire({
          title: 'Live CCTV: ' + studentName,
          html: `
            <div style="text-align:left;">
              <div style="font-weight:600;font-size:12px;color:#ef4444;margin-bottom:4px;">HTML Code</div>
              <pre id="cctv-html" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:200px;">${data.html.replace(/</g, '&lt;')}</pre>
              <div style="font-weight:600;font-size:12px;color:#3b82f6;margin-top:12px;margin-bottom:4px;">CSS Code</div>
              <pre id="cctv-css" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:150px;">${data.css.replace(/</g, '&lt;')}</pre>
            </div>
          `,
          width: 600,
          showConfirmButton: false,
          showCloseButton: true,
          didOpen: () => {
              // Simpan id modal aktif
              window.activeCctvStudent = studentId;
          },
          willClose: () => {
              window.activeCctvStudent = null;
          }
      });
  }

  // Bind code-update event
  Echo.join(`assignment.{{ $assignment->id }}`)
      .listenForWhisper('code-update', (e) => {
          let studentId = e.id || (e.user ? e.user.id : null);
          if (!studentId) return;
          
          const btn = document.getElementById('cctv-btn-' + studentId);
          if (btn) btn.style.display = 'inline-block';
          
          window.studentCodes[studentId] = { html: e.html, css: e.css };
          
          if (window.activeCctvStudent == studentId) {
              const htmlEl = document.getElementById('cctv-html');
              const cssEl = document.getElementById('cctv-css');
              if (htmlEl) htmlEl.textContent = e.html;
              if (cssEl) cssEl.textContent = e.css;
          }
      });
</script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js">  window.studentCodes = {};
  
  function openLiveView(studentId, studentName) {
      if(!window.studentCodes[studentId]) return;
      let data = window.studentCodes[studentId];
      Swal.fire({
          title: 'Live CCTV: ' + studentName,
          html: `
            <div style="text-align:left;">
              <div style="font-weight:600;font-size:12px;color:#ef4444;margin-bottom:4px;">HTML Code</div>
              <pre id="cctv-html" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:200px;">${data.html.replace(/</g, '&lt;')}</pre>
              <div style="font-weight:600;font-size:12px;color:#3b82f6;margin-top:12px;margin-bottom:4px;">CSS Code</div>
              <pre id="cctv-css" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:150px;">${data.css.replace(/</g, '&lt;')}</pre>
            </div>
          `,
          width: 600,
          showConfirmButton: false,
          showCloseButton: true,
          didOpen: () => {
              // Simpan id modal aktif
              window.activeCctvStudent = studentId;
          },
          willClose: () => {
              window.activeCctvStudent = null;
          }
      });
  }

  // Bind code-update event
  Echo.join(`assignment.{{ $assignment->id }}`)
      .listenForWhisper('code-update', (e) => {
          let studentId = e.id || (e.user ? e.user.id : null);
          if (!studentId) return;
          
          const btn = document.getElementById('cctv-btn-' + studentId);
          if (btn) btn.style.display = 'inline-block';
          
          window.studentCodes[studentId] = { html: e.html, css: e.css };
          
          if (window.activeCctvStudent == studentId) {
              const htmlEl = document.getElementById('cctv-html');
              const cssEl = document.getElementById('cctv-css');
              if (htmlEl) htmlEl.textContent = e.html;
              if (cssEl) cssEl.textContent = e.css;
          }
      });
</script>
<script>
  window.Pusher = Pusher;
  window.Echo = new Echo({
      broadcaster: 'reverb',
      key: '{{ env("REVERB_APP_KEY") }}',
      wsHost: window.location.hostname,
      wsPort: {{ env("REVERB_PORT", 8080) }},
      wssPort: {{ env("REVERB_PORT", 8080) }},
      forceTLS: false,
      enabledTransports: ['ws', 'wss'],
  });

  const presenceChannel = window.Echo.join('assignment.{{ $assignment->id }}');

  presenceChannel.here((users) => {
      users.forEach((user) => {
          if(user.role === 'siswa') setOnline(user.id);
      });
  })
  .joining((user) => {
      if(user.role === 'siswa') setOnline(user.id);
  })
  .leaving((user) => {
      if(user.role === 'siswa') setOffline(user.id);
  })
  .listenForWhisper('typing', (e) => {
      const typingEl = document.getElementById('typing-' + e.id);
      if(typingEl) {
          typingEl.style.display = e.typing ? 'inline' : 'none';
      }
  })
  .listenForWhisper('cheat', (e) => {
      const cheatEl = document.getElementById('cheat-' + e.id);
      const dot = document.getElementById('status-dot-' + e.id);
      if(cheatEl && dot) {
          cheatEl.style.display = e.cheating ? 'inline' : 'none';
          if(e.cheating) {
              dot.style.background = '#f59e0b'; // Ubah titik jadi orange/kuning
              dot.style.boxShadow = '0 0 6px #f59e0b';
          } else {
              dot.style.background = ''; // Kembalikan ke hijau (CSS default)
              dot.style.boxShadow = '';
          }
      }
  });

  function setOnline(id) {
      const dot = document.getElementById('status-dot-' + id);
      if(dot) {
          dot.classList.remove('offline');
          dot.classList.add('online');
          dot.title = 'Online sekarang';
      }
  }

  function setOffline(id) {
      const dot = document.getElementById('status-dot-' + id);
      if(dot) {
          dot.classList.remove('online');
          dot.classList.add('offline');
          dot.title = 'Offline';
          
          const typingEl = document.getElementById('typing-' + id);
          if(typingEl) typingEl.style.display = 'none';
      }
  }
  window.studentCodes = {};
  
  function openLiveView(studentId, studentName) {
      if(!window.studentCodes[studentId]) return;
      let data = window.studentCodes[studentId];
      Swal.fire({
          title: 'Live CCTV: ' + studentName,
          html: `
            <div style="text-align:left;">
              <div style="font-weight:600;font-size:12px;color:#ef4444;margin-bottom:4px;">HTML Code</div>
              <pre id="cctv-html" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:200px;">${data.html.replace(/</g, '&lt;')}</pre>
              <div style="font-weight:600;font-size:12px;color:#3b82f6;margin-top:12px;margin-bottom:4px;">CSS Code</div>
              <pre id="cctv-css" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:150px;">${data.css.replace(/</g, '&lt;')}</pre>
            </div>
          `,
          width: 600,
          showConfirmButton: false,
          showCloseButton: true,
          didOpen: () => {
              // Simpan id modal aktif
              window.activeCctvStudent = studentId;
          },
          willClose: () => {
              window.activeCctvStudent = null;
          }
      });
  }

  // Bind code-update event
  Echo.join(`assignment.{{ $assignment->id }}`)
      .listenForWhisper('code-update', (e) => {
          let studentId = e.id || (e.user ? e.user.id : null);
          if (!studentId) return;
          
          const btn = document.getElementById('cctv-btn-' + studentId);
          if (btn) btn.style.display = 'inline-block';
          
          window.studentCodes[studentId] = { html: e.html, css: e.css };
          
          if (window.activeCctvStudent == studentId) {
              const htmlEl = document.getElementById('cctv-html');
              const cssEl = document.getElementById('cctv-css');
              if (htmlEl) htmlEl.textContent = e.html;
              if (cssEl) cssEl.textContent = e.css;
          }
      });
</script>
@endsection