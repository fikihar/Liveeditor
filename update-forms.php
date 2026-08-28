<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\index.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\tugas\show.blade.php',
    'c:\laragon\www\liveeditor\resources\views\guru\kelas\index.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $html = file_get_contents($file);
        
        // Remove old onsubmit
        $html = preg_replace('/onsubmit="return confirm\([^)]+\);"/i', '', $html);
        
        // Add class="form-delete" and data-confirm
        // We will just replace method="POST" with method="POST" class="form-delete" data-confirm="Data yang dihapus tidak bisa dikembalikan!"
        // Actually, let's just make it simpler.
        $html = str_replace('<form action="{{ route(\'guru.tugas.destroy\', $tugas) }}" method="POST" >', '<form action="{{ route(\'guru.tugas.destroy\', $tugas) }}" method="POST" class="form-delete" data-confirm="Semua nilai dan file siswa terkait tugas/latihan ini juga akan ikut terhapus!">', $html);
        
        $html = str_replace('<form action="{{ route(\'guru.tugas.destroy\', $assignment) }}" method="POST"  style="margin:0;">', '<form action="{{ route(\'guru.tugas.destroy\', $assignment) }}" method="POST" class="form-delete" data-confirm="Semua jawaban siswa terkait juga akan terhapus!" style="margin:0;">', $html);
        
        // For Kelas
        $html = preg_replace('/<form action="{{ route\(\'guru.kelas.destroy\'([^>]+)> /i', '<form action="{{ route(\'guru.kelas.destroy\'$1 class="form-delete" data-confirm="Semua siswa dan tugas di kelas ini akan ikut terhapus!">', $html);

        file_put_contents($file, $html);
    }
}
echo "Forms updated.\n";
?>