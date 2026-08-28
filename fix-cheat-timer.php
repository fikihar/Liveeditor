<?php
$editorPath = __DIR__ . '/resources/views/siswa/editor.blade.php';
$html = file_get_contents($editorPath);

$oldVis = <<<JS
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

$newVis = <<<JS
    // Anti-Cheat (Page Visibility)
    let cheatTimer;
    let isCheating = false;

    document.addEventListener("visibilitychange", () => {
      if (document.visibilityState === "hidden") {
        // Beri toleransi 1.5 detik (mencegah salah deteksi akibat iframe/keyboard/notifikasi)
        cheatTimer = setTimeout(() => {
            isCheating = true;
            presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: true });
            fetch("{{ route('siswa.editor.log', \$assignment) }}", {
              method: "POST",
              headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
              body: JSON.stringify({ event: 'tab_switch' })
            });
        }, 1500);
      } else {
        clearTimeout(cheatTimer);
        if (isCheating) {
            presenceChannel.whisper('cheat', { id: {{ auth()->id() }}, cheating: false });
            const alert = document.getElementById('cheatAlert');
            alert.classList.add('show');
            setTimeout(() => alert.classList.remove('show'), 5000);
            isCheating = false;
        }
      }
    });
JS;

$html = str_replace($oldVis, $newVis, $html);
file_put_contents($editorPath, $html);
echo "Toleransi anti-cheat ditambahkan.\n";
?>