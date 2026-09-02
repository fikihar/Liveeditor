<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// 1. UPDATE CSS for Desktop Split View
$styleSearch = "/* Iframe */";
$styleReplace = <<<CSS
      /* Desktop Split View */
      @media(min-width: 768px) {
          .tabs { display: none !important; }
          .content-area {
              display: grid !important;
              grid-template-columns: 1fr 1fr;
              grid-template-rows: {{ \$assignment->has_css ? '1fr 1fr' : '1fr' }};
          }
          .panel { position: relative !important; display: flex !important; }
          #panel-html { grid-column: 1; grid-row: 1; border-right: 1px solid #334155; }
          #panel-css { grid-column: 1; grid-row: 2; border-right: 1px solid #334155; border-top: 1px solid #334155; }
          #panel-preview { grid-column: 2; grid-row: 1 / span {{ \$assignment->has_css ? '2' : '1' }}; }
          
          /* Panel Labels */
          #panel-html::before { content: 'HTML'; position: absolute; top: 0; right: 0; background: rgba(0,0,0,0.5); padding: 2px 8px; font-size: 10px; color: #94a3b8; z-index: 10; border-bottom-left-radius: 6px; }
          #panel-css::before { content: 'CSS'; position: absolute; top: 0; right: 0; background: rgba(0,0,0,0.5); padding: 2px 8px; font-size: 10px; color: #94a3b8; z-index: 10; border-bottom-left-radius: 6px; }
      }
      
      /* Iframe */
CSS;
$html = str_replace($styleSearch, $styleReplace, $html);

// 2. EXTRACT renderPreview() logic and debounce it in CodeMirror
// First, extract from switchTab
$oldSwitchTab = <<<JS
    function switchTab(tabId) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
      
      event.target.classList.add('active');
      document.getElementById('panel-' + tabId).classList.add('active');

      if (tabId === 'preview') {
        const doc = document.getElementById('preview-frame').contentWindow.document;
        doc.open();
        
        let htmlCode = window.getHtmlCode ? window.getHtmlCode() : document.getElementById('starter-html-data').value;
        let cssCode = window.getCssCode ? window.getCssCode() : (document.getElementById('starter-css-data') ? document.getElementById('starter-css-data').value : '');
        
        if (cssCode.trim() !== '') {
            const externalCssRegex = /<link\s+[^>]*rel=['"]stylesheet['"][^>]*>/i;
            if (externalCssRegex.test(htmlCode)) {
                htmlCode = htmlCode.replace(externalCssRegex, `<style>\\n\${cssCode}\\n</style>`);
            } else {
                htmlCode = htmlCode.replace('</head>', `<style>\\n\${cssCode}\\n</style>\\n</head>`);
            }
        }
        // Jika tidak ditulis, Tab CSS akan diabaikan (hanya mengandalkan HTML / Inline / Internal CSS)

        doc.write(htmlCode);
        doc.close();
      }
    }
JS;

$newSwitchTab = <<<JS
    function renderPreview() {
        const frame = document.getElementById('preview-frame');
        if (!frame) return;
        const doc = frame.contentWindow.document;
        doc.open();
        
        let htmlCode = window.getHtmlCode ? window.getHtmlCode() : document.getElementById('raw-html-data').value;
        let cssCode = window.getCssCode ? window.getCssCode() : (document.getElementById('raw-css-data') ? document.getElementById('raw-css-data').value : '');
        
        if (cssCode.trim() !== '') {
            const externalCssRegex = /<link\s+[^>]*rel=['"]stylesheet['"][^>]*>/i;
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
$html = str_replace($oldSwitchTab, $newSwitchTab, $html);

// Update HTML onClick handlers to pass `this`
$html = str_replace("onclick=\"switchTab('html')\"", "onclick=\"switchTab('html', this)\"", $html);
$html = str_replace("onclick=\"switchTab('css')\"", "onclick=\"switchTab('css', this)\"", $html);
$html = str_replace("onclick=\"switchTab('preview')\"", "onclick=\"switchTab('preview', this)\"", $html);


// 3. Add debounce to updateListener
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
            // Auto-render live preview on desktop
            clearTimeout(renderTimer);
            renderTimer = setTimeout(renderPreview, 500);
        }
    });
JS;
$html = str_replace($oldUpdateListener, $newUpdateListener, $html);


file_put_contents($file, $html);
echo "Editor updated for Desktop Split View & Mobile Toggle.\n";
?>