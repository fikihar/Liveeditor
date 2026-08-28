<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$html = file_get_contents($file);

// 1. TAMBAH TOMBOL CCTV
$tdMain = <<<HTML
                  <span id="cheat-{{ \$siswa->id }}" class="cheat-indicator" style="display:none; font-size:11px; color:#dc2626; margin-left:8px; font-weight:700; background:#fee2e2; padding:2px 6px; border-radius:4px; animation: blink 1s infinite;">⚠️ Keluar Layar!</span>
                  <button id="cctv-btn-{{ \$siswa->id }}" onclick="openLiveView({{ \$siswa->id }}, '{{ addslashes(\$siswa->name) }}')" style="display:none; margin-left:8px; border:none; background:#eff6ff; color:#3b82f6; padding:2px 6px; border-radius:4px; font-size:11px; cursor:pointer; font-weight:600;"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:2px;margin-top:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Live</button>
HTML;
$html = preg_replace('/<span id="cheat-{{ \$siswa->id }}".*?<\/span>/is', $tdMain, $html);

// 2. TAMPILKAN CHEAT COUNT DARI DATABASE
$badgeAnchor = "@else\n                    \n                    @if(\$sub->score !== null)";
$newBadgeAnchor = <<<HTML
@else
                    @if(\$sub->cheat_count > 0 && \$assignment->type === 'tugas')
                      <span class="badge" style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;margin-bottom:4px;display:block;width:max-content;">⚠️ Terdeteksi Keluar: {{ \$sub->cheat_count }}x</span>
                    @endif
                    @if(\$sub->score !== null)
HTML;
$html = str_replace($badgeAnchor, $newBadgeAnchor, $html);


// 3. TAMBAHKAN JS LOGIC UNTUK CCTV
$jsAnchor = "</script>";
$newJs = <<<JS
  window.studentCodes = {};
  
  function openLiveView(studentId, studentName) {
      if(!window.studentCodes[studentId]) return;
      let data = window.studentCodes[studentId];
      Swal.fire({
          title: 'Live CCTV: ' + studentName,
          html: `
            <div style="text-align:left;">
              <div style="font-weight:600;font-size:12px;color:#ef4444;margin-bottom:4px;">HTML Code</div>
              <pre id="cctv-html" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:200px;">\${data.html.replace(/</g, '&lt;')}</pre>
              <div style="font-weight:600;font-size:12px;color:#3b82f6;margin-top:12px;margin-bottom:4px;">CSS Code</div>
              <pre id="cctv-css" style="background:#1e293b;color:#f8fafc;padding:12px;border-radius:6px;font-size:12px;overflow-x:auto;max-height:150px;">\${data.css.replace(/</g, '&lt;')}</pre>
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
  Echo.join(`assignment.{{ \$assignment->id }}`)
      .listenForWhisper('code-update', (e) => {
          // Tampilkan tombol cctv jika belum muncul
          const btn = document.getElementById('cctv-btn-' + e.user.id);
          if (btn) btn.style.display = 'inline-block';
          
          window.studentCodes[e.user.id] = { html: e.html, css: e.css };
          
          // Jika modal siswa ini sedang terbuka, update real-time
          if (window.activeCctvStudent === e.user.id) {
              const htmlEl = document.getElementById('cctv-html');
              const cssEl = document.getElementById('cctv-css');
              if (htmlEl) htmlEl.innerHTML = e.html.replace(/</g, '&lt;');
              if (cssEl) cssEl.innerHTML = e.css.replace(/</g, '&lt;');
          }
      });
</script>
JS;
$html = str_replace($jsAnchor, $newJs, $html);

file_put_contents($file, $html);
echo "Show view updated for CCTV and cheat count.\n";
?>