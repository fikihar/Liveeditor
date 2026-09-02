<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// We need to replace the old linter functions with the new one.
// Let's use regex to find the functions block.
$pattern = '/function htmlLinter\(view\) \{.*?return diagnostics;\s*\}/is';

$newHtmlLinter = <<<JS
    function htmlLinter(view) {
        const diagnostics = [];
        const doc = view.state.doc.toString();
        const voidTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        const asgType = '{{ \$assignment->type }}';
        
        const tagRegex = /<\/?([a-z0-9]+)[^>]*>/gi;
        let match;
        const stack = [];

        while ((match = tagRegex.exec(doc)) !== null) {
            const fullTag = match[0];
            const tagName = match[1].toLowerCase();
            const isClosing = fullTag.startsWith('</');
            const isSelfClosing = fullTag.endsWith('/>') || voidTags.includes(tagName);

            if (isClosing) {
                if (stack.length === 0) {
                    diagnostics.push({
                        from: match.index, to: match.index + fullTag.length,
                        severity: "error", 
                        message: asgType === 'tugas' ? " " : `Kelebihan tag penutup </\${tagName}> atau tag pembukanya tidak ada.`
                    });
                } else {
                    const last = stack.pop();
                    if (last.tagName !== tagName) {
                        diagnostics.push({
                            from: match.index, to: match.index + fullTag.length,
                            severity: "error", 
                            message: asgType === 'tugas' ? " " : `Penutup tidak cocok! Diharapkan </\${last.tagName}> tapi yang ditulis </\${tagName}>.`
                        });
                    }
                }
            } else if (!isSelfClosing) {
                stack.push({ tagName: tagName, from: match.index, to: match.index + fullTag.length });
            }
        }

        for (let item of stack) {
            diagnostics.push({
                from: item.from, to: item.to,
                severity: "error", 
                message: asgType === 'tugas' ? " " : `Lupa ditutup! Tag pembuka <\${item.tagName}> ini belum memiliki penutup.`
            });
        }
        return diagnostics;
    }
JS;

$html = preg_replace($pattern, $newHtmlLinter, $html, 1);

$cssPattern = '/function cssLinter\(view\) \{.*?return diagnostics;\s*\}/is';
$newCssLinter = <<<JS
    function cssLinter(view) {
        const diagnostics = [];
        const doc = view.state.doc.toString();
        const asgType = '{{ \$assignment->type }}';
        let braceStack = [];
        
        for (let i = 0; i < doc.length; i++) {
            if (doc[i] === '{') {
                braceStack.push(i);
            } else if (doc[i] === '}') {
                if (braceStack.length === 0) {
                    diagnostics.push({ from: i, to: i + 1, severity: "error", message: asgType === 'tugas' ? " " : "Kelebihan kurung tutup '}'" });
                } else {
                    braceStack.pop();
                }
            }
        }
        
        for (let pos of braceStack) {
            diagnostics.push({ from: pos, to: pos + 1, severity: "error", message: asgType === 'tugas' ? " " : "Lupa ditutup! Kurung kurawal pembuka '{' ini belum memiliki penutup '}'." });
        }
        return diagnostics;
    }
JS;

$html = preg_replace($cssPattern, $newCssLinter, $html, 1);

file_put_contents($file, $html);
echo "Linter messages updated based on assignment type.\n";
?>