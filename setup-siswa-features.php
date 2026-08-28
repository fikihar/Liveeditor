<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// 1. ADD RESET BUTTON
$oldTitle = '<div class="title">{{ $assignment->title }}</div>';
$newTitle = <<<HTML
          <div class="title">{{ \$assignment->title }}</div>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
          <button class="btn-back" style="width:auto;padding:0 12px;font-size:12px;font-weight:600;background:rgba(239,68,68,0.1);color:#ef4444;" onclick="resetCode()" title="Ulangi dari Awal">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reset
          </button>
HTML;
$html = str_replace($oldTitle . "\n        </div>", $newTitle, $html);

// 2. ADD RESET JS FUNCTION
$jsAnchor = 'function submitWork() {';
$resetJs = <<<JS
    function resetCode() {
      document.getElementById('resetModal').classList.add('active');
    }
    function closeResetModal() {
      document.getElementById('resetModal').classList.remove('active');
    }
    function confirmReset() {
      htmlEditor.dispatch({ changes: { from: 0, to: htmlEditor.state.doc.length, insert: document.getElementById('starter-html-data').value } });
      if(window.cssEditor) {
         cssEditor.dispatch({ changes: { from: 0, to: cssEditor.state.doc.length, insert: document.getElementById('starter-css-data').value } });
      }
      closeResetModal();
    }

    function submitWork() {
JS;
$html = str_replace($jsAnchor, $resetJs, $html);

// Inject raw starter codes
$rawAnchor = '<input type="hidden" id="raw-html-data"';
$starterInputs = <<<HTML
    <input type="hidden" id="starter-html-data" value="{{ \$assignment->starter_html }}">
    <input type="hidden" id="starter-css-data" value="{{ \$assignment->starter_css }}">
    <input type="hidden" id="raw-html-data"
HTML;
$html = str_replace($rawAnchor, $starterInputs, $html);

// Add Reset Modal HTML
$modalAnchor = '<!-- Custom Modal Kumpulkan -->';
$resetModalHtml = <<<HTML
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
HTML;
$html = str_replace($modalAnchor, $resetModalHtml, $html);

// 3. ADD CHEAT LOGGING IN VISIBILITY CHANGE
$visAnchor = "channel.whisper('cheat', { user: window.currentUser });";
$cheatAjax = <<<JS
          channel.whisper('cheat', { user: window.currentUser });
          // Log ke database jika ini tugas
          if ("{{ \$assignment->type }}" === "tugas") {
              fetch("{{ route('siswa.editor.cheat', \$assignment) }}", {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                      'Accept': 'application/json'
                  }
              });
          }
JS;
$html = str_replace($visAnchor, $cheatAjax, $html);

// 4. ADD LIVE CODE WHISPERING
$updateAnchor = "channel.whisper('typing', { user: window.currentUser });";
$codeWhisper = <<<JS
                channel.whisper('typing', { user: window.currentUser });
                channel.whisper('code-update', { 
                    user: window.currentUser, 
                    html: window.getHtmlCode(), 
                    css: window.getCssCode() 
                });
JS;
$html = str_replace($updateAnchor, $codeWhisper, $html);

file_put_contents($file, $html);
echo "Frontend features added.\n";
?>