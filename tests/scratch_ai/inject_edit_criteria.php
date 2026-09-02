<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php';
$html = file_get_contents($file);

$criteriaUI = '
        <!-- KRITERIA PENILAIAN -->
        <hr style="border-top:1px solid #e2e8f0;margin:32px 0;">
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:8px;">Kriteria Penilaian Otomatis (Opsional)</h3>
        <p style="font-size:0.875rem;color:#64748b;margin-bottom:16px;">Tentukan aturan kunci jawaban untuk tugas ini. Kosongkan jika tidak butuh penilaian otomatis.</p>

        <div id="criteria-container" style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
            @foreach($assignment->gradingCriteria ?? [] as $i => $crit)
            <div class="criteria-row" style="display:flex;gap:12px;align-items:center;background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px;">
                <input type="hidden" name="criteria[{{ $i }}][id]" value="{{ $crit->id }}">
                <div style="flex:2;">
                    <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Tipe Cek</label>
                    <select name="criteria[{{ $i }}][type]" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                        <option value="has_tag" {{ $crit->type == \'has_tag\' ? \'selected\' : \'\' }}>Tag HTML Muncul</option>
                        <option value="has_attribute" {{ $crit->type == \'has_attribute\' ? \'selected\' : \'\' }}>Atribut Muncul</option>
                        <option value="has_text" {{ $crit->type == \'has_text\' ? \'selected\' : \'\' }}>Teks Tulisan</option>
                        <option value="has_css" {{ $crit->type == \'has_css\' ? \'selected\' : \'\' }}>Properti CSS</option>
                    </select>
                </div>
                <div style="flex:2;">
                    <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Target</label>
                    <input type="text" name="criteria[{{ $i }}][target]" value="{{ $crit->target }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                </div>
                <div style="flex:2;">
                    <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Nilai (Opsional)</label>
                    <input type="text" name="criteria[{{ $i }}][value]" value="{{ $crit->value }}" class="form-control" style="padding:6px;font-size:0.875rem;">
                </div>
                <div style="flex:1;">
                    <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Poin</label>
                    <input type="number" name="criteria[{{ $i }}][points]" value="{{ $crit->points }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                </div>
                <div style="flex:3;">
                    <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Deskripsi</label>
                    <input type="text" name="criteria[{{ $i }}][description]" value="{{ $crit->description }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:600;color:transparent;display:block;">Aksi</label>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="btn btn-ghost btn-sm" style="color:#ef4444;padding:6px 10px;">&times;</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" onclick="addCriteriaRow()" class="btn btn-secondary btn-sm" style="display:inline-flex;align-items:center;gap:4px;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kriteria
        </button>
';

$jsScript = "
@endsection

@push('scripts')
<script>
    let criteriaCount = {{ count(\$assignment->gradingCriteria ?? []) }};

    function addCriteriaRow() {
        const container = document.getElementById('criteria-container');
        const row = document.createElement('div');
        row.className = 'criteria-row';
        row.style.cssText = 'display:flex;gap:12px;align-items:center;background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px;';
        
        row.innerHTML = `
            <div style=\"flex:2;\">
                <label style=\"font-size:0.75rem;font-weight:600;color:#64748b;\">Tipe Cek</label>
                <select name=\"criteria[\${criteriaCount}][type]\" class=\"form-control\" style=\"padding:6px;font-size:0.875rem;\" required>
                    <option value=\"has_tag\">Tag HTML Muncul</option>
                    <option value=\"has_attribute\">Atribut Muncul</option>
                    <option value=\"has_text\">Teks Tulisan</option>
                    <option value=\"has_css\">Properti CSS</option>
                </select>
            </div>
            <div style=\"flex:2;\">
                <label style=\"font-size:0.75rem;font-weight:600;color:#64748b;\">Target (misal: h1)</label>
                <input type=\"text\" name=\"criteria[\${criteriaCount}][target]\" class=\"form-control\" style=\"padding:6px;font-size:0.875rem;\" placeholder=\"button\" required>
            </div>
            <div style=\"flex:2;\">
                <label style=\"font-size:0.75rem;font-weight:600;color:#64748b;\">Nilai (Opsional)</label>
                <input type=\"text\" name=\"criteria[\${criteriaCount}][value]\" class=\"form-control\" style=\"padding:6px;font-size:0.875rem;\" placeholder=\"red\">
            </div>
            <div style=\"flex:1;\">
                <label style=\"font-size:0.75rem;font-weight:600;color:#64748b;\">Poin</label>
                <input type=\"number\" name=\"criteria[\${criteriaCount}][points]\" class=\"form-control\" style=\"padding:6px;font-size:0.875rem;\" value=\"10\" min=\"1\" max=\"100\" required>
            </div>
            <div style=\"flex:3;\">
                <label style=\"font-size:0.75rem;font-weight:600;color:#64748b;\">Deskripsi Eror</label>
                <input type=\"text\" name=\"criteria[\${criteriaCount}][description]\" class=\"form-control\" style=\"padding:6px;font-size:0.875rem;\" placeholder=\"Wajib ada tag h1\" required>
            </div>
            <div>
                <label style=\"font-size:0.75rem;font-weight:600;color:transparent;display:block;\">Aksi</label>
                <button type=\"button\" onclick=\"this.parentElement.parentElement.remove()\" class=\"btn btn-ghost btn-sm\" style=\"color:#ef4444;padding:6px 10px;\">&times;</button>
            </div>
        `;
        container.appendChild(row);
        criteriaCount++;
    }
</script>
@endpush
";

if (strpos($html, 'criteria-container') === false) {
    $html = str_replace(
        '<div class="card-footer form-actions">',
        $criteriaUI . "\n      </div>\n      <div class=\"card-footer form-actions\">",
        $html
    );
    $html = str_replace('@endsection', $jsScript, $html);
    file_put_contents($file, $html);
    echo "Edit Criteria UI injected.\n";
} else {
    echo "Already injected.\n";
}
?>