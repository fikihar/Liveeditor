<?php
$editorFile = __DIR__ . '/resources/views/siswa/editor.blade.php';
$html = file_get_contents($editorFile);

$oldPreviewLogic = <<<'JS'
      if (tabId === 'preview') {
        const doc = document.getElementById('preview-frame').contentWindow.document;
        doc.open();
        doc.write(`
          ${window.getHtmlCode()}
          <style>${window.getCssCode()}</style>
        `);
        doc.close();
      }
JS;

$newPreviewLogic = <<<'JS'
      if (tabId === 'preview') {
        const doc = document.getElementById('preview-frame').contentWindow.document;
        doc.open();
        
        let htmlCode = window.getHtmlCode();
        let cssCode = window.getCssCode();
        
        // Simulasi External CSS: Cari tag <link rel="stylesheet" href="style.css">
        const externalCssRegex = /<link\s+[^>]*href=["']style\.css["'][^>]*>/gi;
        
        // Jika siswa menuliskan link eksternalnya, kita ganti link tersebut dengan isi Tab CSS
        if (externalCssRegex.test(htmlCode)) {
            htmlCode = htmlCode.replace(externalCssRegex, `<style>\n${cssCode}\n</style>`);
        }
        // Jika tidak ditulis, Tab CSS akan diabaikan (hanya mengandalkan HTML / Inline / Internal CSS)

        doc.write(htmlCode);
        doc.close();
      }
JS;

$html = str_replace($oldPreviewLogic, $newPreviewLogic, $html);
file_put_contents($editorFile, $html);
echo "Sistem Simulasi External CSS berhasil diterapkan!\n";
?>