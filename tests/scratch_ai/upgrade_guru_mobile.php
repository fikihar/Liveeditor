<?php
// 1. UPDATE CSS
$cssFile = 'c:\laragon\www\liveeditor\public\css\guru.css';
$css = file_get_contents($cssFile);

$mobileCSS = <<<CSS

/* ========== MOBILE RESPONSIVENESS ========== */
@media (max-width: 768px) {
    :root {
        --sidebar-w: 240px; /* Tetap sama untuk offcanvas */
    }
    
    /* Sembunyikan sidebar di kiri luar layar */
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    }
    .sidebar.open {
        transform: translateX(0);
    }
    
    /* Topbar memanjang full */
    .topbar {
        left: 0;
        padding: 0 16px;
    }
    
    /* Tampilkan tombol hamburger di topbar */
    .btn-mobile-menu {
        display: block !important;
        background: transparent;
        border: none;
        color: var(--slate-700);
        cursor: pointer;
        padding: 8px;
        margin-right: 12px;
    }
    
    /* Area Konten memanjang full */
    .main-wrap {
        margin-left: 0;
    }
    .main-content {
        padding: 16px;
    }
    
    /* Overlay untuk menutup sidebar */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(2px);
        z-index: 150;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .sidebar-overlay.open {
        display: block;
        opacity: 1;
    }
    
    /* Tabel responsif - bisa di-scroll horizontal */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Form flex menjadi kolom di HP */
    .form-group-flex {
        flex-direction: column !important;
        gap: 12px !important;
    }
    
    /* Modal full screen di HP */
    .modal {
        width: 95%;
        margin: 20px auto;
    }
}
CSS;

if (strpos($css, 'MOBILE RESPONSIVENESS') === false) {
    file_put_contents($cssFile, $css . "\n" . $mobileCSS);
}

// 2. UPDATE LAYOUT HTML
$layoutFile = 'c:\laragon\www\liveeditor\resources\views\layouts\guru.blade.php';
$html = file_get_contents($layoutFile);

// Add Hamburger Button to Topbar
$searchTopbar = '<header class="topbar">' . "\n" . '      <div>';
$replaceTopbar = <<<HTML
    <!-- Overlay Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <header class="topbar">
      <div style="display:flex;align-items:center;">
        <button class="btn-mobile-menu" onclick="toggleSidebar()" style="display:none;">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div>
HTML;

if (strpos($html, 'btn-mobile-menu') === false) {
    $html = str_replace($searchTopbar, $replaceTopbar, $html);
}

// Add Toggle JS
$js = <<<JS
  <script>
    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
  </script>
</body>
JS;

if (strpos($html, 'toggleSidebar()') === false) {
    $html = str_replace('</body>', $js, $html);
    file_put_contents($layoutFile, $html);
}

echo "Teacher mobile layout upgraded.\n";
?>