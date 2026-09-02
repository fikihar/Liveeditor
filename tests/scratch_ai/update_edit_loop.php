<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php';
$content = file_get_contents($file);

$oldRow = <<<HTML
              @foreach(\$assignment->gradingCriteria ?? [] as \$i => \$crit)
              <div class="criteria-row" style="display:flex;gap:12px;align-items:center;background:#f8fafc;padding:12px;border:1px solid #e2e8f0;border-radius:8px;">
                  <input type="hidden" name="criteria[{{ \$i }}][id]" value="{{ \$crit->id }}">
                  <div style="flex:2;">
                      <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Tipe Cek</label>
                      <select name="criteria[{{ \$i }}][type]" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                          <option value="has_tag" {{ \$crit->type == 'has_tag' ? 'selected' : '' }}>Tag HTML Muncul</option>
                          <option value="has_attribute" {{ \$crit->type == 'has_attribute' ? 'selected' : '' }}>Atribut Muncul</option>
                          <option value="has_text" {{ \$crit->type == 'has_text' ? 'selected' : '' }}>Teks Tulisan</option>
                          <option value="has_css" {{ \$crit->type == 'has_css' ? 'selected' : '' }}>Properti CSS</option>
                      </select>
                  </div>
                  <div style="flex:2;">
                      <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Target</label>
                      <input type="text" name="criteria[{{ \$i }}][target]" value="{{ \$crit->target }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                  </div>
                  <div style="flex:2;">
                      <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Nilai (Opsional)</label>
                      <input type="text" name="criteria[{{ \$i }}][value]" value="{{ \$crit->value }}" class="form-control" style="padding:6px;font-size:0.875rem;">
                  </div>
                  <div style="flex:1;">
                      <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Poin</label>
                      <input type="number" name="criteria[{{ \$i }}][points]" value="{{ \$crit->points }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                  </div>
                  <div style="flex:3;">
                      <label style="font-size:0.75rem;font-weight:600;color:#64748b;">Deskripsi</label>
                      <input type="text" name="criteria[{{ \$i }}][description]" value="{{ \$crit->description }}" class="form-control" style="padding:6px;font-size:0.875rem;" required>
                  </div>
                  <button type="button" onclick="this.parentElement.remove()" class="btn btn-secondary btn-sm" style="color:#ef4444;background:#fef2f2;border:none;">Hapus</button>
              </div>
              @endforeach
HTML;

$newRow = <<<HTML
              @foreach(\$assignment->gradingCriteria ?? [] as \$i => \$crit)
              <div class="criteria-row" style="background:#f8fafc; padding:16px; border:1px solid #e2e8f0; border-radius:12px; position:relative; box-shadow:0 2px 4px rgba(0,0,0,0.02); margin-bottom:12px;">
                  <input type="hidden" name="criteria[{{ \$i }}][id]" value="{{ \$crit->id }}">
                  <button type="button" onclick="this.closest('.criteria-row').remove()" style="position:absolute; top:12px; right:12px; background:transparent; border:none; color:#ef4444; cursor:pointer; padding:4px; border-radius:6px; transition:background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'" title="Hapus Kriteria">
                      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                  <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap:12px; align-items:end; margin-bottom:12px; padding-right:24px;">
                      <div>
                          <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Tipe Cek</label>
                          <select name="criteria[{{ \$i }}][type]" class="form-input" style="padding:8px 12px; font-size:0.875rem;" required>
                              <option value="has_tag" {{ \$crit->type == 'has_tag' ? 'selected' : '' }}>Tag HTML Muncul</option>
                              <option value="has_attribute" {{ \$crit->type == 'has_attribute' ? 'selected' : '' }}>Atribut Muncul</option>
                              <option value="has_text" {{ \$crit->type == 'has_text' ? 'selected' : '' }}>Teks Tulisan</option>
                              <option value="has_css" {{ \$crit->type == 'has_css' ? 'selected' : '' }}>Properti CSS</option>
                          </select>
                      </div>
                      <div>
                          <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Target</label>
                          <input type="text" name="criteria[{{ \$i }}][target]" value="{{ \$crit->target }}" class="form-input" style="padding:8px 12px; font-size:0.875rem;" required>
                      </div>
                      <div>
                          <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Nilai (Opsional)</label>
                          <input type="text" name="criteria[{{ \$i }}][value]" value="{{ \$crit->value }}" class="form-input" style="padding:8px 12px; font-size:0.875rem;">
                      </div>
                      <div>
                          <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Poin</label>
                          <input type="number" name="criteria[{{ \$i }}][points]" value="{{ \$crit->points }}" class="form-input" style="padding:8px 12px; font-size:0.875rem;" required>
                      </div>
                  </div>
                  <div>
                      <label style="display:block; font-size:0.75rem; font-weight:600; color:#64748b; margin-bottom:4px;">Pesan Error (Jika Gagal)</label>
                      <input type="text" name="criteria[{{ \$i }}][description]" value="{{ \$crit->description }}" class="form-input" style="padding:8px 12px; font-size:0.875rem; width:100%;" required>
                  </div>
              </div>
              @endforeach
HTML;

$content = str_replace($oldRow, $newRow, $content);
file_put_contents($file, $content);
echo "edit.blade.php updated.\n";
?>