<?php
$file = 'c:\laragon\www\liveeditor\app\Http\Controllers\Siswa\DashboardController.php';
$content = file_get_contents($file);

if (strpos($content, 'function history(') === false) {
    $newMethod = <<<PHP

    public function history()
    {
        \$siswa = auth()->user();
        \$kelasId = \$siswa->class_id;

        \$assignments = \App\Models\Assignment::published()
            ->where('class_id', \$kelasId)
            ->with(['submissions' => function (\$q) use (\$siswa) {
                \$q->where('student_id', \$siswa->id);
            }])
            ->latest()
            ->get();

        return view('siswa.history', compact('siswa', 'assignments'));
    }
}
PHP;

    $content = preg_replace('/\}\s*$/', $newMethod, $content);
    file_put_contents($file, $content);
    echo "Controller updated.\n";
} else {
    echo "Controller method already exists.\n";
}
?>