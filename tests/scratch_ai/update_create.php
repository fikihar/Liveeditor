<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\create.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // We will extract everything between "row.innerHTML = `" and "`;"
    $startStr = "row.innerHTML = `";
    $endStr = "`;";
    
    $start = strpos($content, $startStr);
    $end = strpos($content, $endStr, $start);
    
    if ($start !== false && $end !== false) {
        $oldInner = substr($content, $start, ($end - $start) + 2);
        
        $newInner = <<<JS
        row.style.cssText = 'background:#f8fafc; padding:16px; border:1px solid #e2e8f0; border-radius:12px; position:relative; box-shadow:0 2px 4px rgba(0,0,0,0.02); animation: fadeIn 0.3s ease; margin-bottom:12px;';
        row.innerHTML = `
            <button type="button" onclick="this.closest('.criteria-row').remove()" style="position:absolute; top:12px; right:12px; background:transparent; border:none; color:#ef4444; cursor:pointer; padding:4px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'" title="Hapus Kriteria">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap:12px; align-items:end; margin-bottom:12px; padding-right:24px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Tipe Cek</label>
                    <select name="criteria[\${criteriaCount}][type]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" required>
                        <option value="has_tag">Tag HTML Muncul</option>
                        <option value="has_attribute">Atribut Muncul</option>
                        <option value="has_text">Teks Tulisan</option>
                        <option value="has_css">Properti CSS</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Target (misal: h1)</label>
                    <input type="text" name="criteria[\${criteriaCount}][target]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" placeholder="button" required>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Nilai (Opsional)</label>
                    <input type="text" name="criteria[\${criteriaCount}][value]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" placeholder="red">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Poin</label>
                    <input type="number" name="criteria[\${criteriaCount}][points]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" value="10" min="1" required>
                </div>
            </div>
            <div>
                <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Pesan Error (Jika Gagal)</label>
                <input type="text" name="criteria[\${criteriaCount}][description]" class="form-input" style="padding:8px 12px; font-size:0.875rem; width:100%;" placeholder="Gunakan tag <h1>" required>
            </div>
        `;
JS;
        
        // Find the old row.style line before it
        $styleStart = strpos($content, "row.style.cssText =");
        if ($styleStart !== false && $styleStart < $start) {
            $fullOldBlock = substr($content, $styleStart, ($end - $styleStart) + 2);
            $content = str_replace($fullOldBlock, $newInner, $content);
        }
        
        // Also update the PHP-rendered rows in edit.blade.php
        if (strpos($file, 'edit.blade.php') !== false) {
            $phpRowRegex = '/<div class="criteria-row".*?<\/div>\s*<\/div>\s*<\/div>/is'; // Very fragile.
            // Let's just do a manual string replace for edit blade
        }

        // Add fadeIn animation style
        if (strpos($content, '@keyframes fadeIn') === false) {
            $content = str_replace('</style>', "  @keyframes fadeIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }\n</style>", $content);
        }

        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
?>