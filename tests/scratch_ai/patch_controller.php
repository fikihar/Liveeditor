<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\EditorController.php';
$content = file_get_contents($file);

$search = "return redirect()->route('siswa.dashboard')->with('success', \$msg);";
$replace = <<<PHP
        if (\$request->ajax()) {
            return response()->json(['success' => true, 'message' => \$msg]);
        }
        return redirect()->route('siswa.dashboard')->with('success', \$msg);
PHP;

if (strpos($content, '$request->ajax()') === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Controller patched for Ajax.\n";
} else {
    echo "Controller already patched.\n";
}
?>