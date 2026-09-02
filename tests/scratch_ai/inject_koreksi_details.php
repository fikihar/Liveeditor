<?php
$file = 'c:\laragon\www\liveeditor\resources\views\guru\tugas\koreksi.blade.php';
$html = file_get_contents($file);

$detailsUI = <<<HTML
        @if(\$submission->grading_detail)
        <div style="margin-bottom:24px;background:#f8fafc;padding:16px;border-radius:8px;border:1px solid #e2e8f0;">
          <h4 style="font-weight:700;margin-bottom:12px;color:#334155;font-size:14px;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">Hasil Auto-Grading</h4>
          <ul style="list-style:none;padding:0;margin:0;font-size:13px;display:flex;flex-direction:column;gap:12px;">
            @foreach(json_decode(\$submission->grading_detail) as \$detail)
            <li>
              <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <strong style="color:#0f172a;">{{ \$detail->description }}</strong>
                <span style="font-weight:700;color:{{ \$detail->points_awarded > 0 ? (\$detail->points_awarded == \$detail->points_max ? '#16a34a' : '#d97706') : '#ef4444' }};">
                  {{ \$detail->points_awarded }} / {{ \$detail->points_max }}
                </span>
              </div>
              <div style="color:#64748b;">Info: {{ \$detail->note }}</div>
            </li>
            @endforeach
          </ul>
        </div>
        @endif
HTML;

if (strpos($html, 'Hasil Auto-Grading') === false) {
    $html = str_replace(
        '<form action="{{ route(\'guru.tugas.nilai\'',
        $detailsUI . "\n            " . '<form action="{{ route(\'guru.tugas.nilai\'',
        $html
    );
    file_put_contents($file, $html);
    echo "Koreksi view updated with grading details.\n";
} else {
    echo "Koreksi view already updated.\n";
}
?>