<?php

$editorView = <<<'HTML'
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
        <a href="{{ route('siswa.dashboard') }}" class="btn-back" onclick="saveDraft(event)">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="title">{{ $assignment->title }}</div>
      </div>
      <button class="btn-submit" onclick="submitWork()">Kumpulkan</button>
    </div>

    <div class="tabs">
      <div class="tab active" onclick="switchTab('html')">HTML</div>
      <div class="tab" onclick="switchTab('css')">CSS</div>
      <div class="tab" onclick="switchTab('preview')" style="color:#a7f3d0">Hasil</div>
    </div>

    <div class="content-area">
      <div class="panel active" id="panel-html"></div>
      <div class="panel" id="panel-css"></div>
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
        doc.write(`
          ${window.getHtmlCode()}
          <style>${window.getCssCode()}</style>
        `);
        doc.close();
      }
    }

    function submitWork() {
      if(confirm('Yakin mengumpulkan sekarang?')) {
        document.getElementById('inputHtml').value = window.getHtmlCode();
        document.getElementById('inputCss').value = window.getCssCode();
        document.getElementById('inputAction').value = 'submit';
        document.getElementById('editorForm').submit();
      }
    }

    function saveDraft(e) {
      e.preventDefault();
      document.getElementById('inputHtml').value = window.getHtmlCode();
      document.getElementById('inputCss').value = window.getCssCode();
      document.getElementById('inputAction').value = 'save';
      document.getElementById('editorForm').submit();
    }

    // Anti-Cheat (Page Visibility)
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "hidden") {
        fetch("{{ route('siswa.editor.log', $assignment) }}", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ event: 'tab_switch' })
        });
      } else {
        const alert = document.getElementById('cheatAlert');
        alert.classList.add('show');
        setTimeout(() => alert.classList.remove('show'), 5000);
      }
    });
  </script>

  <script type="module">
    import { EditorView, basicSetup } from "https://esm.sh/codemirror@6.0.1";
    import { html } from "https://esm.sh/@codemirror/lang-html@6.0.0";
    import { css } from "https://esm.sh/@codemirror/lang-css@6.0.0";
    import { oneDark } from "https://esm.sh/@codemirror/theme-one-dark@6.1.2";

    const initHtml = document.getElementById('raw-html-data').value;
    const initCss = document.getElementById('raw-css-data').value;

    const htmlEditor = new EditorView({
      doc: initHtml,
      extensions: [basicSetup, html(), oneDark],
      parent: document.getElementById('panel-html')
    });

    const cssEditor = new EditorView({
      doc: initCss,
      extensions: [basicSetup, css(), oneDark],
      parent: document.getElementById('panel-css')
    });

    // Replace fallback
    window.getHtmlCode = () => htmlEditor.state.doc.toString();
    window.getCssCode = () => cssEditor.state.doc.toString();
  </script>
</body>
</html>
HTML;

file_put_contents(__DIR__ . '/resources/views/siswa/editor.blade.php', $editorView);
echo "Editor view safely rewritten.\n";
?>