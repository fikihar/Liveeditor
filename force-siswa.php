<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// 1. ADD RESET BUTTON NEXT TO SUBMIT BUTTON
if (strpos($html, 'Ulangi dari Awal') === false) {
    $html = preg_replace(
        '/(<button class="btn-submit".*?<\/button>)/i',
        '<button class="btn-submit" style="background:rgba(239,68,68,0.1);color:#ef4444;" onclick="resetCode()" title="Ulangi dari Awal" type="button">Reset</button> $1',
        $html
    );
}

// 2. ADD RESET JS LOGIC
if (strpos($html, 'function resetCode()') === false) {
    $html = preg_replace(
        '/(function submitWork\(\) {)/',
        "function resetCode() {\n  document.getElementById('resetModal').classList.add('active');\n}\nfunction closeResetModal() {\n  document.getElementById('resetModal').classList.remove('active');\n}\nfunction confirmReset() {\n  htmlEditor.dispatch({ changes: { from: 0, to: htmlEditor.state.doc.length, insert: document.getElementById('starter-html-data').value } });\n  if(typeof cssEditor !== 'undefined') {\n     cssEditor.dispatch({ changes: { from: 0, to: cssEditor.state.doc.length, insert: document.getElementById('starter-css-data').value } });\n  }\n  closeResetModal();\n}\n\n$1",
        $html
    );
}

// 3. INJECT STARTER DATA INPUTS
if (strpos($html, 'starter-html-data') === false) {
    $html = preg_replace(
        '/(<input type="hidden" id="raw-html-data")/',
        '<input type="hidden" id="starter-html-data" value="{{ $assignment->starter_html }}">' . "\n" . '<input type="hidden" id="starter-css-data" value="{{ $assignment->starter_css }}">' . "\n$1",
        $html
    );
}

// 4. ADD RESET MODAL HTML
if (strpos($html, 'id="resetModal"') === false) {
    $html = preg_replace(
        '/(<!-- Custom Modal Kumpulkan -->)/',
        '<!-- Custom Modal Reset -->
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
$1',
        $html
    );
}

// 5. ADD CHEAT LOGGING IN VISIBILITY CHANGE
if (strpos($html, "route('siswa.editor.cheat'") === false) {
    $html = preg_replace(
        "/(presenceChannel\.whisper\('cheat', \{[\s\S]*?\}\);)/",
        "$1\n        if ('{{ \$assignment->type }}' === 'tugas') {\n            fetch('{{ route('siswa.editor.cheat', \$assignment) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'), 'Accept': 'application/json' }});\n        }",
        $html
    );
}

// 6. ADD CODE WHISPERING IN updateListener
if (strpos($html, "presenceChannel.whisper('code-update'") === false) {
    $html = preg_replace(
        "/(notifyTyping\(\);)/",
        "$1\n            if(window.getHtmlCode) { presenceChannel.whisper('code-update', { id: {{ auth()->id() }}, html: window.getHtmlCode(), css: window.getCssCode() }); }",
        $html
    );
}

file_put_contents($file, $html);
echo "Siswa editor successfully updated.\n";
?>