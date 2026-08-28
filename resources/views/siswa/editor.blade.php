<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#1e293b">
  <title>{{ $assignment->title }} - ClassEditor</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { height: 100%; overflow: hidden; background: #1e293b; color: white; font-family: -apple-system, BlinkMacSystemFont, sans-serif; }
    
    .app-container { display: flex; flex-direction: column; height: 100vh; }
    
    /* Topbar */
    .topbar { display: flex; align-items: center; justify-content: space-between; padding: 0 12px; height: 50px; background: #0f172a; border-bottom: 1px solid #334155; flex-shrink: 0; }
    .topbar-left { display: flex; align-items: center; gap: 10px; }
    .btn-back { color: #94a3b8; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,.05); text-decoration: none; }
    .title { font-size: 14px; font-weight: 600; max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-submit { background: #2563eb; color: white; border: none; padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
    
    /* Tabs */
    .tabs { display: flex; background: #1e293b; height: 44px; flex-shrink: 0; border-bottom: 1px solid #334155; }
    .tab { flex: 1; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; color: #94a3b8; border-bottom: 2px solid transparent; cursor: pointer; transition: all .2s; }
    .tab.active { color: #60a5fa; border-bottom-color: #60a5fa; background: rgba(255,255,255,.02); }
    
    /* Content Area */
    .content-area { flex: 1; position: relative; display: flex; flex-direction: column; min-height: 0; }
    .panel { position: absolute; inset: 0; display: none; flex-direction: column; }
    .panel.active { display: flex; }
    
    /* CodeMirror Overrides */
    .cm-editor { height: 100%; font-size: 14px; }
    .cm-scroller { font-family: 'SF Mono', Consolas, monospace; }
    
    /* Iframe */
    .preview-frame { width: 100%; height: 100%; border: none; background: white; }
    
    /* Anti-cheat Alert */
    .cheat-alert { position: fixed; top: 10px; left: 10px; right: 10px; background: #ef4444; color: white; padding: 12px; border-radius: 8px; font-size: 13px; text-align: center; z-index: 9999; transform: translateY(-150%); transition: transform .3s; font-weight: 600; }
    .cheat-alert.show { transform: translateY(0); }

          /* Custom Modal Kumpulkan Tugas */
      .custom-modal { position: fixed; inset: 0; background: rgba(15,23,42,0.6); z-index: 99999; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.3s; backdrop-filter: blur(4px); padding: 20px; }
      .custom-modal.active { opacity: 1; pointer-events: auto; }
      .modal-content { background: white; border-radius: 16px; padding: 24px; text-align: center; width: 100%; max-width: 340px; transform: scale(0.95) translateY(10px); transition: transform 0.3s; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); }
      .custom-modal.active .modal-content { transform: scale(1) translateY(0); }
      .modal-icon { background: #eff6ff; color: #3b82f6; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; }
      .modal-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
      .modal-text { font-size: 14px; color: #64748b; line-height: 1.5; margin-bottom: 24px; }
      .modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
      .modal-actions button { padding: 12px; border-radius: 8px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 6px; }
      .btn-cancel { background: #f1f5f9; color: #475569; }
      .btn-cancel:hover { background: #e2e8f0; }
      .btn-confirm { background: #3b82f6; color: white; }
      .btn-confirm:hover { background: #2563eb; }
      .spinner { animation: rotate 2s linear infinite; width: 16px; height: 16px; }
      .spinner .path { stroke: currentColor; stroke-linecap: round; animation: dash 1.5s ease-in-out infinite; }
      @keyframes rotate { 100% { transform: rotate(360deg); } }
      @keyframes dash { 0% { stroke-dasharray: 1, 150; stroke-dashoffset: 0; } 50% { stroke-dasharray: 90, 150; stroke-dashoffset: -35; } 100% { stroke-dasharray: 90, 150; stroke-dashoffset: -124; } }

      /* LANDSCAPE OPTIMIZATION */
    @media (orientation: landscape) {
      .topbar { height: 38px; }
      .tabs { height: 36px; }
      .title { display: none; /* Hide title to save space */ }
      .cm-editor { font-size: 13px; } /* Slightly smaller font to fit more code */
    }
  </style>
</head>
<body>

  <div class="cheat-alert" id="cheatAlert">
    PERINGATAN: Anda terdeteksi keluar dari layar tugas!
  </div>

  <!-- Sembunyikan kode asli dengan aman di dalam textarea DOM, bukan langsung di JS -->
  <textarea id="raw-html-data" style="display:none;">{{ $submission->html_code ?? '' }}</textarea>
  <textarea id="raw-css-data" style="display:none;">{{ $submission->css_code ?? '' }}</textarea>

  <div class="app-container">
    <form id="editorForm" method="POST" action="{{ route('siswa.editor.submit', $assignment) }}" style="display:none;">
      @csrf
      <input type="hidden" name="html_code" id="inputHtml">
      <input type="hidden" name="css_code" id="inputCss">
      <input type="hidden" name="action" id="inputAction" value="save">
    </form>

    <div class="topbar">
      <div class="topbar-left">
                @if($assignment->type === 'tugas' && $submission->status === 'submitted')
          <a href="{{ route('siswa.dashboard') }}" class="btn-back">
        @else
          <a href="{{ route('siswa.dashboard') }}" class="btn-back" onclick="saveDraft(event)">
        @endif
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="title">{{ $assignment->title }}</div>
      </div>
            @if($assignment->type === 'tugas' && $submission->status === 'submitted')
        <button class="btn-submit" style="background:rgba(239,68,68,0.1);color:#ef4444;" onclick="resetCode()" title="Ulangi dari Awal" type="button">Reset</button> <button class="btn-submit" style="background:rgba(255,255,255,0.1);color:#94a3b8;cursor:not-allowed;" disabled>Terkunci</button>
      @else
        <button class="btn-submit" style="background:rgba(239,68,68,0.1);color:#ef4444;" onclick="resetCode()" title="Ulangi dari Awal" type="button">Reset</button> <button class="btn-submit" onclick="submitWork()">{{ $assignment->type === 'latihan' ? 'Selesai' : 'Kumpulkan' }}</button>
      @endif
    </div>

    <div class="tabs">
      <div class="tab active" onclick="switchTab('html')">HTML</div>
      @if($assignment->has_css) <div class="tab" onclick="switchTab('css')">CSS</div> @endif
      <div class="tab" onclick="switchTab('preview')" style="color:#a7f3d0">Hasil</div>
    </div>

    <div class="content-area">
      <div class="panel active" id="panel-html"></div>
      @if($assignment->has_css) <div class="panel" id="panel-css"></div> @endif
      <div class="panel" id="panel-preview">
        <iframe id="preview-frame" class="preview-frame"></iframe>
      </div>
    </div>
  </div>

  <script>
    // Fallback fungsi (akan di-override saat esm termuat)
    window.getHtmlCode = () => document.getElementById('raw-html-data').value;
    window.getCssCode = () => document.getElementById('raw-css-data').value;

    function switchTab(tabId) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
      
      event.target.classList.add('active');
      document.getElementById('panel-' + tabId).classList.add('active');

      if (tabId === 'preview') {
        const doc = document.getElementById('preview-frame').contentWindow.document;
        doc.open();
        
        let htmlCode = window.getHtmlCode();
        let cssCode = window.getCssCode();
        
        // Simulasi External CSS: Cari tag <link rel="stylesheet" href="style.css">
        const externalCssRegex = /<link\s+[^>]*href=["']style\.css["'][^>]*>/gi;
        
        // Jika siswa menuliskan link eksternalnya, kita ganti link tersebut dengan isi Tab CSS
        if (externalCssRegex.test(htmlCode)) {
            htmlCode = htmlCode.replace(externalCssRegex, `<style>\n${cssCode}\n</style>`);
        }
        // Jika tidak ditulis, Tab CSS akan diabaikan (hanya mengandalkan HTML / Inline / Internal CSS)

        doc.write(htmlCode);
        doc.close();
      }
    }

        function resetCode() {
      document.getElementById('resetModal').classList.add('active');
    }
    function closeResetModal() {
      document.getElementById('resetModal').classList.remove('active');
    }
    function confirmReset() {
      window.htmlEditor.dispatch({ changes: { from: 0, to: window.htmlEditor.state.doc.length, insert: document.getElementById('starter-html-data').value } });
      if(window.cssEditor) {
         window.cssEditor.dispatch({ changes: { from: 0, to: window.cssEditor.state.doc.length, insert: document.getElementById('starter-css-data').value } });
      }
      closeResetModal();
    }

    function submitWork() {
      document.getElementById('submitModal').classList.add('active');
    }

    function closeModal() {
      document.getElementById('submitModal').classList.remove('active');
    }

    function confirmSubmit() {
      const btn = document.querySelector('.btn-confirm');
      btn.innerHTML = '<svg class="spinner" viewBox="0 0 24 24"><circle class="path" cx="12" cy="12" r="10" fill="none" stroke-width="4"></circle></svg> Mengirim...';
      btn.style.opacity = '0.8';
      btn.style.pointerEvents = 'none';

      document.getElementById('inputHtml').value = window.getHtmlCode();
      document.getElementById('inputCss').value = window.getCssCode();
      document.getElementById('inputAction').value = 'submit';
      document.getElementById('editorForm').submit();
    }

    function saveDraft(e) {
      e.preventDefault();
      document.getElementById('inputHtml').value = window.getHtmlCode();
      document.getElementById('inputCss').value = window.getCssCode();
      document.getElementById('inputAction').value = 'save';
      document.getElementById('editorForm').submit();
    }

    // Anti-Cheat (Page Visibility)
    let cheatTimer;
    let isCheating = false;

    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "hidden") {
        // Beri toleransi 1.5 detik (mencegah salah deteksi akibat iframe/keyboard/notifikasi)
        cheatTimer = setTimeout(() => {
            isCheating = true;
            presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: true });
        if ('{{ $assignment->type }}' === 'tugas') {
            fetch('{{ route('siswa.editor.cheat', $assignment) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }});
        }
            fetch("{{ route('siswa.editor.log', $assignment) }}", {
              method: "POST",
              headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
              body: JSON.stringify({ event: 'tab_switch' })
            });
        }, 1500);
      } else {
        clearTimeout(cheatTimer);
        if (isCheating) {
            presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: false });
        if ('{{ $assignment->type }}' === 'tugas') {
            fetch('{{ route('siswa.editor.cheat', $assignment) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }});
        }
            const alert = document.getElementById('cheatAlert');
            alert.classList.add('show');
            setTimeout(() => alert.classList.remove('show'), 5000);
            isCheating = false;
        }
      }
    });
  </script>

    <script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
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
    
    let typingTimer;
    function notifyTyping() {
        presenceChannel.whisper('typing', {
            id: {{ auth()->id() }},
            typing: true
        });
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            presenceChannel.whisper('typing', {
                id: {{ auth()->id() }},
                typing: false
            });
        }, 1500);
    }
  </script>
  <script type="module">
        import { EditorView, basicSetup } from "https://esm.sh/codemirror@6.0.1";
    import { html } from "https://esm.sh/@codemirror/lang-html@6.0.0";
    import { css } from "https://esm.sh/@codemirror/lang-css@6.0.0";
    import { oneDark } from "https://esm.sh/@codemirror/theme-one-dark@6.1.2";

    const updateListener = EditorView.updateListener.of((update) => {
        if (update.docChanged) {
            notifyTyping();
            if(window.getHtmlCode) { presenceChannel.whisper('code-update', { id: {{ auth()->id() }}, html: window.getHtmlCode(), css: window.getCssCode() }); }
        }
    });

    const initHtml = document.getElementById('raw-html-data').value;
    const htmlEditor = new EditorView({
      doc: initHtml,
      extensions: [basicSetup, html(), oneDark, updateListener {{ ($assignment->type === 'tugas' && $submission->status === 'submitted') ? ', EditorView.editable.of(false)' : '' }}],
      parent: document.getElementById('panel-html')
    });
      window.htmlEditor = htmlEditor;
    window.getHtmlCode = () => htmlEditor.state.doc.toString();

    @if($assignment->has_css)
      const initCss = document.getElementById('raw-css-data').value;
      const cssEditor = new EditorView({
        doc: initCss,
        extensions: [basicSetup, css(), oneDark, updateListener {{ ($assignment->type === 'tugas' && $submission->status === 'submitted') ? ', EditorView.editable.of(false)' : '' }}],
        parent: document.getElementById('panel-css')
      });
        window.cssEditor = cssEditor;
      window.getCssCode = () => cssEditor.state.doc.toString();
    @else
      window.getCssCode = () => '';
    @endif
  </script>
    <!-- Custom Modal Reset -->
  <div class="custom-modal" id="resetModal">
    <div class="modal-content">
      <div class="modal-icon" style="background:#fef2f2;color:#ef4444;">
        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      </div>
      <h3 class="modal-title">Reset Kode?</h3>
      <p class="modal-text">Semua kodemu akan dihapus dan dikembalikan ke kode awal bawaan dari tugas ini. Yakin?</p>
      <div class="modal-actions">
        <button class="btn-cancel" onclick="closeResetModal()">Batal</button>
        <button class="btn-confirm" style="background:#ef4444;" onclick="confirmReset()">Ya, Reset</button>
      </div>
    </div>
  </div>
  
  <!-- Custom Modal Kumpulkan -->
  <div class="custom-modal" id="submitModal">
    <div class="modal-content">
      <div class="modal-icon">
        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
      </div>
      <h3 class="modal-title">{{ $assignment->type === 'latihan' ? 'Selesaikan Latihan?' : 'Kumpulkan Tugas?' }}</h3>
      <p class="modal-text">
        @if($assignment->type === 'latihan')
          Pastikan kamu sudah puas bereksperimen. Latihan yang sudah diselesaikan akan ditutup.
        @else
          Pastikan kode HTML dan CSS kamu sudah berjalan dengan baik. Tugas yang sudah dikumpulkan tidak bisa diubah lagi dan akan menunggu penilaian Guru.
        @endif
      </p>
      <div class="modal-actions">
        <button class="btn-cancel" onclick="closeModal()">Kembali</button>
        <button class="btn-confirm" onclick="confirmSubmit()">
          Ya, Kirim!
        </button>
      </div>
    </div>
  </div>
</body>
</html>