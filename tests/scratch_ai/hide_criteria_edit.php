<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\edit.blade.php';
$html = file_get_contents($file);

// Ensure it hasn't been wrapped yet
if (strpos($html, '<div id="criteria-section">') === false) {
    // Wrap criteria in a div
    $html = str_replace(
        '<!-- KRITERIA PENILAIAN -->',
        '<div id="criteria-section"><!-- KRITERIA PENILAIAN -->',
        $html
    );

    $html = str_replace(
        'Tambah Kriteria
        </button>',
        'Tambah Kriteria
        </button>
        </div>',
        $html
    );

    // Add JS
    $js = <<<JS
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('select[name="type"]');
        const criteriaSection = document.getElementById('criteria-section');
        
        if(typeSelect && criteriaSection) {
            function toggleCriteria() {
                if (typeSelect.value === 'tugas') {
                    criteriaSection.style.display = 'block';
                } else {
                    criteriaSection.style.display = 'none';
                }
            }
            typeSelect.addEventListener('change', toggleCriteria);
            toggleCriteria(); // initial load
        }
    });
</script>
JS;

    $html = str_replace('<script>', $js . "\n<script>", $html);
    file_put_contents($file, $html);
    echo "Edit view updated.\n";
} else {
    echo "Edit view already updated.\n";
}
?>