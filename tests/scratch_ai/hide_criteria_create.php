<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\create.blade.php';
$html = file_get_contents($file);

// Wrap criteria in a div
$html = str_replace(
    '<!-- KRITERIA PENILAIAN -->',
    '<div id="criteria-section"><!-- KRITERIA PENILAIAN -->',
    $html
);

$html = str_replace(
    'Tambah Kriteria Baru
        </button>',
    'Tambah Kriteria Baru
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
        
        function toggleCriteria() {
            if (typeSelect.value === 'tugas') {
                criteriaSection.style.display = 'block';
            } else {
                criteriaSection.style.display = 'none';
            }
        }
        
        typeSelect.addEventListener('change', toggleCriteria);
        toggleCriteria(); // initial load
    });
</script>
JS;

$html = str_replace('<script>', $js . "\n<script>", $html);
file_put_contents($file, $html);
echo "Create view updated.\n";
?>