<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// 1. Add CSS for Custom Modal
$cssAnchor = '/* LANDSCAPE OPTIMIZATION */';
$newCss = <<<CSS
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
CSS;
$html = str_replace($cssAnchor, $newCss, $html);

// 2. Add Modal HTML before </body>
$modalHtml = <<<HTML
  <!-- Custom Modal Kumpulkan -->
  <div class="custom-modal" id="submitModal">
    <div class="modal-content">
      <div class="modal-icon">
        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
      </div>
      <h3 class="modal-title">Kumpulkan Tugas?</h3>
      <p class="modal-text">Pastikan kode HTML dan CSS kamu sudah berjalan dengan baik. Tugas yang sudah dikumpulkan tidak bisa diubah lagi.</p>
      <div class="modal-actions">
        <button class="btn-cancel" onclick="closeModal()">Kembali</button>
        <button class="btn-confirm" onclick="confirmSubmit()">
          Ya, Kirim!
        </button>
      </div>
    </div>
  </div>
</body>
HTML;
$html = str_replace('</body>', $modalHtml, $html);

// 3. Update JS Logic
$oldJs = <<<JS
    function submitWork() {
      if(confirm('Yakin mengumpulkan sekarang?')) {
        document.getElementById('inputHtml').value = window.getHtmlCode();
        document.getElementById('inputCss').value = window.getCssCode();
        document.getElementById('inputAction').value = 'submit';
        document.getElementById('editorForm').submit();
      }
    }
JS;

$newJs = <<<JS
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
JS;
$html = str_replace($oldJs, $newJs, $html);

file_put_contents($file, $html);
echo "Siswa Editor view updated with Custom Submit Modal.\n";
?>