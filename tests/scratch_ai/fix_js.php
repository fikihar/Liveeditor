<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

$newSwitchTab = <<<JS
    function renderPreview() {
        const frame = document.getElementById('preview-frame');
        if (!frame) return;
        const doc = frame.contentWindow.document;
        doc.open();
        
        let htmlCode = window.getHtmlCode ? window.getHtmlCode() : document.getElementById('raw-html-data').value;
        let cssCode = window.getCssCode ? window.getCssCode() : (document.getElementById('raw-css-data') ? document.getElementById('raw-css-data').value : '');
        
        if (cssCode.trim() !== '') {
            const externalCssRegex = /<link\s+[^>]*href=["']style\.css["'][^>]*>/gi;
            if (externalCssRegex.test(htmlCode)) {
                htmlCode = htmlCode.replace(externalCssRegex, `<style>\\n\${cssCode}\\n</style>`);
            } else {
                if (htmlCode.includes('</head>')) {
                    htmlCode = htmlCode.replace('</head>', `<style>\\n\${cssCode}\\n</style>\\n</head>`);
                } else {
                    htmlCode = `<style>\\n\${cssCode}\\n</style>\\n` + htmlCode;
                }
            }
        }

        doc.write(htmlCode);
        doc.close();
    }

    function switchTab(tabId, el) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
      
      if(el) el.classList.add('active');
      else document.querySelector(`.tab[onclick*="\${tabId}"]`).classList.add('active');
      
      document.getElementById('panel-' + tabId).classList.add('active');

      if (tabId === 'preview') {
          renderPreview();
      }
    }
    
    // Initial Render
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(renderPreview, 500);
    });
JS;

$html = preg_replace('/function switchTab\(tabId\).*?doc\.close\(\);\s*}\s*}/is', $newSwitchTab, $html);

// Now the debounce
$oldUpdateListener = <<<JS
    const updateListener = EditorView.updateListener.of((update) => {
        if (update.docChanged) {
            notifyTyping();
            if(window.getHtmlCode) { presenceChannel.whisper('code-update', { id: {{ auth()->id() }}, html: window.getHtmlCode(), css: window.getCssCode() }); }
        }
    });
JS;

$newUpdateListener = <<<JS
    let renderTimer;
    const updateListener = EditorView.updateListener.of((update) => {
        if (update.docChanged) {
            notifyTyping();
            if(window.getHtmlCode) { 
                presenceChannel.whisper('code-update', { id: {{ auth()->id() }}, html: window.getHtmlCode(), css: window.getCssCode() }); 
            }
            clearTimeout(renderTimer);
            renderTimer = setTimeout(renderPreview, 500);
        }
    });
JS;

$html = str_replace($oldUpdateListener, $newUpdateListener, $html);

file_put_contents($file, $html);
echo "JS replaced.\n";
?>