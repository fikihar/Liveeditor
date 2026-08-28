<?php
// UPDATE CREATE.BLADE.PHP
$createFile = __DIR__ . '/resources/views/guru/tugas/create.blade.php';
$createHtml = file_get_contents($createFile);

$oldCssBlock = '<div class="form-group">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old(\'starter_css\', "/* Styling mulai dari sini */\nbody {\n  font-family: sans-serif;\n}") }}</textarea>
        </div>';

$newCssBlock = '<div class="form-group" style="margin-top:20px;border-top:1px dashed var(--slate-200);padding-top:16px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:12px">
            <input type="checkbox" name="has_css" value="1" id="toggleCss" checked style="width:16px;height:16px;accent-color:var(--blue)">
            <span class="fw-600" style="color:var(--slate-800)">Aktifkan Tab CSS untuk Siswa</span>
          </label>
        </div>
        
        <div class="form-group" id="cssGroup">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old(\'starter_css\', "/* Styling mulai dari sini */\nbody {\n  font-family: sans-serif;\n}") }}</textarea>
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
        </script>';

file_put_contents($createFile, str_replace($oldCssBlock, $newCssBlock, $createHtml));

// UPDATE EDIT.BLADE.PHP
$editFile = __DIR__ . '/resources/views/guru/tugas/edit.blade.php';
$editHtml = file_get_contents($editFile);

$oldEditCssBlock = '<div class="form-group">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old(\'starter_css\', $assignment->starter_css) }}</textarea>
        </div>';

$newEditCssBlock = '<div class="form-group" style="margin-top:20px;border-top:1px dashed var(--slate-200);padding-top:16px;">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;margin-bottom:12px">
            <input type="checkbox" name="has_css" value="1" id="toggleCss" {{ old(\'has_css\', $assignment->has_css) ? \'checked\' : \'\' }} style="width:16px;height:16px;accent-color:var(--blue)">
            <span class="fw-600" style="color:var(--slate-800)">Aktifkan Tab CSS untuk Siswa</span>
          </label>
        </div>
        
        <div class="form-group" id="cssGroup">
          <label class="form-label">CSS Awal</label>
          <textarea name="starter_css" class="form-textarea" style="font-family:monospace;font-size:.8rem" rows="5">{{ old(\'starter_css\', $assignment->starter_css) }}</textarea>
        </div>
        
        <script>
          document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("toggleCss");
            const group = document.getElementById("cssGroup");
            toggle.addEventListener("change", function() {
               group.style.display = this.checked ? "block" : "none";
            });
            group.style.display = toggle.checked ? "block" : "none";
          });
        </script>';

file_put_contents($editFile, str_replace($oldEditCssBlock, $newEditCssBlock, $editHtml));
echo "Guru Views updated.\n";
?>