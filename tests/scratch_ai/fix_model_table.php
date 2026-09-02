<?php
$file = 'c:\laragon\www\liveeditor\app\Models\GradingCriteria.php';
$content = file_get_contents($file);

if (strpos($content, 'protected $table') === false) {
    $content = str_replace(
        'use HasFactory;',
        "use HasFactory;\n\n    protected \$table = 'grading_criteria';",
        $content
    );
    file_put_contents($file, $content);
    echo "Model updated with exact table name.\n";
} else {
    echo "Table already set.\n";
}
?>