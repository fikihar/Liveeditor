<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php';
$html = file_get_contents($file);

// Clean up duplicate blocks if any
// This regex will replace all occurrences of "// Bind code-update event... });" with just one correct block.
$html = preg_replace('/(\/\/ Bind code-update event.*?\}\);)+/is', 
"// Bind code-update event
  Echo.join(`assignment.{{ \$assignment->id }}`)
      .listenForWhisper('code-update', (e) => {
          let studentId = e.id || (e.user ? e.user.id : null);
          if (!studentId) return;
          
          const btn = document.getElementById('cctv-btn-' + studentId);
          if (btn) btn.style.display = 'inline-block';
          
          window.studentCodes[studentId] = { html: e.html, css: e.css };
          
          if (window.activeCctvStudent == studentId) {
              const htmlEl = document.getElementById('cctv-html');
              const cssEl = document.getElementById('cctv-css');
              if (htmlEl) htmlEl.textContent = e.html;
              if (cssEl) cssEl.textContent = e.css;
          }
      });", $html);

file_put_contents($file, $html);
echo "Show blade fixed for e.id.\n";
?>