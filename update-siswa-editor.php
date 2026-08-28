<?php
$editorFile = __DIR__ . '/resources/views/siswa/editor.blade.php';
$html = file_get_contents($editorFile);

// 1. Hide CSS Tab button
$oldTab = '<div class="tab" onclick="switchTab(\'css\')">CSS</div>';
$newTab = '@if($assignment->has_css) <div class="tab" onclick="switchTab(\'css\')">CSS</div> @endif';
$html = str_replace($oldTab, $newTab, $html);

// 2. Hide CSS Panel
$oldPanel = '<div class="panel" id="panel-css"></div>';
$newPanel = '@if($assignment->has_css) <div class="panel" id="panel-css"></div> @endif';
$html = str_replace($oldPanel, $newPanel, $html);

// 3. Update CodeMirror Initialization logic
$oldScript = <<<'JS'
    const initCss = document.getElementById('raw-css-data').value;

    const htmlEditor = new EditorView({
      doc: initHtml,
      extensions: [basicSetup, html(), oneDark],
      parent: document.getElementById('panel-html')
    });

    const cssEditor = new EditorView({
      doc: initCss,
      extensions: [basicSetup, css(), oneDark],
      parent: document.getElementById('panel-css')
    });

    // Replace fallback
    window.getHtmlCode = () => htmlEditor.state.doc.toString();
    window.getCssCode = () => cssEditor.state.doc.toString();
JS;

$newScript = <<<'JS'
    const htmlEditor = new EditorView({
      doc: initHtml,
      extensions: [basicSetup, html(), oneDark],
      parent: document.getElementById('panel-html')
    });
    window.getHtmlCode = () => htmlEditor.state.doc.toString();

    @if($assignment->has_css)
      const initCss = document.getElementById('raw-css-data').value;
      const cssEditor = new EditorView({
        doc: initCss,
        extensions: [basicSetup, css(), oneDark],
        parent: document.getElementById('panel-css')
      });
      window.getCssCode = () => cssEditor.state.doc.toString();
    @else
      window.getCssCode = () => '';
    @endif
JS;

$html = str_replace($oldScript, $newScript, $html);
file_put_contents($editorFile, $html);
echo "Editor view updated.\n";
?>