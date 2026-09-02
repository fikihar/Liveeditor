<?php
$files = [
    'c:\laragon\www\liveeditor\resources\views\siswa\dashboard.blade.php',
    'c:\laragon\www\liveeditor\resources\views\siswa\history.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // 1. Add CSS for Bottom Nav
    $css = <<<CSS
    /* ---- BOTTOM NAV (MOBILE ONLY) ---- */
    .bottom-nav {
        display: none;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: white;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
        border-top: 1px solid var(--gray-200);
    }
    .bottom-nav-inner {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 60px;
    }
    .bottom-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        color: var(--gray-400);
        text-decoration: none;
        font-size: 0.65rem;
        font-weight: 600;
        flex: 1;
        height: 100%;
        transition: color 0.2s;
    }
    .bottom-nav-item svg { width: 22px; height: 22px; stroke-width: 2px; }
    .bottom-nav-item.active { color: var(--blue); }
    .bottom-nav-item.active svg { stroke-width: 2.5px; }

    @media (max-width: 640px) {
        .navbar .nav-links { display: none; } /* Hide top links on mobile */
        .bottom-nav { display: block; }
        body { padding-bottom: 70px; } /* Space for bottom nav */
    }
    </style>
CSS;
    
    if (strpos($content, '.bottom-nav {') === false) {
        $content = str_replace('</style>', $css, $content);
    }

    // 2. Add Bottom Nav HTML before </body>
    $isBeranda = strpos($file, 'dashboard.blade.php') !== false ? 'active' : '';
    $isRiwayat = strpos($file, 'history.blade.php') !== false ? 'active' : '';

    $html = <<<HTML
    <!-- BOTTOM NAVIGATION (MOBILE) -->
    <nav class="bottom-nav">
        <div class="bottom-nav-inner">
            <a href="{{ route('siswa.dashboard') }}" class="bottom-nav-item {$isBeranda}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="{{ route('siswa.riwayat') }}" class="bottom-nav-item {$isRiwayat}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat
            </a>
            <a href="#" class="bottom-nav-item" onclick="event.preventDefault(); confirmLogout();">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>
        </div>
    </nav>
  </body>
HTML;
    
    if (strpos($content, '<nav class="bottom-nav">') === false) {
        $content = str_replace('</body>', $html, $content);
    }
    
    file_put_contents($file, $content);
    echo "Updated bottom nav in $file\n";
}
?>