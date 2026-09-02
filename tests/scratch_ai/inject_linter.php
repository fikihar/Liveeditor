<?php
$file = 'c:\laragon\www\liveeditor\resources\views\siswa\editor.blade.php';
$html = file_get_contents($file);

// Inject import
if (strpos($html, '@codemirror/lint') === false) {
    $html = str_replace(
        'import { oneDark }',
        'import { linter, lintGutter } from "https://esm.sh/@codemirror/lint@6.0.0";
    import { oneDark }',
        $html
    );

    // Inject linter functions
    $linterFunctions = <<<JS

    function htmlLinter(view) {
        const diagnostics = [];
        const doc = view.state.doc.toString();
        const voidTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        
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
                        severity: "error", message: `Kelebihan tag penutup </\${tagName}> atau tag pembukanya tidak ada.`
                    });
                } else {
                    const last = stack.pop();
                    if (last.tagName !== tagName) {
                        diagnostics.push({
                            from: match.index, to: match.index + fullTag.length,
                            severity: "error", message: `Penutup tidak cocok! Diharapkan </\${last.tagName}> tapi yang ditulis </\${tagName}>.`
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
                severity: "error", message: `Lupa ditutup! Tag pembuka <\${item.tagName}> ini belum memiliki penutup.`
            });
        }
        return diagnostics;
    }

    function cssLinter(view) {
        const diagnostics = [];
        const doc = view.state.doc.toString();
        let braceStack = [];
        
        for (let i = 0; i < doc.length; i++) {
            if (doc[i] === '{') {
                braceStack.push(i);
            } else if (doc[i] === '}') {
                if (braceStack.length === 0) {
                    diagnostics.push({ from: i, to: i + 1, severity: "error", message: "Kelebihan kurung tutup '}'" });
                } else {
                    braceStack.pop();
                }
            }
        }
        
        for (let pos of braceStack) {
            diagnostics.push({ from: pos, to: pos + 1, severity: "error", message: "Lupa ditutup! Kurung kurawal pembuka '{' ini belum memiliki penutup '}'." });
        }
        return diagnostics;
    }

JS;

    $html = str_replace(
        'const updateListener =',
        $linterFunctions . "\n    const updateListener =",
        $html
    );

    // Inject into html extensions
    $html = str_replace(
        'extensions: [basicSetup, html(), oneDark',
        'extensions: [basicSetup, html(), linter(htmlLinter), lintGutter(), oneDark',
        $html
    );

    // Inject into css extensions
    $html = str_replace(
        'extensions: [basicSetup, css(), oneDark',
        'extensions: [basicSetup, css(), linter(cssLinter), lintGutter(), oneDark',
        $html
    );

    file_put_contents($file, $html);
    echo "Linter injected.\n";
} else {
    echo "Linter already injected.\n";
}
?>