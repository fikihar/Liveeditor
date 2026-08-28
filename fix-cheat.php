<?php
// UPDATE SISWA EDITOR
$editorPath = __DIR__ . '/resources/views/siswa/editor.blade.php';
$html = file_get_contents($editorPath);

$oldVis = <<<JS
    // Anti-Cheat (Page Visibility)
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "hidden") {
        fetch("{{ route('siswa.editor.log', \$assignment) }}", {
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
JS;

$newVis = <<<JS
    // Anti-Cheat (Page Visibility)
    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "hidden") {
        presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: true });
        fetch("{{ route('siswa.editor.log', \$assignment) }}", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({ event: 'tab_switch' })
        });
      } else {
        presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: false });
        const alert = document.getElementById('cheatAlert');
        alert.classList.add('show');
        setTimeout(() => alert.classList.remove('show'), 5000);
      }
    });
JS;

$html = str_replace($oldVis, $newVis, $html);
file_put_contents($editorPath, $html);

// UPDATE GURU SHOW
$showPath = __DIR__ . '/resources/views/guru/tugas/show.blade.php';
$showHtml = file_get_contents($showPath);

$oldTd = '<span id="typing-{{ $siswa->id }}" class="typing-indicator" style="display:none; font-size:11px; color:#3b82f6; margin-left:8px; font-style:italic">mengetik...</span>';
$newTd = '<span id="typing-{{ $siswa->id }}" class="typing-indicator" style="display:none; font-size:11px; color:#3b82f6; margin-left:8px; font-style:italic">mengetik...</span>' . "\n                " . '<span id="cheat-{{ $siswa->id }}" class="cheat-indicator" style="display:none; font-size:11px; color:#dc2626; margin-left:8px; font-weight:700; background:#fee2e2; padding:2px 6px; border-radius:4px; animation: blink 1s infinite;">⚠️ Keluar Layar!</span>';

$showHtml = str_replace($oldTd, $newTd, $showHtml);

$oldListen = <<<JS
  .listenForWhisper('typing', (e) => {
      const typingEl = document.getElementById('typing-' + e.id);
      if(typingEl) {
          typingEl.style.display = e.typing ? 'inline' : 'none';
      }
  });
JS;

$newListen = <<<JS
  .listenForWhisper('typing', (e) => {
      const typingEl = document.getElementById('typing-' + e.id);
      if(typingEl) {
          typingEl.style.display = e.typing ? 'inline' : 'none';
      }
  })
  .listenForWhisper('cheat', (e) => {
      const cheatEl = document.getElementById('cheat-' + e.id);
      const dot = document.getElementById('status-dot-' + e.id);
      if(cheatEl && dot) {
          cheatEl.style.display = e.cheating ? 'inline' : 'none';
          if(e.cheating) {
              dot.style.background = '#f59e0b'; // Ubah titik jadi orange/kuning
              dot.style.boxShadow = '0 0 6px #f59e0b';
          } else {
              dot.style.background = ''; // Kembalikan ke hijau (CSS default)
              dot.style.boxShadow = '';
          }
      }
  });
JS;

$showHtml = str_replace($oldListen, $newListen, $showHtml);
file_put_contents($showPath, $showHtml);

echo "Update Anti-Cheat berhasil!\n";
?>