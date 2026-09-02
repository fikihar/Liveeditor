<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

$autoSaveJS = <<<JS
  <script>
    // AUTO SAVE BACKGROUND
    let lastSavedHtml = document.getElementById('raw-html-data').value;
    let lastSavedCss = document.getElementById('raw-css-data').value;
    
    setInterval(() => {
        if (!window.getHtmlCode) return;
        
        let currentHtml = window.getHtmlCode();
        let currentCss = window.getCssCode ? window.getCssCode() : '';
        
        if (currentHtml !== lastSavedHtml || currentCss !== lastSavedCss) {
            let formData = new FormData();
            formData.append('html_code', currentHtml);
            formData.append('css_code', currentCss);
            formData.append('action', 'save');
            
            fetch("{{ route('siswa.editor.submit', \$assignment) }}", {
                method: "POST",
                headers: { 
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            }).then(r => r.json()).then(res => {
                if(res.success) {
                    lastSavedHtml = currentHtml;
                    lastSavedCss = currentCss;
                }
            });
        }
    }, 15000); // 15 detik
  </script>
</body>
JS;

if (strpos($html, 'AUTO SAVE BACKGROUND') === false) {
    $html = str_replace('</body>', $autoSaveJS, $html);
    file_put_contents($file, $html);
    echo "Auto-save script added to editor.\n";
} else {
    echo "Auto-save script already exists.\n";
}
?>