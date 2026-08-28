<?php
function replaceEmojisWithSVGs($filePath) {
    if (!file_exists($filePath)) return;
    $content = file_get_contents($filePath);
    
    // Ganti emoji / corrupted string dengan SVG icon
    $replacements = [
        // Corrupted characters
        'ðŸ”' => '', 'ðŸ‘‹' => '', 'ðŸ“‹' => '', 'ðŸ’¡' => '', 'ðŸ“š' => '', 'Â·' => '&bull;',
        
        // Emojis
        '👋' => '',
        '🏫' => '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
        '👥' => '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        '📋' => '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>',
        '✅' => '<svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        '·'  => '&bull;'
    ];

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($filePath, $content);
}

// Fix file-file Guru
replaceEmojisWithSVGs(__DIR__ . '/resources/views/guru/dashboard.blade.php');
replaceEmojisWithSVGs(__DIR__ . '/resources/views/guru/kelas/index.blade.php');
replaceEmojisWithSVGs(__DIR__ . '/resources/views/guru/kelas/show.blade.php');
replaceEmojisWithSVGs(__DIR__ . '/resources/views/guru/tugas/index.blade.php');
replaceEmojisWithSVGs(__DIR__ . '/resources/views/guru/siswa/index.blade.php');

echo "Guru files fixed.\n";
?>